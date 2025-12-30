@extends('views.layouts.app')

@section('title', 'Transaksi Pengeluaran Obat')

@section('content')
    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">Transaksi Pengeluaran Obat</h2>
        <p class="text-sm opacity-90">Informasi Transaksi Pengeluaran Obat</p>
    </div>

    <div class="p-6 bg-white rounded-lg shadow">
        <p class="mb-4 text-gray-600">
            Sistem akan secara otomatis merekomendasikan
            <b>Batch FEFO (First Expired, First Out)</b>
            saat pemilihan obat.
        </p>
        <form action="{{ route('admin.pengeluaran-obat.store') }}" method="POST">
            @csrf
            <div class="flex gap-x-4 w-full mb-4">

                <div class="flex-1">
                    <label class="block font-medium mb-1" for="nama_obat">Nama Obat</label>
                    <select name="nama_obat" id="nama_obat" class="border rounded px-3 py-2 w-full mt-2" required>
                        <option value="" disabled selected>Pilih Obat..</option>
                        @foreach ($stoks as $stok)
                            <option value="{{ $stok->id_stok }}" data-stok="{{ $stok->jumlah_masuk }}"
                                data-batch="{{ $stok->nomor_batch }}" data-exp="{{ $stok->tanggal_kadaluarsa }}"
                                {{ old('nama_obat') == $stok->id_stok ? 'selected' : '' }}>
                                {{ optional($stok->barang)->obat?->nama_obat ?? '-' }}
                                (Exp: {{ $stok->tanggal_kadaluarsa }})
                            </option>
                        @endforeach
                    </select>
                    @error('nama_obat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex-1">
                    <label class="block font-medium mb-1" for="jumlah_pengeluaran">
                        Jumlah Pengeluaran
                    </label>
                    <input type="number" name="jumlah_pengeluaran" id="jumlah_pengeluaran" min="1"
                        class="border rounded px-3 py-2 w-full mt-2" placeholder="Masukkan jumlah" required>
                    <small class="text-gray-500">
                        Stok tersedia: <span id="stok-tersedia">-</span>
                    </small>
                </div>
            </div>


            <div class="mb-4">
                <label class="block font-medium mb-1" for="keterangan">
                    Tujuan Pengeluaran
                </label>
                <input type="text" name="keterangan" id="keterangan" placeholder="Contoh: Penjualan Resep Dokter Abdi"
                    class="border rounded px-3 py-2 w-full" required>
            </div>


            <div class="text-sm text-gray-500 mb-4">
                Batch FEFO terpilih:
                <b id="info-batch">-</b> |
                Expired:
                <b id="info-exp">-</b>
            </div>


            <div class="flex justify-end mt-4">
                <button type="submit" class="border rounded px-4 py-2 bg-blue-500 text-white hover:bg-blue-600">
                    Proses Pengeluaran
                </button>
            </div>
        </form>
    </div>


    <script>
        const obatSelect = document.getElementById('nama_obat');
        const stokText = document.getElementById('stok-tersedia');
        const batchText = document.getElementById('info-batch');
        const expText = document.getElementById('info-exp');
        const jumlahInput = document.getElementById('jumlah_pengeluaran');

        obatSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];

            const stok = option.dataset.stok ?? '-';
            const batch = option.dataset.batch ?? '-';
            const exp = option.dataset.exp ?? '-';

            stokText.innerText = stok;
            batchText.innerText = batch;
            expText.innerText = exp;

            jumlahInput.max = stok;
        });
    </script>
@endsection
