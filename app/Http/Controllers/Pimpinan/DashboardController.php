<?php

namespace App\Http\Controllers\Pimpinan;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('views.layouts.app');
    }

    public function dashboard(Request $request)
    {
        $dari   = $request->get('dari_tanggal');
        $sampai = $request->get('sampai_tanggal');

        $stokAktif = StokBarang::with([
            'obat',
            'jenis',
            'kategori',
            'satuan',
            'lokasi'
        ])
            ->where('jumlah_stok', '>', 0)
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();

        $barangKeluar = BarangKeluar::when(
            $dari && $sampai,
            fn($q) => $q->whereBetween('created_at', [$dari, $sampai])
        )->get();

        $totalBatch  = $stokAktif->count();
        $totalStok   = $stokAktif->sum('jumlah_stok');
        $totalKeluar = $barangKeluar->sum('jumlah_keluar');

        $stokKeluarBulanan = BarangKeluar::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
            DB::raw('SUM(jumlah_keluar) as total')
        )
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('created_at', [$dari, $sampai])
            )
            ->groupBy('periode')
            ->orderBy('periode')
            ->pluck('total', 'periode');

        $stokExpiredBulanan = StokBarang::select(
            DB::raw("DATE_FORMAT(tanggal_kadaluarsa, '%Y-%m') as periode"),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('tanggal_kadaluarsa', [
                now()->startOfDay(),
                now()->addDays(30)->endOfDay()
            ])
            ->groupBy('periode')
            ->orderBy('periode')
            ->pluck('total', 'periode');

        return view('views.pimpinan.dashboard', [
            'stokAktif'           => $stokAktif,

            // card
            'totalBatch'          => $totalBatch,
            'totalStok'           => $totalStok,
            'totalKeluar'         => $totalKeluar,

            // grafik
            'stokKeluarBulanan'   => $stokKeluarBulanan,
            'stokExpiredBulanan'  => $stokExpiredBulanan,

            // filter
            'dari'                => $dari,
            'sampai'              => $sampai,
        ]);
    }
}
