@extends('views.layouts.app')

@section('title', 'Notifikasi Kekadaluarsaan')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Notifikasi Kekadaluarsaan</h2>
    </div>
    <div class="p-6 bg-white rounded-lg shadow mb-4">


        <div class="">
            <h2 class="font-bold mb-4">Daftar Batch Kritis (FEFO Alert)</h2>

            @foreach ($notifications as $notif)
                <div class="flex items-center justify-between mb-3">
                    <!-- Notifikasi penuh, sisakan ruang untuk tombol -->
                    <div class="flex-1 flex items-center p-2 border rounded space-x-3">
                        <span>⚠️</span>
                        <div>
                            <p class="font-semibold">{{ $notif->batch }} ({{ $notif->nama_obat }})</p>
                            <p class="text-sm">
                                Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol dengan icon saja -->
                    <div class="flex space-x-2 ml-2">
                        <button class="p-2 text-white rounded text-sm" title="Hapus">🗑️</button>
                        <button class="p-2 text-white rounded text-sm" title="Edit">✏️</button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
