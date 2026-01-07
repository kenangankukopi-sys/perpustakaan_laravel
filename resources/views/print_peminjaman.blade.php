<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        table, th, td{
            border:1px solid #000;
        }
        th, td{
            padding: 6px 10px;
            text-align:left;
        }
    </style>
</head>
<body onload="window.print()">

<h2>Laporan Peminjaman</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Kategori</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($laporan as $l)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $l->nama_peminjam }}</td>
            <td>{{ $l->judul_buku }}</td>
            <td>{{ $l->kategori ?? '-' }}</td>
            <td>{{ $l->tanggal_pinjam }}</td>
            <td>{{ $l->tanggal_kembali ?? '-' }}</td>
            <td>
                @if($l->status == 'Dipinjam')
                   {{ $l->status }}
                @elseif($l->status == 'Dikembalikan')
                    {{ $l->status }}
                @else
                    {{ $l->status }}
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;">Tidak ada data peminjaman ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
