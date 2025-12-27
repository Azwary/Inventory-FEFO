@extends('views.layouts.app')

@section('title', 'Laporan & Analisis Data')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Laporan & Analisis Data</h2>
    </div>
    <div class="p-6 bg-white rounded-lg shadow">
        Filter Laporan
        <div class="flex gap-x-4 w-full mb-4">
            <div class="flex-1">
                <label class="block font-medium mb-1" for="dari_tanggl">Dari Tanggal</label>
                <input class="border rounded px-3 py-2 w-full mt-2" type="date" name="dari_tanggal" id="dari_tanggal">
            </div>
            <div class="flex-1">
                <label class="block font-medium mb-1" for="sampai_tanggl">Sampai Tanggal</label>
                <input class="border rounded px-3 py-2 w-full mt-2" type="date" name="sampai_tanggal"
                    id="sampai_tanggal">
            </div>
            <div class="flex-1">
                <button class="mt-8 border rounded px-3 py-2 w-full bg-blue-500 text-white hover:bg-blue-600">Generate
                    Laporan</button>
            </div>
        </div>

        <div class="mb-4 p-6 bg-white rounded-lg shadow">
            <p class="font-bold">Preview Laporan Stok Saat Ini (Per Batch)</p>
            <p class="mb-4"> Laporan Menunjukan 50 batch aktif dengan total nilai stok Rp.150.000.000.</p>
            <button class="border rounded px-4 py-2 bg-blue-500 text-white hover:bg-blue-600">Export CSV/PDF</button>
        </div>

        <div class="flex justify-end mt-4">

        </div>
    </div>
@endsection
