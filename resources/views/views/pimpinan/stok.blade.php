@extends('views.layouts.app')

@section('title', 'Persediaan / Stok Obat')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Persediaan / Stok Obat</h2>
    </div>
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex items-center mb-4 gap-2">
            <input type="text" placeholder="Search..." class="border rounded px-3 py-1 w-1/3">
            <button class="bg-gray-200 px-3 py-1 rounded">Filter FEFO (Exp ASC)</button>
            <button onclick="openModal('addModal')" class="ml-auto bg-blue-500 text-white px-3 py-1 rounded">+ Tambah Obat
                Masuk</button>
        </div>

        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
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
                @foreach ($obats as $obat)
                    <tr>
                        <td class="border px-3 py-2">{{ $obat->kode }}</td>
                        <td class="border px-3 py-2">{{ $obat->nama_obat }}</td>
                        <td class="border px-3 py-2">{{ $obat->batch }}</td>
                        <td class="border px-3 py-2">{{ $obat->tanggal_masuk->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2">{{ $obat->tanggal_exp->format('d/m/Y') }}</td>
                        <td class="border px-3 py-2">{{ $obat->jumlah }}</td>
                        <td class="border px-3 py-2">{{ $obat->lokasi }}</td>
                        <td class="border px-3 py-2">
                            <button onclick="openModal('detailModal{{ $obat->id }}')"
                                class="text-blue-500">Detail</button>
                        </td>
                    </tr>

                    <!-- Modal Detail Obat -->
                    <div id="detailModal{{ $obat->id }}"
                        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

                        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
                            <h3 class="text-lg font-semibold mb-4">Detail Obat: {{ $obat->nama_obat }}</h3>
                            <ul class="mb-4">
                                <li><strong>Kode:</strong> {{ $obat->kode }}</li>
                                <li><strong>Batch:</strong> {{ $obat->batch }}</li>
                                <li><strong>Tanggal Masuk:</strong> {{ $obat->tanggal_masuk->format('d/m/Y') }}</li>
                                <li><strong>Tanggal Exp:</strong> {{ $obat->tanggal_exp->format('d/m/Y') }}</li>
                                <li><strong>Jumlah:</strong> {{ $obat->jumlah }}</li>
                                <li><strong>Lokasi:</strong> {{ $obat->lokasi }}</li>
                                <li><strong>Satuan:</strong> {{ $obat->satuan ?? '-' }}</li>
                            </ul>
                            <div class="flex justify-end">
                                <button onclick="closeModal('detailModal{{ $obat->id }}')"
                                    class="px-4 py-2 bg-gray-200 rounded">Tutup</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Obat Masuk -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6">
            <h3 class="text-lg font-semibold mb-4">Tambah Obat Masuk</h3>
            {{-- <form action="{{ route('admin.obat.store') }}" method="POST"> --}}
            @csrf
            <div class="mb-4">
                <label class="block font-medium mb-1" for="nama_obat">Nama Obat</label>
                <select name="nama_obat" id="nama_obat" class="border rounded px-3 py-2 w-full">
                    <option value="">Pilih Obat..</option>
                    @foreach ($obats as $obat)
                        <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="batch">Nomor Batch</label>
                <input type="text" name="batch" id="batch" placeholder="Contoh: BN123456"
                    class="border rounded px-3 py-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="jumlah">Jumlah Masuk</label>
                <input type="number" name="jumlah" id="jumlah" class="border rounded px-3 py-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="satuan">Satuan</label>
                <select name="satuan" id="satuan" class="border rounded px-3 py-2 w-full">
                    <option value="box">Box</option>
                    <option value="pcs">Pcs</option>
                    <option value="botol">Botol</option>
                    <option value="strip">Strip</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="tanggal_masuk">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="border rounded px-3 py-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="tanggal_exp">Tanggal Kedaluwarsa (FEFO)</label>
                <input type="date" name="tanggal_exp" id="tanggal_exp" class="border rounded px-3 py-2 w-full">
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1" for="lokasi">Lokasi</label>
                <select name="lokasi" id="lokasi" class="border rounded px-3 py-2 w-full">
                    <option value="Rak A, Tingkat 1">Rak A, Tingkat 1</option>
                    <option value="Rak B, Tingkat 1">Rak B, Tingkat 1</option>
                    <option value="Rak C, Tingkat 2">Rak C, Tingkat 2</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
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
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection
