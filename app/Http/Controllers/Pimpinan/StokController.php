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
        $orderExp = $request->get('exp', 'asc');
        $search   = $request->get('search');

        $today = Carbon::today()->startOfDay();
        $warningDate = Carbon::today()->addDays(30)->endOfDay();

        $rakStoks = StokBarang::select(
            'id_lokasi',

            DB::raw("
        SUM(
            CASE
                WHEN jumlah_stok > 0 THEN jumlah_stok
                ELSE 0
            END
        ) AS jumlah_item
    "),

            DB::raw("
        SUM(
            CASE
                WHEN jumlah_stok > 0
                AND tanggal_kadaluarsa IS NOT NULL
                AND tanggal_kadaluarsa < ?
                THEN 1
                ELSE 0
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
                THEN 1
                ELSE 0
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


        $stoks = StokBarang::with([
            'barang.obat',
            'barang.jenis',
            'barang.kategori',
            'barang.satuan',
            'lokasi'
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('barang.obat', function ($q) use ($search) {
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

    public function show($id)
    {
        $stok = Barang::with(['obat', 'jenis', 'satuan'])->findOrFail($id);

        return view('views.pimpinan.obat_detail', compact('obat'));
    }
}
