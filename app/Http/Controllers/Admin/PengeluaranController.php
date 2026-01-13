<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
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
        $stoks = StokBarang::with('barang.obat')
            ->where('jumlah_stok', '>', 0)
            ->orderBy('tanggal_kadaluarsa')
            ->get();

        // Ambil histori transaksi KELUAR urut waktu (PENTING)
        $historiKeluar = BarangKeluar::with('barang.obat')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('id_barang')
            ->flatMap(function ($transaksiPerBarang) {

                // total masuk sebagai stok awal
                $stokAwal = \App\Models\BarangMasuk::where(
                    'id_barang',
                    $transaksiPerBarang->first()->id_barang
                )->sum('jumlah_masuk');

                $sisa = $stokAwal;

                return $transaksiPerBarang->map(function ($trx) use (&$sisa) {
                    $sisa -= $trx->jumlah_keluar;
                    $trx->sisa_stok_transaksi = $sisa;
                    return $trx;
                });
            })
            ->sortByDesc('created_at')
            ->values();

        return view('views.admin.pengeluaran', compact(
            'stoks',
            'historiKeluar'
        ));
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
        $request->validate([
            'nama_obat'          => 'required|exists:stok_barang,id_stok',
            'jumlah_pengeluaran' => 'required|integer|min:1',
            'keterangan'         => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Ambil STOK BATCH (FEFO)
            $stok = StokBarang::lockForUpdate()
                ->where('id_stok', $request->nama_obat)
                ->firstOrFail();

            // 2️⃣ Ambil BARANG
            $barang = \App\Models\Barang::lockForUpdate()
                ->where('id_barang', $stok->id_barang)
                ->firstOrFail();

            // 3️⃣ Ambil PERSEDIAAN (TOTAL)
            $persediaan = Persediaan::lockForUpdate()
                ->where('id_persediaan', $barang->id_persediaan)
                ->firstOrFail();

            // 4️⃣ Validasi stok
            if ($stok->jumlah_stok < $request->jumlah_pengeluaran) {
                throw new \Exception('Stok batch tidak mencukupi');
            }

            if ($persediaan->stok_barang < $request->jumlah_pengeluaran) {
                throw new \Exception('Stok total tidak mencukupi');
            }

            // 5️⃣ Generate ID
            $idBarangKeluar = $this->generateId(
                new BarangKeluar(),
                'id_keluar',
                'BRK',
                2
            );

            // 6️⃣ Simpan TRANSAKSI BARANG KELUAR
            BarangKeluar::create([
                'id_keluar'     => $idBarangKeluar,
                'id_barang'     => $stok->id_barang,
                'id_user'       => Auth::id(),
                'jumlah_keluar' => $request->jumlah_pengeluaran,
                'keterangan'    => $request->keterangan,
            ]);

            // 7️⃣ UPDATE STOK (FEFO)
            $stok->decrement('jumlah_stok', $request->jumlah_pengeluaran);

            // 8️⃣ UPDATE PERSEDIAAN (TOTAL)
            $persediaan->decrement('stok_barang', $request->jumlah_pengeluaran);
        });

        return back()->with('success', 'Pengeluaran obat berhasil diproses (FEFO)');
    }



    // public function destroy($id)
    // {
    //     $barangKeluar = BarangKeluar::findOrFail($id);

    //     DB::transaction(function () use ($barangKeluar) {

    //         $tr = TrBarangKeluar::where('id_keluar', $barangKeluar->id_keluar)->first();
    //         $stok = StokBarang::where('id_barang', $barangKeluar->id_barang)->first();

    //         // Kembalikan stok
    //         if ($stok && $tr) {
    //             $stok->increment('jumlah_stok', $tr->jumlah_keluar);
    //         }

    //         $tr?->delete();
    //         $barangKeluar->delete();
    //     });

    //     return back()->with('success', 'Pengeluaran berhasil dibatalkan');
    // }
}
