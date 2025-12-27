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
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                            </svg></span>
                        <div>
                            <p class="font-semibold">{{ $notif->batch }} ({{ $notif->nama_obat }})</p>
                            <p class="text-sm">
                                Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol dengan icon saja -->
                    <div class="flex space-x-2 ml-2">
                        <button class="p-2 text-white rounded text-sm" title="Hapus"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash">
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                <path d="M3 6h18" />
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg></button>
                        <button class="p-2 text-white rounded text-sm" title="Edit"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen">
                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path
                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                            </svg></button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
