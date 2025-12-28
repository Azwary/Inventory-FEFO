<!-- Overlay untuk menutup sidebar saat mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-50" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-50" x-transition:leave-end="opacity-0"></div>

<aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col h-full transform transition-transform duration-300 md:translate-x-0 md:static md:h-screen z-50">

    <!-- Header Sidebar + Tombol Close -->
    <div class="p-4 flex items-center justify-between border-b">
        <span class="font-bold text-lg">Inventori FEFO</span>

        <!-- Tombol Close hanya muncul di mobile -->
        <button @click="sidebarOpen = false" class="md:hidden text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Menu Admin -->
    @if (auth()->user()->role === 'Admin')
        <nav class="mt-6 flex flex-col gap-4 px-4">
            <a href="{{ route('admin.dashboard') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.stok.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.stok.index') ? 'bg-gray-200 font-bold' : '' }}">
                Persediaan / Stok
            </a>
            <a href="{{ route('admin.pengeluaran-obat.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.pengeluaran-obat.index') ? 'bg-gray-200 font-bold' : '' }}">
                Pengeluaran Obat
            </a>
            <a href="{{ route('admin.notifikasi-kedaluwarsa.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.notifikasi-kedaluwarsa.index') ? 'bg-gray-200 font-bold' : '' }}">
                Notifikasi Kedaluwarsa
            </a>
            <a href="{{ route('admin.laporan-audit.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.laporan-audit.index') ? 'bg-gray-200 font-bold' : '' }}">
                Laporan & Audit
            </a>
        </nav>
    @endif

    <!-- Menu Pimpinan -->
    @if (auth()->user()->role === 'Pimpinan')
        <nav class="mt-6 flex flex-col gap-4 px-4">
            <a href="{{ route('pimpinan.dashboard') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-gray-200 font-bold' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('pimpinan.stok.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('pimpinan.stok.index') ? 'bg-gray-200 font-bold' : '' }}">
                Persediaan / Stok
            </a>
            <a href="{{ route('pimpinan.notifikasi-kedaluwarsa.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('pimpinan.notifikasi-kedaluwarsa.index') ? 'bg-gray-200 font-bold' : '' }}">
                Notifikasi Kedaluwarsa
            </a>
            <a href="{{ route('pimpinan.laporan-audit.index') }}"
                class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('pimpinan.laporan-audit.index') ? 'bg-gray-200 font-bold' : '' }}">
                Laporan & Audit
            </a>
        </nav>
    @endif

    <!-- Logout -->
    <nav class="mt-auto border-t pt-4 text-center px-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-red-600 hover:text-red-800 hover:bg-gray-200">
                Logout
            </button>
        </form>
    </nav>
</aside>
