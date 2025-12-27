<aside class="w-64 bg-white shadow-lg flex flex-col h-screen">
    <div class="p-4 flex items-center justify-center space-x-2 border-b">
        <span class="font-bold text-lg">Inventori FEFO</span>
    </div>

    {{-- ================= ADMIN ================= --}}
    @if (auth()->user()->role === 'Admin')
        <nav class="mt-8 text-center flex flex-col gap-4">
            {{-- <p class="text-xs text-gray-500 mb-2">MENU ADMIN</p> --}}

            <a href="{{ route('admin.dashboard') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.stok.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Persediaan / Stok
            </a>

            <a href="{{ route('admin.pengeluaran-obat.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Pengeluaran Obat
            </a>

            <a href="{{ route('admin.notifikasi-kedaluwarsa.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Notifikasi Kedaluwarsa
            </a>

            <a href="{{ route('admin.laporan-audit.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Laporan & Audit
            </a>
        </nav>
    @endif

    {{-- ================= PIMPINAN ================= --}}
    @if (auth()->user()->role === 'Pimpinan')
        <nav class="mt-6 text-center border-t pt-4 flex flex-col gap-4">
            {{-- <p class="text-xs text-gray-500 mb-2">MENU PIMPINAN</p> --}}

            <a href="{{ route('pimpinan.dashboard') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('pimpinan.stok.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Persediaan / Stok
            </a>

            {{-- <a href="{{ route('pimpinan.laporan.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Laporan Stok
            </a> --}}

            {{-- <a href="{{ route('pimpinan.audit.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Audit FEFO
            </a>

            <a href="{{ route('pimpinan.grafik.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                Grafik & Statistik
            </a> --}}
        </nav>
    @endif

    {{-- ================= LOGOUT ================= --}}
    <nav class="text-center border-t pt-4 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-red-600 hover:text-red-800 hover:bg-gray-200">
                Logout
            </button>
        </form>
    </nav>

</aside>
