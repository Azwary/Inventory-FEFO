<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Obat;
use App\Models\Satuan;
use App\Models\StokBarang;
use App\Models\TrBarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    // Menampilkan list stok obat
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

    // Form tambah stok
    public function create()
    {
        $obats = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis = Lokasi::select('id_lokasi', 'nama_lokasi')->get();


        return view('views.admin.stok', compact('obats', 'jeniss', 'kategoris', 'satuans', 'lokasis'));
    }

    // Simpan stok baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|exists:obat,id_obat',
            'batch' => 'required|string|max:50',
            'jumlah' => 'required|integer|min:1',
            'jenis' => 'required|exists:jenis,id_jenis',
            'satuan' => 'required|exists:satuan,id_satuan',
            'tanggal_masuk' => 'required|date',
            'tanggal_exp' => 'required|date|after_or_equal:tanggal_masuk',
            'lokasi' => 'required|exists:lokasi,id_lokasi',
        ]);

        // ID Barang
        $lastBarang = Barang::orderBy('id_barang', 'desc')->first();
        $newNumber = $lastBarang ? (int) substr($lastBarang->id_barang, 2) + 1 : 1;
        $Idbarang = 'BR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $barangData = [
            'id_barang' => $Idbarang,
            'id_obat' => $request->nama_obat,
            'id_jenis' => $request->jenis,
            'id_kategori' => $request->kategori,
            'id_satuan' => $request->satuan,
            'id_lokasi' => $request->lokasi,
        ];
        // dd('Barang:', $barangData);
        Barang::create($barangData);

        // ID Stok
        $lastStok = StokBarang::orderBy('id_stok', 'desc')->first();
        $newNumber = $lastStok ? (int) substr($lastStok->id_stok, 2) + 1 : 1;
        $IdStok = 'ST' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $stokData = [
            'id_stok' => $IdStok,
            'id_barang' => $Idbarang,
            'id_lokasi' => $request->lokasi,
            'nomor_batch' => $request->batch,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_kadaluarsa' => $request->tanggal_exp,
            'jumlah_masuk' => $request->jumlah,
        ];
        // dd('StokBarang:', $stokData);
        StokBarang::create($stokData);

        // BarangMasuk
        $lastBarang = Barang::orderBy('id_barang', 'desc')->first();
        $newNumber = $lastBarang ? (int) substr($lastBarang->id_barang, 2) + 1 : 1;
        $Idbarang = 'BR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $barangMasukData = [
            'id_masuk' => $request->batch,
            'id_barang' => $Idbarang,
            'id_user' => Auth::user()->id,
            'jumlah' => $request->jumlah,
        ];
        dd('BarangMasuk:', $barangMasukData);
        BarangMasuk::create($barangMasukData);

        // TrBarangMasuk
        $lastTrMasuk = TrBarangMasuk::orderBy('id_tr_masuk', 'desc')->first();
        $newNumber = $lastTrMasuk ? (int) substr($lastTrMasuk->id_tr_masuk, 2) + 1 : 1;
        $IdTrMasuk = 'TR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $trMasukData = [
            'barang_id' => $IdTrMasuk,
            'jumlah' => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk,
        ];
        dd('TrBarangMasuk:', $trMasukData);
        TrBarangMasuk::create($trMasukData);

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
