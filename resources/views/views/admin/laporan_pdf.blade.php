<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Barang</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        td {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>LAPORAN TRANSAKSI BARANG</h2>

    <p>
        Periode:
        {{ $dari ?? '-' }} s/d {{ $sampai ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nama Obat</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>

            {{-- BARANG MASUK --}}
            @foreach ($barangMasuk as $i => $m)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>Masuk</td>
                    <td>{{ $m->barang?->obat?->nama_obat ?? '-' }}</td>
                    <td>{{ $m->tanggal_masuk }}</td>
                    <td>{{ $m->jumlah_masuk }}</td>
                    <td>{{ $m->keterangan }}</td>
                </tr>
            @endforeach

            {{-- BARANG KELUAR --}}
            @foreach ($barangKeluar as $j => $k)
                <tr>
                    <td>{{ $barangMasuk->count() + $j + 1 }}</td>
                    <td>Keluar</td>
                    <td>{{ $k->barang?->obat?->nama_obat ?? '-' }}</td>
                    <td>{{ $k->created_at->format('Y-m-d') }}</td>
                    <td>{{ $k->jumlah_keluar }}</td>
                    <td>{{ $k->keterangan }}</td>
                </tr>
            @endforeach

            @if ($barangMasuk->isEmpty() && $barangKeluar->isEmpty())
                <tr>
                    <td colspan="6" style="text-align:center">
                        Tidak ada data transaksi
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

</body>

</html>
