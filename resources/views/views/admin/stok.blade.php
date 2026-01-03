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
                    @foreach ($stoks as $stok)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $stok->id_stok ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $stok->barang->obat?->nama_obat ?? '-' }}
                            </td>
                            {{-- {{ dd($stok->barang, $stok->barang?->obat) }} --}}


                            <td class="border px-3 py-2">{{ $stok->nomor_batch }}</td>
                            <td class="border px-3 py-2">{{ $stok->tanggal_masuk ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $stok->tanggal_kadaluarsa ?? '-' }}
                            </td>
                            <td class="border px-3 py-2">{{ $stok->jumlah_masuk ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $stok->lokasi->nama_lokasi ?? '-' }}</td>
                            <td class="border px-3 py-2">
                                <button type="button" onclick="openModal('detailModal{{ $stok->id_barang }}')"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition sm:w-auto">Detail</button>
                            </td>
                        </tr>

                        <div id="detailModal{{ $stok->id_barang }}"
                            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                <h3 class="text-lg font-semibold mb-4">Detail Obat:
                                    {{ $stok->barang->obat?->nama_obat ?? '-' }}</h3>
                                <ul class="mb-4 space-y-1">
                                    <li><strong>Kode:</strong> {{ $stok->id_stok ?? '-' }}</li>
                                    <li><strong>Batch:</strong> {{ $stok->nomor_batch ?? '-' }}</li>
                                    <li><strong>Tanggal Masuk:</strong>
                                        {{ $stok->tanggal_masuk ?? '-' }}</li>
                                    <li><strong>Tanggal Exp:</strong>
                                        {{ $stok->tanggal_kadaluarsa ?? '-' }}</li>
                                    <li><strong>Jumlah:</strong> {{ $stok->jumlah_masuk ?? '-' }}</li>
                                    <li><strong>Lokasi:</strong> {{ $stok->lokasi->nama_lokasi ?? '-' }}</li>
                                    <li><strong>Satuan:</strong> {{ $stok->barang->satuan->nama_satuan ?? '-' }}</li>
                                </ul>
                                <div class="flex justify-end">
                                    <button type="button" onclick="closeModal('detailModal{{ $stok->id_barang }}')"
                                        class="px-4 py-2 bg-gray-200 rounded">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="addModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-start justify-center overflow-auto p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 mt-10">
            <h3 class="text-lg font-semibold mb-6">Tambah Stok Obat</h3>

            <form action="{{ route('admin.stok.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_obat" class="block font-medium mb-1">Nama Obat</label>
                    <select name="nama_obat" id="nama_obat" class="border rounded px-3 py-2 w-full" required>
                        <option value="" disabled selected>Pilih Obat..</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id_obat }}"
                                {{ old('nama_obat') == $obat->id_obat ? 'selected' : '' }}>
                                {{ $obat->nama_obat }}
                            </option>
                        @endforeach
                    </select>
                    @error('nama_obat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- <div class="mb-4">
                        <label for="batch">Nomor Batch</label>
                        <input type="text" name="batch" id="batch" class="border rounded px-3 py-2 w-full"
                            placeholder="Contoh: NB001" value="{{ old('batch') }}" required>
                        @error('batch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <div class="mb-4">
                        <label for="jumlah">Jumlah Masuk</label>
                        <input type="number" name="jumlah" id="jumlah" class="border rounded px-3 py-2 w-full"
                            value="{{ old('jumlah') }}" required>
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="satuan">Satuan</label>
                        <select name="satuan" id="satuan" class="border rounded px-3 py-2 w-full" required>
                            <option value="" disabled selected>Pilih Satuan..</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id_satuan }}"
                                    {{ old('satuan') == $satuan->id_satuan ? 'selected' : '' }}>
                                    {{ $satuan->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jenis">Jenis</label>
                        <select name="jenis" id="jenis" class="border rounded px-3 py-2 w-full" required>
                            <option value="" disabled selected>Pilih Jenis..</option>
                            @foreach ($jeniss as $jenis)
                                <option value="{{ $jenis->id_jenis }}"
                                    {{ old('jenis') == $jenis->id_jenis ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="border rounded px-3 py-2 w-full" required>
                            <option value="" disabled selected>Pilih Kategori..</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}"
                                    {{ old('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="border rounded px-3 py-2 w-full" value="{{ old('tanggal_masuk') }}" required>
                        @error('tanggal_masuk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_exp">Tanggal Kedaluwarsa</label>
                        <input type="date" name="tanggal_exp" id="tanggal_exp"
                            class="border rounded px-3 py-2 w-full" value="{{ old('tanggal_exp') }}" required>
                        @error('tanggal_exp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="lokasi">Lokasi</label>
                        <select name="lokasi" id="lokasi" class="border rounded px-3 py-2 w-full" required>
                            <option value="" disabled selected>Pilih Lokasi..</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}"
                                    {{ old('lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal('addModal')"
                        class="px-4 py-2 border rounded bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
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
    </script>
@endsection
