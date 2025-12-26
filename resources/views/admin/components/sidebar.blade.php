<aside class="w-64 bg-white shadow-lg">
    <div class="p-4 flex items-center space-x-2 border-b">
        {{-- <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10"> --}}
        <span class="font-bold text-lg">Inventori FEFO</span>
    </div>
    <nav class="mt-4">
        <a href="{{ route('admin.dashboard') }}"
            class="block px-4 py-2 hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 font-bold' : '' }}">Dashboard</a>
        <a href="{{ route('admin.stok.index') }}" class="block px-4 py-2 hover:bg-gray-200">Persediaan / Stok</a>
        {{-- <a href="{{ route('admin.tambah-obat-masuk') }}" class="block px-4 py-2 hover:bg-gray-200">Tambah Obat Masuk</a> --}}
        <a href="{{ route('admin.pengeluaran-obat.index') }}" class="block px-4 py-2 hover:bg-gray-200">Pengeluaran
            Obat</a>
        <a href="{{ route('admin.notifikasi-kedaluwarsa.index') }}" class="block px-4 py-2 hover:bg-gray-200">Notifikasi
            Kedaluwarsa</a>
        {{-- <a href="{{ route('admin.laporan-audit') }}" class="block px-4 py-2 hover:bg-gray-200">Laporan & Audit</a>
        <a href="{{ route('admin.pengguna') }}" class="block px-4 py-2 hover:bg-gray-200">Pengguna & Pengaturan</a>
        <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-200 text-red-500">Logout</a> --}}
    </nav>
</aside>
