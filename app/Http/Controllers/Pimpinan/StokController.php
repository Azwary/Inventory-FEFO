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
