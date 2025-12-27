<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StokController extends Controller
{
    // Menampilkan list stok obat (dummy)
    public function index()
    {
        $obats = collect([
            (object)[
                'id' => 1,
                'kode' => 'OB001',
                'nama_obat' => 'Paracetamol',
                'batch' => 'B001',
                'tanggal_masuk' => Carbon::parse('2025-12-20'),
                'tanggal_exp' => Carbon::parse('2025-12-31'),
                'jumlah' => 100,
                'lokasi' => 'Gudang A',
            ],
            (object)[
                'id' => 2,
                'kode' => 'OB002',
                'nama_obat' => 'Amoxicillin',
                'batch' => 'B002',
                'tanggal_masuk' => Carbon::parse('2025-12-25'),
                'tanggal_exp' => Carbon::parse('2026-01-10'),
                'jumlah' => 50,
                'lokasi' => 'Gudang B',
            ],
            (object)[
                'id' => 3,
                'kode' => 'OB003',
                'nama_obat' => 'Vitamin C',
                'batch' => 'B003',
                'tanggal_masuk' => Carbon::parse('2025-12-15'),
                'tanggal_exp' => Carbon::parse('2026-03-01'),
                'jumlah' => 200,
                'lokasi' => 'Gudang A',
            ],
        ]);

        return view('views.admin.stok', compact('obats'));
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

        return view('admin.obat_detail', compact('obat'));
    }
}
