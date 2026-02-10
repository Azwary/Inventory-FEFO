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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{

    public function index(Request $request)
    {
        // =====================
        // VALIDASI INPUT
        // =====================
        $search  = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // FEFO
        $orderExp = $request->get('exp', 'asc');
        $orderExp = in_array($orderExp, ['asc', 'desc']) ? $orderExp : 'asc';

        // SORT TABEL
        $sort      = $request->get('sort');
        $direction = $request->get('direction', 'asc');
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        // WHITELIST SORT
        $allowedSort = [
            'tanggal_kadaluarsa',
            'tanggal_masuk',
            'nomor_batch',
            'jumlah_stok',
            'id_stok',
            'nama_obat',
            'nama_lokasi',
        ];

        if (!in_array($sort, $allowedSort)) {
            $sort = null; // penting: biar FEFO bisa aktif
        }

        // =====================
        // TANGGAL
        // =====================
        $today       = Carbon::today()->startOfDay();
        $warningDate = Carbon::today()->addDays(30)->endOfDay();

        // =====================
        // RAK STOK
        // =====================
        $rakStoks = StokBarang::select(
            'id_lokasi',
            DB::raw("
            SUM(CASE WHEN jumlah_stok > 0 THEN jumlah_stok ELSE 0 END) AS jumlah_item
        "),
            DB::raw("
            SUM(
                CASE
                    WHEN jumlah_stok > 0
                    AND tanggal_kadaluarsa IS NOT NULL
                    AND tanggal_kadaluarsa < ?
                    THEN 1 ELSE 0
                END
            ) AS expired_item
        "),
            DB::raw("
            SUM(
                CASE
                    WHEN jumlah_stok > 0
                    AND tanggal_kadaluarsa IS NOT NULL
                    AND tanggal_kadaluarsa >= ?
                    AND tanggal_kadaluarsa <= ?
                    THEN 1 ELSE 0
                END
            ) AS warning_item
        ")
        )
            ->with('lokasi')
            ->groupBy('id_lokasi')
            ->addBinding([
                $today->toDateString(),
                $today->toDateString(),
                $warningDate->toDateString(),
            ], 'select')
            ->get();

        // =====================
        // QUERY DASAR STOK
        // =====================
        $stoks = StokBarang::query()
            ->select('stok_barang.*')
            ->join('barang', 'barang.id_barang', '=', 'stok_barang.id_barang')
            ->join('obat', 'obat.id_obat', '=', 'barang.id_obat')
            ->leftJoin('lokasi', 'lokasi.id_lokasi', '=', 'stok_barang.id_lokasi')
            ->with([
                'barang.obat',
                'barang.jenis',
                'barang.kategori',
                'barang.satuan',
                'lokasi'
            ])
            ->where('stok_barang.jumlah_stok', '>', 0)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('obat.nama_obat', 'like', "%{$search}%")
                        ->orWhere('stok_barang.nomor_batch', 'like', "%{$search}%");
                });
            });

        // =====================
        // SORT & FEFO (ANTI TABRAKAN)
        // =====================
        if ($sort === 'nama_obat') {

            $stoks->orderBy('obat.nama_obat', $direction);
        } elseif ($sort === 'nama_lokasi') {

            $stoks->orderBy('lokasi.nama_lokasi', $direction);
        } elseif (in_array($sort, [
            'tanggal_kadaluarsa',
            'tanggal_masuk',
            'nomor_batch',
            'jumlah_stok',
            'id_stok'
        ])) {

            // SORT MANUAL DARI HEADER
            $stoks->orderBy("stok_barang.$sort", $direction);
        } else {

            // FEFO DEFAULT
            $stoks->orderBy('stok_barang.tanggal_kadaluarsa', $orderExp);
        }

        // =====================
        // PAGINATION
        // =====================
        $stoks = $stoks->paginate($perPage)->withQueryString();

        // =====================
        // MASTER DATA
        // =====================
        $obats     = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss    = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans   = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis   = Lokasi::select('id_lokasi', 'nama_lokasi')->get();

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
        $IdPersediaan    = $this->generateId(new Persediaan(), 'id_persediaan', 'PR', 3);

        $NoBatch = $this->generateId(new StokBarang(), 'nomor_batch', 'NB', 3);

        $barangData = [
            'id_barang' => $IdBarang,
            'id_obat' => $request->nama_obat,
            'id_jenis' => $request->jenis,
            'id_kategori' => $request->kategori,
            'id_satuan' => $request->satuan,
            'id_persediaan' => $IdPersediaan,
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
            'jumlah_stok' => $request->jumlah,
        ];
        StokBarang::create($stokData);

        $barangMasukData = [
            'id_masuk' => $IdBarangMasuk,
            'id_barang' => $IdBarang,
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_user' => Auth::user()->id,
            'jumlah_masuk' => $request->jumlah,
            'keterangan' => "Pembelian",
        ];
        BarangMasuk::create($barangMasukData);

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
