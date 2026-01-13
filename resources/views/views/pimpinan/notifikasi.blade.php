@extends('views.layouts.app')

@section('title', 'Notifikasi Kekadaluarsaan')

@section('content')
    {{-- <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">Notifikasi Kekadaluarsaan</h2>
        <p class="text-sm opacity-90">Informasi Kekadaluarsaan Obat</p>
    </div> --}}

    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="font-bold mb-4">Daftar Batch Kritis (FEFO Alert)</h2>

        @forelse ($notifications->where('jumlah_stok', '>=', 1) as $notif)
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 p-4 border rounded-lg gap-4 sm:gap-0 hover:shadow-sm transition-shadow">

                <div class="flex items-start sm:items-center space-x-3 w-full sm:w-2/3">
                    <span class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-triangle-alert text-yellow-500">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                    </span>
                    <div class="w-full">
                        <p class="font-semibold text-gray-800 truncate">
                            {{ $notif->nomor_batch ?? '-' }}
                            ({{ $notif->barang->obat?->nama_obat ?? '-' }})
                        </p>
                        <p class="text-sm text-gray-600">
                            Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.
                        </p>
                        <p class="text-sm text-gray-600">
                            Sisa Stok {{ $notif->jumlah_stok }}
                        </p>
                    </div>
                </div>

                <div class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                    Exp: <span class="text-red-500">{{ $notif->tanggal_kadaluarsa ?? '-' }}</span>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500 italic">
                Tidak ada batch kritis dengan stok tersedia
            </div>
        @endforelse

    </div>
@endsection
