<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .signature {
            margin-top: 4rem;
            text-align: right;
        }
    </style>
</head>

<body>
    <h3 class="text-center mb-4">SURAT PERNYATAAN ORANG TUA / WALI<br>CALON MURID MIN 1 ROKAN HULU</h3>

    <p>Yang bertanda tangan di bawah ini:</p>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 35%;">Nama</td>
            <td>: {{ $data['nama'] }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: {{ $data['pekerjaan'] }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>: {{ $data['agama'] }}</td>
        </tr>
        <tr>
            <td>Orang Tua / Wali dari</td>
            <td>: {{ $data['anak'] }}</td>
        </tr>
        <tr>
            <td>Hubungan dengan calon murid</td>
            <td>: {{ $data['hubungan'] }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $data['alamat'] }}</td>
        </tr>
    </table>


    <p>Dengan sungguh-sungguh dan penuh kesadaran, MENYATAKAN:</p>
    <ol>
        <li>Bersedia membimbing dan mengawasi anak kami tersebut untuk memenuhi peraturan madrasah.</li>
        <li>Anak kami akan mengikuti Pendidikan Agama Islam di madrasah.</li>
        <li>Menerima sanksi:
            <ol type="a">
                <li>Tidak diperkenankan mengikuti pelajaran sementara jika melanggar tata tertib.</li>
                <li>Dikeluarkan jika melanggar ketentuan berat dari madrasah.</li>
            </ol>
        </li>
    </ol>

    <div class="signature">
        <p>Pasir Pengaraian, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="mt-4"><strong>Yang Membuat Pernyataan</strong><br>Orang Tua / Wali</p>
    </div>
</body>

</html>