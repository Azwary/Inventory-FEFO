<header class="bg-white px-6 py-4 border-b">
    <div class="flex justify-between items-center">
        <input type="text" placeholder="Cari obat, Batch atau Laporan..." class="px-4 py-2 w-1/2 border rounded">

        <div class="flex items-center space-x-4">
            {{-- notifikasi --}}
            @if (Auth::user()->role === 'Pimpinan')
                {{-- Tombol tampil tapi disable --}}
                <button class="relative cursor-not-allowed opacity-50" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                        <path
                            d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                    </svg>
                </button>
            @else
                @if (!empty($notifications) && count($notifications) > 0)
                    <a href="{{ route('admin.notifikasi-kedaluwarsa.index') }}">
                        <button class="relative">
                            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                                <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                                <path
                                    d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                            </svg>
                        </button>
                    </a>
                @else
                    <button class="relative cursor-not-allowed opacity-50" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                            <path
                                d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                        </svg>
                    </button>
                @endif
            @endif

            {{-- user --}}
            <div class="flex items-center gap-2">
                <i class="bi bi-person-circle text-3xl"></i>
                <div class="flex flex-col">
                    <span class="font-medium">{{ Auth::user()->role }}</span>
                    <span class="text-sm text-gray-500">{{ Auth::user()->nama }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
