@extends('views.layouts.app')

@section('title', 'Laporan & Analisis Data')

@section('content')
    <div class="space-y-6">

        {{-- <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white">
            <h2 class="text-2xl font-bold">Laporan & Analisis Data</h2>
            <p class="text-sm opacity-90">Monitoring stok aktif dan aktivitas gudang</p>
        </div> --}}


        <div class="p-6 bg-white rounded-lg shadow">
            <form method="GET" action="{{ route('admin.laporan-audit.index') }}">
                <p class="font-semibold mb-4 text-gray-700">Filter Laporan</p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dari }}"
                            class="w-full border rounded px-3 py-2 mt-1">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampai }}"
                            class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Generate Laporan
                        </button>
                    </div>
                    @if (isset($barangMasuk) || isset($barangKeluar))
                        <div class="flex gap-2">
                            <a href="{{ route('admin.laporan-audit.export.csv', request()->query()) }}"
                                class="w-full text-center bg-green-600 text-white px-3 py-2 rounded">
                                CSV
                            </a>
                            <a href="{{ route('admin.laporan-audit.export.pdf', request()->query()) }}"
                                class="w-full text-center bg-red-600 text-white px-3 py-2 rounded">
                                PDF
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-green-50 rounded">
                <p class="text-sm text-gray-600">Total Barang Masuk</p>
                <p class="text-xl font-bold text-green-600">{{ $totalMasuk }}</p>
            </div>
            <div class="p-4 bg-red-50 rounded">
                <p class="text-sm text-gray-600">Total Barang Keluar</p>
                <p class="text-xl font-bold text-red-600">{{ $totalKeluar }}</p>
            </div>
        </div>

        @if (isset($barangMasuk) || isset($barangKeluar))
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3 text-left">Nama Barang</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        {{-- BARANG MASUK --}}
                        @forelse($barangMasuk as $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-center text-green-600 font-semibold">
                                    Masuk
                                </td>
                                <td class="px-4 py-2">
                                    {{ $m->barang?->obat?->nama_obat ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ \Carbon\Carbon::parse($m->tanggal_masuk)->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    {{ $m->jumlah_masuk }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $m->keterangan }}
                                </td>
                            </tr>
                        @empty
                        @endforelse

                        {{-- BARANG KELUAR --}}
                        @forelse($barangKeluar as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-center text-red-600 font-semibold">
                                    Keluar
                                </td>
                                <td class="px-4 py-2">
                                    {{ $k->barang?->obat?->nama_obat ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $k->created_at->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    {{ $k->jumlah_keluar }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $k->keterangan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">
                                    Tidak ada transaksi
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        @endif


    </div>
@endsection
