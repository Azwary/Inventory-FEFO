@extends('views.layouts.app')

@section('title', 'Notifikasi Kekadaluarsaan')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Notifikasi Kekadaluarsaan</h2>
    </div>

    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="font-bold mb-4">Daftar Batch Kritis (FEFO Alert)</h2>

        @foreach ($notifications as $notif)
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 p-4 border rounded-lg gap-4 sm:gap-0 hover:shadow-sm transition-shadow">

                <!-- Bagian kiri: ikon + info -->
                <div class="flex items-start sm:items-center space-x-3 w-full sm:w-2/3">
                    <span class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="lucide lucide-triangle-alert-icon lucide-triangle-alert text-yellow-500">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                    </span>
                    <div class="w-full">
                        <p class="font-semibold text-gray-800 truncate">{{ $notif->batch }} ({{ $notif->nama_obat }})</p>
                        <p class="text-sm text-gray-600">Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.</p>
                        <p class="text-sm text-gray-600">Sisa Stok {{ $notif->stok }}</p>
                    </div>
                </div>

                <!-- Bagian kanan: Exp + tombol -->
                <div
                    class="flex flex-wrap sm:flex-nowrap items-center justify-between sm:justify-end gap-2 mt-3 sm:mt-0 w-full sm:w-auto">
                    <div class="text-sm font-semibold text-gray-700 sm:mr-4 whitespace-nowrap">
                        Exp: <span class="text-red-500">{{ $notif->exp_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
                            title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-trash-icon">
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                <path d="M3 6h18" />
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                        </button>
                        <button class="p-2 bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-square-pen-icon">
                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path
                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
