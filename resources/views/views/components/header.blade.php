<header class="bg-white px-6 py-4 border-b">
    <div class="flex justify-between items-center">
        <input type="text" placeholder="Cari obat, Batch atau Laporan..." class="px-4 py-2 w-1/2 border rounded">

        <div class="flex items-center space-x-4">
            {{-- notifikasi --}}
            @if (!empty($notifications) && count($notifications) > 0)
                <a href="{{ route('admin.notifikasi-kedaluwarsa.index') }}">
                    <button class="relative">
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                        🔔
                    </button>
                </a>
            @else
                <button class="relative cursor-not-allowed opacity-50" disabled>
                    🔔
                </button>
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
