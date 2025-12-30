<?php

namespace App\Http\Controllers\Pimpinan;

use App\Models\BarangKeluar;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $stokAktif = StokBarang::with([
            'obat',
            'jenis',
            'kategori',
            'satuan',
            'lokasi'
        ])
            ->where('jumlah_masuk', '>', 0)
            ->when(
                $dari && $sampai,
                fn($q) =>
                $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_kadaluarsa')
            ->get();

        $barangKeluar = BarangKeluar::when(
            $dari && $sampai,
            fn($q) =>
            $q->whereBetween('created_at', [$dari, $sampai])
        )->get();

        return view('views.pimpinan.laporan', [
            'stokAktif'   => $stokAktif,
            'totalBatch' => $stokAktif->count(),
            'totalStok'  => $stokAktif->sum('jumlah_masuk'),
            'totalKeluar' => $barangKeluar->sum('jumlah'),
            'dari'       => $dari,
            'sampai'     => $sampai
        ]);
    }

    public function exportCsv(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $stokAktif = StokBarang::with(['obat', 'lokasi'])
            ->where('jumlah_masuk', '>', 0)
            ->when(
                $dari && $sampai,
                fn($q) =>
                $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_kadaluarsa')
            ->get();

        return new StreamedResponse(function () use ($stokAktif) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Nama Obat',
                'Batch',
                'Lokasi',
                'Tanggal Masuk',
                'Kadaluarsa',
                'Jumlah'
            ]);

            foreach ($stokAktif as $s) {
                fputcsv($handle, [
                    $s->obat?->nama_obat ?? '-',
                    $s->nomor_batch,
                    $s->lokasi?->nama_lokasi ?? '-',
                    $s->tanggal_masuk,
                    $s->tanggal_kadaluarsa,
                    $s->jumlah_masuk
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
            'attachment; filename=laporan_stok_' . now()->format('Ymd_His') . '.csv',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $stokAktif = StokBarang::with(['obat', 'lokasi'])
            ->where('jumlah_masuk', '>', 0)
            ->when(
                $dari && $sampai,
                fn($q) =>
                $q->whereBetween('tanggal_masuk', [$dari, $sampai])
            )
            ->orderBy('tanggal_kadaluarsa')
            ->get();

        return Pdf::loadView('views.admin.laporan_pdf', [
            'stokAktif' => $stokAktif,
            'dari' => $dari,
            'sampai' => $sampai
        ])
            ->setPaper('A4', 'landscape')
            ->download('laporan_stok_' . now()->format('Ymd_His') . '.pdf');
    }
}
