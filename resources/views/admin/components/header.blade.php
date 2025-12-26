<div class="flex justify-between items-center mb-6">
    <input type="text" placeholder="Cari obat, Batch atau Laporan..." class="px-4 py-2 w-1/2 border rounded">
    <div class="flex items-center space-x-4">
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


        <div class="flex items-center space-x-2">

            {{-- <span>{{ auth()->user()->nama }}</span> --}}
            {{-- <span>{{ Auth::check() ? Auth::user()->nama : 'Guest' }}</span> --}}
            <div class="flex items-center gap-2">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::user()->nama }}</span>
            </div>


            {{-- <img src="{{ asset('admin-avatar.png') }}" alt="Admin" class="h-8 w-8 rounded-full"> --}}
        </div>
    </div>
</div>
