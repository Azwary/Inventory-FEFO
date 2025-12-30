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
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{

    public function index(Request $request)
    {
        $orderExp = $request->get('exp', 'asc');
        $search   = $request->get('search');

        $rakStoks = StokBarang::select(
            'id_lokasi',
            DB::raw('COUNT(*) AS jumlah_item'),
            DB::raw("
                SUM(
                    CASE
                        WHEN tanggal_kadaluarsa IS NOT NULL
                        AND tanggal_kadaluarsa BETWEEN CURDATE()
                        AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                        THEN 1 ELSE 0
                    END
                ) AS warning_item
            ")
        )
            ->with('lokasi')
            ->groupBy('id_lokasi')
            ->get();

        $stoks = StokBarang::with([
            'obat',
            'jenis',
            'kategori',
            'satuan',
            'lokasi'
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('obat', function ($q) use ($search) {
                    $q->where('nama_obat', 'like', "%{$search}%");
                })
                    ->orWhere('nomor_batch', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_kadaluarsa', $orderExp)
            ->get();

        $obats = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis = Lokasi::select('id_lokasi', 'nama_lokasi')->get();

        return view('views.admin.stok', compact(
            'rakStoks',
            'stoks',
            'obats',
            'jeniss',
            'kategoris',
            'satuans',
            'lokasis'
        ));
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


    private function generateId($model, $column, $prefix, $length)
    {
        $last = $model::orderByRaw(
            "CAST(SUBSTRING(`$column`, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC"
        )->first();

        $number = $last ? (int) substr($last->{$column}, strlen($prefix)) + 1 : 1;

        do {
            $id = $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
            $exists = $model::where($column, $id)->exists();
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
            'kategori' => 'required|exists:kategori,id_kategori',
            'satuan' => 'required|exists:satuan,id_satuan',
            'tanggal_masuk' => 'required|date',
            'tanggal_exp' => 'required|date|after_or_equal:tanggal_masuk',
            'lokasi' => 'required|exists:lokasi,id_lokasi',
        ]);

        $IdBarang        = $this->generateId(new Barang(), 'id_barang', 'BR', 3);
        $IdStok          = $this->generateId(new StokBarang(), 'id_stok', 'STB', 2);
        $IdBarangMasuk   = $this->generateId(new BarangMasuk(), 'id_masuk', 'BRM', 2);
        $IdTrBarangMasuk = $this->generateId(new TrBarangMasuk(), 'id_tr_masuk', 'TR', 3);
        $IdPersediaan    = $this->generateId(new Persediaan(), 'id_persediaan', 'PR', 3);

        $NoBatch = $this->generateId(new StokBarang(), 'nomor_batch', 'NB', 3);



        $barangData = [
            'id_barang' => $IdBarang,
            'id_obat' => $request->nama_obat,
            'id_jenis' => $request->jenis,
            'id_kategori' => $request->kategori,
            'id_satuan' => $request->satuan,
            'id_lokasi' => $request->lokasi,
        ];
        Barang::create($barangData);


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


        $barangMasukData = [
            'id_masuk' => $IdBarangMasuk,
            'id_barang' => $IdBarang,
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_user' => Auth::user()->id,
        ];
        BarangMasuk::create($barangMasukData);


        $trMasukData = [
            'id_tr_masuk' => $IdTrBarangMasuk,
            'jml_barang_masuk' => $request->jumlah,
        ];
        TrBarangMasuk::create($trMasukData);


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


    // public function store(Request $request)
    // {
    //     return response()->json($request->all());
    // }


    public function show($id)
    {
        $stok = Barang::with(['obat', 'jenis', 'satuan'])->findOrFail($id);
        return view('views.admin.sto', compact('stok'));
    }


    public function edit($id)
    {
        $stok = Barang::findOrFail($id);
        $obats = Obat::all();
        $jeniss = Jenis::all();
        $satuans = Satuan::all();

        return view('views.admin.sto', compact('stok', 'obats', 'jeniss', 'satuans'));
    }


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


    public function destroy($id)
    {
        $stok = Barang::findOrFail($id);
        $stok->delete();

        return redirect()->route('views.admin.stok')->with('success', 'Stok obat berhasil dihapus.');
    }
}
