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
                            class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-50 backdrop-blur-sm">

                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">


                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white">
                                    <h3 class="text-lg font-semibold">
                                        Detail Obat
                                    </h3>
                                    <p class="text-sm opacity-90">
                                        {{ $stok->barang->obat?->nama_obat ?? '-' }}
                                    </p>
                                </div>

                                <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Kode</p>
                                        <p class="font-semibold">{{ $stok->id_stok }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Batch</p>
                                        <p class="font-semibold">{{ $stok->nomor_batch }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Tanggal Masuk</p>
                                        <p class="font-semibold">{{ $stok->tanggal_masuk }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Tanggal Exp</p>
                                        <p class="font-semibold">{{ $stok->tanggal_kadaluarsa }}</p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Jumlah</p>
                                        <p class="font-semibold">
                                            {{ $stok->jumlah_masuk }} {{ $stok->barang->satuan->nama_satuan ?? '' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-gray-500">Lokasi</p>
                                        <p class="font-semibold">{{ $stok->lokasi->nama_lokasi ?? '-' }}</p>
                                    </div>
                                </div>


                                <div class="flex justify-end gap-2 p-4 border-t">
                                    <button onclick="closeModal('detailModal{{ $stok->id_barang }}')"
                                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="addModal"
        class="fixed inset-0 bg-black/60 hidden items-start justify-center overflow-auto p-4 z-50 backdrop-blur-sm">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mt-10">

            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-5 text-white rounded-t-2xl">
                <h3 class="text-xl font-semibold">Tambah Stok Obat</h3>
                <p class="text-sm opacity-90">Masukkan data stok obat yang masuk</p>
            </div>

            <form action="{{ route('admin.stok.store') }}" method="POST" class="p-6">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-medium mb-1">Nama Obat</label>
                    <select name="nama_obat" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                        required>
                        <option value="">Pilih Obat</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([['jumlah', 'Jumlah Masuk', 'number'], ['tanggal_masuk', 'Tanggal Masuk', 'date'], ['tanggal_exp', 'Tanggal Kedaluwarsa', 'date']] as [$name, $label, $type])
                        <div>
                            <label class="block text-sm font-medium mb-1">{{ $label }}</label>
                            <input type="{{ $type }}" name="{{ $name }}"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500" required>
                        </div>
                    @endforeach

                    <div>
                        <label class="block text-sm font-medium mb-1">Satuan</label>
                        <select name="satuan"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500" required>
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id_satuan }}">{{ $satuan->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Lokasi</label>
                        <select name="lokasi"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closeModal('addModal')"
                        class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition shadow">
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
    </script>
@endsection
