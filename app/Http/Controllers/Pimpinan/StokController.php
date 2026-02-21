<?php

namespace App\Http\Controllers\Pimpinan;

use App\Models\Barang;
use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Obat;
use App\Models\Satuan;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
            'dosis',
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
            'id_stok',
            'dosis'
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

        return view('views.pimpinan.stok', compact(
            'rakStoks',
            'stoks',
            'obats',
            'jeniss',
            'kategoris',
            'satuans',
            'lokasis'
        ));
    }
    public function show($id)
    {
        $stok = Barang::with(['obat', 'jenis', 'satuan'])->findOrFail($id);

        return view('views.pimpinan.obat_detail', compact('obat'));
    }
}
