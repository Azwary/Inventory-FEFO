@extends('admin.layouts.app')

@section('title', 'Detail Obat')

@section('content')
    <h1 class="text-xl font-bold mb-4">Detail Obat: {{ $obat->nama_obat }}</h1>

    <div class="p-6 bg-white rounded-lg shadow">
        <p><strong>Kode:</strong> {{ $obat->kode }}</p>
        <p><strong>Batch:</strong> {{ $obat->batch }}</p>
        <p><strong>Tanggal Masuk:</strong> {{ $obat->tanggal_masuk->format('d/m/Y') }}</p>
        <p><strong>Tanggal Exp:</strong> {{ $obat->tanggal_exp->format('d/m/Y') }}</p>
        <p><strong>Jumlah:</strong> {{ $obat->jumlah }}</p>
        <p><strong>Lokasi:</strong> {{ $obat->lokasi }}</p>
        <p><strong>Deskripsi:</strong> {{ $obat->deskripsi }}</p>

        <a href="{{ route('admin.stok.index') }}" class="mt-4 inline-block text-blue-500">Kembali ke Persediaan</a>
    </div>
@endsection
