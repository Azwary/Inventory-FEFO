@extends('views.layouts.app')

@section('title', 'Laporan & Analisis Data')

@section('content')
    <div class="space-y-6">

        <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white">
            <h2 class="text-2xl font-bold">Laporan & Analisis Data</h2>
            <p class="text-sm opacity-90">Monitoring stok aktif dan aktivitas gudang</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <form method="GET" action="{{ route('pimpinan.laporan-audit.index') }}">
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

                    @isset($stokAktif)
                        <div class="flex gap-2">
                            <a href="{{ route('pimpinan.laporan-audit.export.csv', request()->query()) }}"
                                class="w-full text-center bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
                                CSV
                            </a>
                            <a href="{{ route('pimpinan.laporan-audit.export.pdf', request()->query()) }}"
                                class="w-full text-center bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
                                PDF
                            </a>
                        </div>
                    @endisset
                </div>
            </form>
        </div>


        @isset($stokAktif)
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Obat</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Kadaluarsa</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($stokAktif as $s)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 font-medium">
                                    {{ $s->barang->obat?->nama_obat ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs bg-gray-200 rounded">
                                        {{ $s->nomor_batch }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $s->lokasi?->nama_lokasi ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ \Carbon\Carbon::parse($s->tanggal_kadaluarsa)->format('d-m-Y') }}
                                </td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    {{ $s->jumlah_masuk }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">
                                    Tidak ada data laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endisset

    </div>
@endsection
