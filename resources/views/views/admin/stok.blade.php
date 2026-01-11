@extends('views.layouts.app')

@section('title', 'Persediaan / Stok Obat')

@section('content')
    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">Persediaan / Stok Obat</h2>
        <p class="text-sm opacity-90">Informasi Persediaan / Stok Obat</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow">


        <form method="GET"
            class="mb-5 p-4 bg-white rounded-xl shadow-sm border
           flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-3 w-full">


            <div class="relative w-full sm:w-1/3">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">

                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama obat atau nomor batch..."
                    class="w-full pl-10 pr-3 py-2 border rounded-lg
                   focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            @php
                $currentExp = request('exp', 'asc');
                $nextExp = $currentExp === 'asc' ? 'desc' : 'asc';
            @endphp


            <button type="submit"
                class="flex items-center justify-center gap-2
               bg-green-600 text-white px-4 py-2 rounded-lg
               hover:bg-green-700 transition w-full sm:w-auto">
                <span>Cari</span>
            </button>


            <a href="{{ request()->fullUrlWithQuery(['exp' => $nextExp]) }}"
                class="flex items-center justify-center gap-2
               px-4 py-2 rounded-lg border
               {{ $currentExp === 'asc'
                   ? 'bg-orange-50 border-orange-300 text-orange-700'
                   : 'bg-blue-50 border-blue-300 text-blue-700' }}
               hover:opacity-80 transition w-full sm:w-auto">

                FEFO
                <span class="text-xs font-semibold">
                    {{ strtoupper($currentExp) }}
                </span>
            </a>


            <button type="button" onclick="openModal('addModal')"
                class="sm:ml-auto flex items-center justify-center gap-2
               bg-blue-600 text-white px-5 py-2 rounded-lg
               hover:bg-blue-700 transition shadow-md w-full sm:w-auto">

                <span>Tambah Obat Masuk</span>
            </button>
        </form>

        <div class="mb-6">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M3 3h18v6H3zM3 13h18v8H3z" />
                </svg>
                Tampilan Rak Obat
            </h3>

            <div id="rakContainer" class="flex gap-4 overflow-x-auto no-scrollbar select-none cursor-grab pb-2">

                @foreach ($rakStoks as $rak)
                    @php
                        $isKosong = $rak->jumlah_item == 0;
                        $isWarning = $rak->warning_item > 0;

                        $border = $isKosong ? 'border-gray-300' : ($isWarning ? 'border-red-400' : 'border-green-400');

                        $bg = $isKosong ? 'bg-gray-50' : ($isWarning ? 'bg-red-50' : 'bg-green-50');

                        $badge = $isKosong
                            ? 'bg-gray-200 text-gray-600'
                            : ($isWarning
                                ? 'bg-red-100 text-red-600'
                                : 'bg-green-100 text-green-600');
                    @endphp

                    <div
                        class="flex-shrink-0 w-1/5 min-w-[170px] rounded-xl p-4 shadow-sm border {{ $border }} {{ $bg }}
                       hover:shadow-md transition-all duration-300 flex flex-col justify-between">


                        <div class="text-sm font-semibold truncate">
                            {{ $rak->lokasi?->nama_lokasi ?? 'Tanpa Lokasi' }}
                        </div>


                        <div class="text-3xl font-bold mt-2">
                            {{ $rak->jumlah_item }}
                        </div>


                        <div class="mt-2">
                            @if ($isWarning)
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $badge }}">
                                    {{ $rak->warning_item }} Item Warning
                                </span>
                            @elseif ($isKosong)
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $badge }}">
                                    Rak Kosong
                                </span>
                            @else
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $badge }}">
                                    Aman
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full border border-gray-300 table-auto text-center">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-3 py-2">KODE</th>
                        <th class="border px-3 py-2">NAMA OBAT</th>
                        <th class="border px-3 py-2">BATCH</th>
                        <th class="border px-3 py-2">TANGGAL MASUK</th>
                        <th class="border px-3 py-2">TANGGAL EXP</th>
                        <th class="border px-3 py-2">JUMLAH</th>
                        <th class="border px-3 py-2">LOKASI</th>
                        <th class="border px-3 py-2">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $filteredStoks = $stoks->where('jumlah_stok', '>', 0);
                    @endphp

                    @if ($filteredStoks->isEmpty())
                        <tr>
                            <td colspan="8" class="py-10 text-center text-gray-500">
                                @if (request('search'))
                                    <div class="flex flex-col items-center gap-2">
                                        <p class="font-semibold">
                                            Data dengan kata kunci
                                            <span class="text-blue-600">"{{ request('search') }}"</span>
                                            tidak ditemukan
                                        </p>
                                    </div>
                                @else
                                    <p class="italic">Tidak ada stok obat tersedia</p>
                                @endif
                            </td>
                        </tr>
                    @else
                        @foreach ($filteredStoks as $stok)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-2">{{ $stok->id_stok }}</td>
                                <td class="border px-3 py-2">
                                    {{ $stok->barang->obat?->nama_obat ?? '-' }}
                                </td>
                                <td class="border px-3 py-2">{{ $stok->nomor_batch }}</td>
                                <td class="border px-3 py-2">{{ $stok->tanggal_masuk }}</td>
                                <td class="border px-3 py-2">{{ $stok->tanggal_kadaluarsa ?? '-' }}</td>
                                <td class="border px-3 py-2 font-semibold text-green-700">
                                    {{ $stok->jumlah_stok }}
                                </td>
                                <td class="border px-3 py-2">{{ $stok->lokasi->nama_lokasi ?? '-' }}</td>
                                <td class="border px-3 py-2">
                                    <button onclick="openModal('detailModal{{ $stok->id_stok }}')"
                                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- MODAL DETAIL -->
                            <div id="detailModal{{ $stok->id_stok }}"
                                class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4 z-50">

                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">


                                    <!-- HEADER -->
                                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white">
                                        <h3 class="text-lg font-bold">Detail Stok Obat</h3>
                                        <p class="text-sm opacity-90">
                                            {{ $stok->barang->obat?->nama_obat ?? '-' }}
                                        </p>
                                    </div>

                                    <!-- BODY -->
                                    <div class="p-5 space-y-4">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Kode Stok</p>
                                                <p class="font-semibold">{{ $stok->id_stok }}</p>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Batch</p>
                                                <p class="font-semibold">{{ $stok->nomor_batch }}</p>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Tanggal Masuk</p>
                                                <p class="font-semibold">{{ $stok->tanggal_masuk }}</p>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Tanggal Exp</p>
                                                <p class="font-semibold text-red-600">
                                                    {{ $stok->tanggal_kadaluarsa ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Lokasi</p>
                                                <p class="font-semibold">
                                                    {{ $stok->lokasi->nama_lokasi ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-gray-500">Satuan</p>
                                                <p class="font-semibold">
                                                    {{ $stok->barang->satuan->nama_satuan ?? '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        @php
                                            $expired = $stok->tanggal_kadaluarsa
                                                ? \Carbon\Carbon::parse($stok->tanggal_kadaluarsa)->isPast()
                                                : false;
                                        @endphp

                                        <div class="flex justify-between items-center bg-blue-50 border p-4 rounded">
                                            <div>
                                                <p class="text-sm text-blue-700">Jumlah Stok</p>
                                                <p class="text-2xl font-bold text-blue-800">
                                                    {{ $stok->jumlah_stok }}
                                                </p>
                                            </div>

                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $expired ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $expired ? 'EXPIRED' : 'AKTIF' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- FOOTER -->
                                    <div class="px-5 py-4 bg-gray-50 text-right">
                                        <button onclick="closeModal('detailModal{{ $stok->id_stok }}')"
                                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </tbody>


            </table>
        </div>
    </div>

    <div id="addModal"
        class="fixed inset-0 bg-black/60 hidden items-start justify-center overflow-auto p-4 z-50 backdrop-blur-sm">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mt-10 overflow-hidden animate-fade-in">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Stok Obat
                </h3>
                <p class="text-sm opacity-90 mt-1">
                    Input data obat masuk ke gudang
                </p>
            </div>

            <!-- BODY -->
            <form action="{{ route('admin.stok.store') }}" method="POST" class="p-6 space-y-5"
                onsubmit="handleSubmit(this)">
                @csrf

                <!-- NAMA OBAT -->
                <div>
                    <label for="nama_obat" class="block text-sm font-semibold mb-1">
                        Nama Obat
                    </label>
                    <select name="nama_obat" id="nama_obat"
                        class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="" disabled selected>Pilih Obat..</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id_obat }}"
                                {{ old('nama_obat') == $obat->id_obat ? 'selected' : '' }}>
                                {{ $obat->nama_obat }}
                            </option>
                        @endforeach
                    </select>
                    @error('nama_obat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- GRID FORM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold mb-1">Jumlah Masuk</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" required>
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Satuan</label>
                        <select name="satuan" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="" disabled selected>Pilih Satuan..</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id_satuan }}"
                                    {{ old('satuan') == $satuan->id_satuan ? 'selected' : '' }}>
                                    {{ $satuan->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Jenis</label>
                        <select name="jenis" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="" disabled selected>Pilih Jenis..</option>
                            @foreach ($jeniss as $jenis)
                                <option value="{{ $jenis->id_jenis }}"
                                    {{ old('jenis') == $jenis->id_jenis ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Kategori</label>
                        <select name="kategori" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="" disabled selected>Pilih Kategori..</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}"
                                    {{ old('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Tanggal Kedaluwarsa</label>
                        <input type="date" name="tanggal_exp" value="{{ old('tanggal_exp') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-1">Lokasi</label>
                        <select name="lokasi" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="" disabled selected>Pilih Lokasi..</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}"
                                    {{ old('lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeModal('addModal')"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function handleSubmit(form) {
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        }
    </script>
@endsection
