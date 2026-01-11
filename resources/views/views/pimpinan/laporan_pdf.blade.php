<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
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

    <h2>LAPORAN STOK AKTIF</h2>

    <p>
        Periode:
        {{ $dari ?? '-' }} s/d {{ $sampai ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>No Batch</th>
                <th>Lokasi</th>
                <th>Tgl Masuk</th>
                <th>Exp</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokAktif as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->barang->obat?->nama_obat ?? '-' }}</td>
                    <td>{{ $s->nomor_batch }}</td>
                    <td>{{ $s->lokasi?->nama_lokasi ?? '-' }}</td>
                    <td>{{ $s->tanggal_masuk }}</td>
                    <td>{{ $s->tanggal_kadaluarsa }}</td>
                    <td>{{ $s->jumlah_stok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
