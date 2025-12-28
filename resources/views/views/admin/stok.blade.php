@extends('views.layouts.app')

@section('title', 'Persediaan / Stok Obat')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Persediaan / Stok Obat</h2>
    </div>

    <div class="p-6 bg-white rounded-lg shadow">
        <!-- Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2 mb-4">
            <input type="text" placeholder="Search..." class="border rounded px-3 py-1 w-full sm:w-1/3 mb-2 sm:mb-0">
            <button class="bg-gray-200 px-3 py-1 rounded w-full sm:w-auto mb-2 sm:mb-0">Filter FEFO (Exp ASC)</button>
            <button type="button" onclick="openModal('addModal')"
                class="ml-auto bg-blue-500 text-white px-3 py-1 rounded w-full sm:w-auto">+ Tambah Obat Masuk</button>
        </div>

        <!-- Tabel Stok Obat -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2 text-left">KODE</th>
                        <th class="border px-3 py-2 text-left">NAMA OBAT</th>
                        <th class="border px-3 py-2 text-left">BATCH</th>
                        <th class="border px-3 py-2 text-left">TANGGAL MASUK</th>
                        <th class="border px-3 py-2 text-left">TANGGAL EXP</th>
                        <th class="border px-3 py-2 text-left">JUMLAH</th>
                        <th class="border px-3 py-2 text-left">LOKASI</th>
                        <th class="border px-3 py-2 text-left">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoks as $obat)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $obat->kode }}</td>
                            <td class="border px-3 py-2">{{ $obat->nama_obat }}</td>
                            <td class="border px-3 py-2">{{ $obat->batch }}</td>
                            <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($obat->tanggal_masuk)->format('d/m/Y') }}
                            </td>
                            <td class="border px-3 py-2">{{ \Carbon\Carbon::parse($obat->tanggal_exp)->format('d/m/Y') }}
                            </td>
                            <td class="border px-3 py-2">{{ $obat->jumlah }}</td>
                            <td class="border px-3 py-2">{{ $obat->lokasi }}</td>
                            <td class="border px-3 py-2">
                                <button type="button" onclick="openModal('detailModal{{ $obat->id_barang }}')"
                                    class="text-blue-500 underline">Detail</button>
                            </td>
                        </tr>

                        <!-- Modal Detail Obat -->
                        <div id="detailModal{{ $obat->id_barang }}"
                            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                <h3 class="text-lg font-semibold mb-4">Detail Obat: {{ $obat->nama_obat }}</h3>
                                <ul class="mb-4 space-y-1">
                                    <li><strong>Kode:</strong> {{ $obat->kode }}</li>
                                    <li><strong>Batch:</strong> {{ $obat->batch }}</li>
                                    <li><strong>Tanggal Masuk:</strong>
                                        {{ \Carbon\Carbon::parse($obat->tanggal_masuk)->format('d/m/Y') }}</li>
                                    <li><strong>Tanggal Exp:</strong>
                                        {{ \Carbon\Carbon::parse($obat->tanggal_exp)->format('d/m/Y') }}</li>
                                    <li><strong>Jumlah:</strong> {{ $obat->jumlah }}</li>
                                    <li><strong>Lokasi:</strong> {{ $obat->lokasi }}</li>
                                    <li><strong>Satuan:</strong> {{ $obat->satuan->nama_satuan ?? '-' }}</li>
                                </ul>
                                <div class="flex justify-end">
                                    <button type="button" onclick="closeModal('detailModal{{ $obat->id_barang }}')"
                                        class="px-4 py-2 bg-gray-200 rounded">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Stok Obat -->
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
                    <div class="mb-4">
                        <label for="batch">Nomor Batch</label>
                        <input type="text" name="batch" id="batch" class="border rounded px-3 py-2 w-full"
                            placeholder="Contoh: NB001" value="{{ old('batch') }}" required>
                        @error('batch')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
