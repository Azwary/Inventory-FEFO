<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\Persediaan;
use App\Models\StokBarang;
use App\Models\TrBarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $stoks = StokBarang::with([
            'obat',
            'jenis',
            'kategori',
            'satuan',
            'lokasi'
        ])
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();

        return view('views.admin.pengeluaran', compact('stoks'));
    }


    /**
     * Helper generate ID (sama persis seperti StokController)
     */
    private function generateId($model, $column, $prefix, $length)
    {
        // Ambil angka terbesar dari ID (AMAN CAST)
        $lastNumber = $model::selectRaw(
            "MAX(CAST(SUBSTRING(`$column`, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) as max_number"
        )
            ->where($column, 'LIKE', $prefix . '%')
            ->value('max_number');
        $number = $lastNumber ? $lastNumber + 1 : 1;

        do {
            $id = $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
            $exists = $model::where($column, $id)->exists();
            if ($exists) {
                $number++;
            }
        } while ($exists);

        return $id;
    }


    public function store(Request $request)
    {
        // =========================
        // VALIDASI
        // =========================
        $request->validate([
            'nama_obat'          => 'required|exists:stok_barang,id_stok',
            'jumlah_pengeluaran' => 'required|integer|min:1',
            'keterangan'         => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            // =========================
            // AMBIL STOK (FEFO + LOCK)
            // =========================
            $stok = StokBarang::lockForUpdate()
                ->where('id_stok', $request->nama_obat)
                ->firstOrFail();

            if ($stok->jumlah_masuk < $request->jumlah_pengeluaran) {
                throw new \Exception('Stok tidak mencukupi');
            }

            // =========================
            // GENERATE ID
            // =========================
            $IdBarangKeluar   = $this->generateId(new BarangKeluar(), 'id_keluar', 'BRK', 2);
            $IdTrBarangKeluar = $this->generateId(new TrBarangKeluar(), 'id_tr_keluar', 'TRK', 2);
            $IdPersediaan     = $this->generateId(new Persediaan(), 'id_persediaan', 'PR', 3);

            // =========================
            // 1️⃣ BARANG KELUAR
            // =========================
            $barangKeluar = BarangKeluar::create([
                'id_keluar' => $IdBarangKeluar,
                'id_barang' => $stok->id_barang,
                'id_user'   => Auth::user()->id,
                'jumlah'    => $request->jumlah_pengeluaran,
            ]);

            if (!$barangKeluar) {
                throw new \Exception('Gagal insert barang_keluar');
            }

            // =========================
            // 2️⃣ TRANSAKSI BARANG KELUAR
            // =========================
            $trBarangKeluar = TrBarangKeluar::create([
                'id_tr_keluar' => $IdTrBarangKeluar,
                'id_keluar'    => $IdBarangKeluar,
                'keterangan'   => $request->keterangan,
            ]);

            if (!$trBarangKeluar) {
                throw new \Exception('Gagal insert tr_barang_keluar');
            }

            // =========================
            // 3️⃣ UPDATE STOK
            // =========================
            $stok->decrement('jumlah_masuk', $request->jumlah_pengeluaran);

            // =========================
            // 4️⃣ UPDATE PERSEDIAAN
            // =========================
            $lastPersediaan = Persediaan::orderBy('id_persediaan', 'desc')->first();
            $stokLama = $lastPersediaan ? $lastPersediaan->stok_barang : 0;

            Persediaan::create([
                'id_persediaan' => $IdPersediaan,
                'stok_barang'   => $stokLama - $request->jumlah_pengeluaran,
            ]);

            DB::commit();

            return back()->with('success', 'Pengeluaran obat berhasil diproses');
        } catch (\Throwable $e) {

            DB::rollBack();

            // 🔴 DEBUG LANGSUNG KELIHATAN
            dd([
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);
        }
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        DB::transaction(function () use ($barangKeluar) {

            $tr = TrBarangKeluar::where('id_keluar', $barangKeluar->id_keluar)->first();
            $stok = StokBarang::where('id_barang', $barangKeluar->id_barang)->first();

            // Kembalikan stok
            if ($stok && $tr) {
                $stok->increment('jumlah_masuk', $tr->jumlah_keluar);
            }

            $tr?->delete();
            $barangKeluar->delete();
        });

        return back()->with('success', 'Pengeluaran berhasil dibatalkan');
    }
}
