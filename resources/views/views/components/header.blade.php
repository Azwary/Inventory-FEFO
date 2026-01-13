<header class="bg-white px-6 py-4 border-b flex items-center justify-between md:justify-between">

    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-700 focus:outline-none mr-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- <input type="text" placeholder="Cari obat, Batch atau Laporan..." class="px-4 py-2 w-1/2 border rounded"> --}}

    <div class="text-lg font-semibold text-gray-800">
        @yield('title')
    </div>

    <div class="flex items-center ml-auto space-x-4">
        @php
            $routeNotif =
                Auth::user()->role === 'Pimpinan'
                    ? route('pimpinan.notifikasi-kedaluwarsa.index')
                    : route('admin.notifikasi-kedaluwarsa.index');
        @endphp

        {{-- NOTIFIKASI --}}
        @if (!empty($notifications) && count($notifications) > 0)
            <a href="{{ $routeNotif }}" class="relative">
                <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                    <path
                        d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                </svg>
            </a>
        @else
            <button class="relative cursor-not-allowed opacity-50" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                    <path
                        d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                </svg>
            </button>
        @endif

        {{-- USER --}}
        <div class="flex items-center gap-2">
            <i class="bi bi-person-circle text-3xl"></i>
            <div class="flex flex-col text-left">
                <span class="font-medium">{{ Auth::user()->role }}</span>
                <span class="text-sm text-gray-500">{{ Auth::user()->nama }}</span>
            </div>
        </div>
    </div>

</header>
