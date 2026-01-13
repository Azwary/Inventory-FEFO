<?php

namespace Database\Seeders;

use App\Models\Jenis;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Obat;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Apoteker',
        ]);

        User::create([
            'nama' => 'Pimpinan',
            'username' => 'pimpinan',
            'password' => Hash::make('pimpinan123'),
            'role' => 'Pimpinan',
        ]);

        // Seeder Jenis
        $jenisList = ['kapsul', 'tablet', 'sirup', 'salep', 'injeksi', 'drops', 'suppositoria', 'patch'];
        foreach ($jenisList as $index => $namaJenis) {
            Jenis::create([
                'id_jenis' => 'JNS' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_jenis' => $namaJenis,
            ]);
        }

        // Seeder kategori
        $kategoriList = ['Analgesik', 'Antibiotik', 'Antiseptik', 'Antipiretik', 'Antiinflamasi', 'Antihistamin', 'Antasida', 'Diuretik', 'Kortikosteroid', 'Vaksin', 'Vitamin dan Suplemen'];
        foreach ($kategoriList as $index => $namaKategori) {
            Kategori::create([
                'id_kategori' => 'KTG' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_kategori' => $namaKategori,
            ]);
        }

        // Seeder Obat
        $obatList = ['Paracetamol', 'Amoxicillin', 'Ibuprofen', 'Aspirin', 'Cefixime', 'Metformin', 'Simvastatin', 'Omeprazole', 'Lisinopril', 'Azithromycin'];
        foreach ($obatList as $index => $namaObat) {
            Obat::create([
                'id_obat' => 'OBT' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_obat' => $namaObat,
            ]);
        }

        // Seeder Satuan
        $satuanList = ['pcs', 'btl', 'kapsul', 'tablet', 'sirup', 'salep', 'injeksi', 'drops', 'suppositoria'];
        foreach ($satuanList as $index => $namaSatuan) {
            Satuan::create([
                'id_satuan' => 'STN' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_satuan' => $namaSatuan,
            ]);
        }
        // Seeder Lokasi
        $lokasiList = ['Gudang Utama', 'Rak A1', 'Rak A2', 'Rak B1', 'Rak B2', 'Rak C1', 'Rak C2'];
        $keteranganlokasi = 'Lokasi penyimpanan obat dan barang medis';
        foreach ($lokasiList as $index => $namaLokasi) {
            Lokasi::create([
                'id_lokasi' => 'LKS' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'nama_lokasi' => $namaLokasi,
                'keterangan' => $keteranganlokasi,
            ]);
        }
    }
}
