<div class="flex justify-center ">


    {{-- <a href="{{ route('admin.tambah-batch') }}"
        class="mb-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 block text-center">
        Tambah Batch Baru
    </a> --}}


    <div class="w-4/5 flex flex-col gap-4">
        <a href="{{ route('admin.stok.index') }}"
            class="mb-2 text-black hover:bg-gray-200 px-4 py-2 border rounded block text-center">
            Persediaan / Stok Obat
        </a>
        <a href="{{ route('admin.pengeluaran-obat.index') }}"
            class="mb-2 text-black hover:bg-gray-200 px-4 py-2 border rounded block text-center">
            Transaksi Obat Keluar
        </a>
        <a href="{{ route('admin.laporan-audit.index') }}"
            class="mb-2 text-black hover:bg-gray-200 px-4 py-2 border rounded block text-center">
            Lihat Laporan
        </a>
    </div>



    {{-- <a href="{{ route('admin.lihat-laporan') }}"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 block text-center">
        Lihat Laporan
    </a> --}}
</div>
