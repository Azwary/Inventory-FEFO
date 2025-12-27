@extends('views.layouts.app')

@section('title', 'Transaksi Pengeluaran Obat')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Transaksi Pengeluaran Obat</h2>
    </div>
    <div class="p-6 bg-white rounded-lg shadow">
        Sistem akan secara otomatis merekomendasikan
        <br>
        Batch FEFO (Frist Expired, Frist Out) saat pemilihan obat.

        <div class="flex gap-x-4 w-full mb-4">
            <div class="flex-1">
                <label class="block font-medium mb-1" for="nama_obat">Nama Obat</label>
                <select name="nama_obat" id="nama_obat" class="border rounded px-3 py-2 w-full mt-2">
                    <option value="">Pilih Obat..</option>
                    <option value="Null">Null</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block font-medium mb-1" for="jumlah_pengeluaran">Jumlah Pengeluaran</label>
                <select name="jumlah_pengeluaran" id="jumlah_pengeluaran" class="border rounded px-3 py-2 w-full mt-2">
                    <option value="">Pilih Jumlah..</option>
                    <option value="Null">Null</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1" for="batch">Tujuan Pengeluaran (Cth: Penjualan, Transfer,
                Discord)</label>
            <input type="text" name="batch" id="batch" placeholder="Contoh: Penjualan Resep Dokter Abdi"
                class="border rounded px-3 py-2 w-full">
        </div>

        <span class="text-gray-400">Pilih obat untuk melihat batch FEFO yang direkomendasikan</span>

        <!-- Tombol di kanan bawah -->
        <div class="flex justify-end mt-4">
            <button class="border rounded px-4 py-2 bg-blue-500 text-white hover:bg-blue-600">Proses Pengeluaran</button>
        </div>
    </div>
@endsection
