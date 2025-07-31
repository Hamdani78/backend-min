<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        .bordered td {
            border: 1px solid #000;
        }

        tr {
            page-break-inside: avoid;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="center">
        <img src="{{ public_path('images/ic_logo.png') }}" class="logo" alt="Logo Madrasah">
        <h2>MIN 1 Rokan Hulu</h2>
        <p><strong>Bukti Pendaftaran Calon Peserta Didik Baru</strong></p>
    </div>

    <table class="bordered">
        <tr>
            <td><strong>Nama Lengkap</strong></td>
            <td>{{ $pendaftar->nama }}</td>
        </tr>
        <tr>
            <td><strong>Tempat, Tanggal Lahir</strong></td>
            <td>{{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Kelamin</strong></td>
            <td>{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td><strong>NIK</strong></td>
            <td>{{ $pendaftar->nik }}</td>
        </tr>
        <tr>
            <td><strong>No. KK</strong></td>
            <td>{{ $pendaftar->no_kk }}</td>
        </tr>
        <tr>
            <td><strong>Status Pendaftaran</strong></td>
            <td>{{ ucfirst($pendaftar->status_pendaftaran) }}</td>
        </tr>
    </table>

    <div class="signature">
        Pasir Pengaraian, {{ now()->translatedFormat('d F Y') }} <br><br><br><br>
        <strong>{{ $pendaftar->nama }}</strong>
    </div>

</body>

</html>