<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{

    public function index(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $barangMasuk = BarangMasuk::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_masuk')
            ->get();

        $barangKeluar = BarangKeluar::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('created_at', [$dari, $sampai])
            )
            ->orderBy('created_at')
            ->get();

        return view('views.admin.laporan', [
            'barangMasuk'   => $barangMasuk,
            'barangKeluar'  => $barangKeluar,
            'totalMasuk'    => $barangMasuk->sum('jumlah_masuk'),
            'totalKeluar'   => $barangKeluar->sum('jumlah_keluar'),
            'dari'          => $dari,
            'sampai'        => $sampai
        ]);
    }

    public function exportCsv(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $masuk = BarangMasuk::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )->get();

        $keluar = BarangKeluar::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('created_at', [$dari, $sampai])
            )->get();

        return new StreamedResponse(function () use ($masuk, $keluar) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Jenis Transaksi',
                'Nama Barang',
                'Tanggal',
                'Jumlah',
                'Keterangan'
            ], ';');

            foreach ($masuk as $m) {
                fputcsv($handle, [
                    'Masuk',
                    $m->barang?->obat?->nama_obat ?? '-',
                    $m->tanggal_masuk,
                    $m->jumlah_masuk,
                    $m->keterangan
                ], ';');
            }

            foreach ($keluar as $k) {
                fputcsv($handle, [
                    'Keluar',
                    $k->barang?->obat?->nama_obat ?? '-',
                    $k->created_at->format('Y-m-d'),
                    $k->jumlah_keluar,
                    $k->keterangan
                ], ';');
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' =>
            'attachment; filename=laporan_transaksi_' . now()->format('Ymd_His') . '.csv',
        ]);
    }


    public function exportPdf(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $barangMasuk = BarangMasuk::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )->get();

        $barangKeluar = BarangKeluar::with('barang')
            ->when(
                $dari && $sampai,
                fn($q) => $q->whereBetween('created_at', [$dari, $sampai])
            )->get();

        return Pdf::loadView('views.admin.laporan_pdf', [
            'barangMasuk'  => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'dari'         => $dari,
            'sampai'       => $sampai
        ])
            ->setPaper('A4', 'landscape')
            ->download('laporan_transaksi_' . now()->format('Ymd_His') . '.pdf');
    }
}
