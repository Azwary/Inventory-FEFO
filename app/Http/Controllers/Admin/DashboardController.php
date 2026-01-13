<?php

namespace App\Http\Controllers\Admin;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
                fn($q) =>
                $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();


        $barangKeluar = BarangKeluar::when(
            $dari && $sampai,
            fn($q) =>
            $q->whereBetween('created_at', [$dari, $sampai])
        )->get();


        $totalBatchAktif = $stokAktif->count();
        $totalStok       = $stokAktif->sum('jumlah_stok');
        $totalKeluar     = $barangKeluar->sum('jumlah_keluar');

        $stokKadaluarsa = $stokAktif->where(
            'tanggal_kadaluarsa',
            '<',
            Carbon::today()
        )->count();

        return view('views.admin.dashboard', [
            'stokAktif'       => $stokAktif,
            'totalBatch'      => $totalBatchAktif,
            'totalStok'       => $totalStok,
            'totalKeluar'     => $totalKeluar,
            'stokKadaluarsa'  => $stokKadaluarsa,
            'dari'            => $dari,
            'sampai'          => $sampai,
        ]);
    }
}
