<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Obat;
use App\Models\Persediaan;
use App\Models\Satuan;
use App\Models\StokBarang;
use App\Models\TrBarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{


    public function index()
    {
        $stoks = StokBarang::with(['obat', 'jenis', 'kategori', 'satuan'])->get();
        $obats = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis = Lokasi::select('id_lokasi', 'nama_lokasi')->get();


        return view('views.admin.stok', compact('stoks', 'obats', 'jeniss', 'kategoris', 'satuans', 'lokasis'));
    }

    public function create()
    {
        $obats = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis = Lokasi::select('id_lokasi', 'nama_lokasi')->get();


        return view('views.admin.stok', compact('obats', 'jeniss', 'kategoris', 'satuans', 'lokasis'));
    }


    private function generateId($model, $prefix, $length)
    {
        $primaryKey = $model->getKeyName(); // ambil nama kolom PK otomatis

        // Gunakan backtick agar MySQL mengenali kolom
        $last = $model::orderByRaw(
            "CAST(SUBSTRING(`$primaryKey`, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC"
        )->first();

        $number = $last ? (int) substr($last->{$primaryKey}, strlen($prefix)) + 1 : 1;

        do {
            $id = $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
            $exists = $model::where($primaryKey, $id)->exists();
            if ($exists) $number++;
        } while ($exists);

        return $id;
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|exists:obat,id_obat',
            'jumlah' => 'required|integer|min:1',
            'jenis' => 'required|exists:jenis,id_jenis',
            'satuan' => 'required|exists:satuan,id_satuan',
            'tanggal_masuk' => 'required|date',
            'tanggal_exp' => 'required|date|after_or_equal:tanggal_masuk',
            'lokasi' => 'required|exists:lokasi,id_lokasi',
        ]);

        // Generate semua ID unik
        $IdBarang = $this->generateId(new Barang(), 'BR', 3);
        $NoBatch = $this->generateId(new StokBarang(), 'NB', 3);
        $IdStok = $this->generateId(new StokBarang(), 'STB', 2);
        $IdBarangMasuk = $this->generateId(new BarangMasuk(), 'BRM', 2);
        $IdTrBarangMasuk = $this->generateId(new TrBarangMasuk(), 'TR', 3);
        $IdPersediaan = $this->generateId(new Persediaan(), 'PR', 3);

        // 1. Barang
        $barangData = [
            'id_barang' => $IdBarang,
            'id_obat' => $request->nama_obat,
            'id_jenis' => $request->jenis,
            'id_kategori' => $request->kategori,
            'id_satuan' => $request->satuan,
            'id_lokasi' => $request->lokasi,
        ];
        Barang::create($barangData);

        // 2. Stok Barang
        $stokData = [
            'id_stok' => $IdStok,
            'id_barang' => $IdBarang,
            'id_lokasi' => $request->lokasi,
            'nomor_batch' => $NoBatch,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_kadaluarsa' => $request->tanggal_exp,
            'jumlah_masuk' => $request->jumlah,
        ];
        StokBarang::create($stokData);

        // 3. Barang Masuk
        $barangMasukData = [
            'id_masuk' => $IdBarangMasuk,
            'id_barang' => $IdBarang,
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_user' => Auth::user()->id,
        ];
        BarangMasuk::create($barangMasukData);

        // 4. TrBarangMasuk
        $trMasukData = [
            'id_tr_masuk' => $IdTrBarangMasuk,
            'jml_barang_masuk' => $request->jumlah,
        ];
        TrBarangMasuk::create($trMasukData);

        // 5. Persediaan
        $lastPersediaan = Persediaan::orderBy('id_persediaan', 'desc')->first();
        $stokLama = $lastPersediaan ? $lastPersediaan->stok_barang : 0;
        $stokBaru = $stokLama + $request->jumlah;

        $persediaanData = [
            'id_persediaan' => $IdPersediaan,
            'stok_barang' => $stokBaru,
        ];
        Persediaan::create($persediaanData);

        return redirect()->route('admin.stok.index')->with('success', 'Stok obat berhasil ditambahkan.');
    }

    // Controller sementara
    // public function store(Request $request)
    // {
    //     return response()->json($request->all());
    // }


    // Tampilkan detail stok
    public function show($id)
    {
        $stok = Barang::with(['obat', 'jenis', 'satuan'])->findOrFail($id);
        return view('views.admin.sto', compact('stok'));
    }

    // Form edit stok
    public function edit($id)
    {
        $stok = Barang::findOrFail($id);
        $obats = Obat::all();
        $jeniss = Jenis::all();
        $satuans = Satuan::all();

        return view('views.admin.sto', compact('stok', 'obats', 'jeniss', 'satuans'));
    }

    // Update stok
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required|exists:obats,id',
            'batch' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'jjenis' => 'required|exists:jeniss,id_jenis',
            'satuan' => 'required|exists:satuans,id_satuan',
            'tanggal_masuk' => 'required|date',
            'tanggal_exp' => 'required|date|after_or_equal:tanggal_masuk',
            'lokasi' => 'required|string|max:100',
        ]);

        $stok = Barang::findOrFail($id);
        $stok->update([
            'obat_id' => $request->nama_obat,
            'batch' => $request->batch,
            'jumlah' => $request->jumlah,
            'jenis_id' => $request->jjenis,
            'satuan_id' => $request->satuan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_exp' => $request->tanggal_exp,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('views.admin.stok')->with('success', 'Stok obat berhasil diupdate.');
    }

    // Hapus stok
    public function destroy($id)
    {
        $stok = Barang::findOrFail($id);
        $stok->delete();

        return redirect()->route('views.admin.stok')->with('success', 'Stok obat berhasil dihapus.');
    }
}
