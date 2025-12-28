<?php

namespace App\Http\Controllers\Pimpinan;

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
    public function index()
    {
        // Data rak (lokasi + agregasi stok)
        $stoks = StokBarang::select(
            'id_lokasi',
            DB::raw('COUNT(*) AS jumlah_item'),
            DB::raw("
                SUM(
                    CASE
                        WHEN tanggal_kadaluarsa IS NOT NULL
                        AND tanggal_kadaluarsa BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                        THEN 1 ELSE 0
                    END
                ) AS warning_item
            ")
        )
            ->with('lokasi')
            ->groupBy('id_lokasi')
            ->get();

        $stokss = StokBarang::with(['obat', 'jenis', 'kategori', 'satuan'])->get();
        $obats = Obat::select('id_obat', 'nama_obat')->get();
        $jeniss = Jenis::select('id_jenis', 'nama_jenis')->get();
        $kategoris = Kategori::select('id_kategori', 'nama_kategori')->get();
        $satuans = Satuan::select('id_satuan', 'nama_satuan')->get();
        $lokasis = Lokasi::select('id_lokasi', 'nama_lokasi')->get();
        return view('views.pimpinan.stok', compact(
            'stoks',
            'stokss',
            'obats',
            'jeniss',
            'kategoris',
            'satuans',
            'lokasis'
        ));
    }
    // Menampilkan detail obat berdasarkan id
    public function show($id)
    {
        $obats = collect([
            1 => (object)[
                'id' => 1,
                'kode' => 'OB001',
                'nama_obat' => 'Paracetamol',
                'batch' => 'B001',
                'tanggal_masuk' => Carbon::parse('2025-12-20'),
                'tanggal_exp' => Carbon::parse('2025-12-31'),
                'jumlah' => 100,
                'lokasi' => 'Gudang A',
                'deskripsi' => 'Obat untuk meredakan demam dan nyeri ringan.',
            ],
            2 => (object)[
                'id' => 2,
                'kode' => 'OB002',
                'nama_obat' => 'Amoxicillin',
                'batch' => 'B002',
                'tanggal_masuk' => Carbon::parse('2025-12-25'),
                'tanggal_exp' => Carbon::parse('2026-01-10'),
                'jumlah' => 50,
                'lokasi' => 'Gudang B',
                'deskripsi' => 'Antibiotik golongan penisilin.',
            ],
            3 => (object)[
                'id' => 3,
                'kode' => 'OB003',
                'nama_obat' => 'Vitamin C',
                'batch' => 'B003',
                'tanggal_masuk' => Carbon::parse('2025-12-15'),
                'tanggal_exp' => Carbon::parse('2026-03-01'),
                'jumlah' => 200,
                'lokasi' => 'Gudang A',
                'deskripsi' => 'Vitamin untuk menjaga daya tahan tubuh.',
            ],
        ]);

        $obat = $obats[$id] ?? abort(404);

        return view('views.pimpinan.obat_detail', compact('obat'));
    }
}
