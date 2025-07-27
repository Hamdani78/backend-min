<!DOCTYPE html>
<html>
<head>
    <title>Data SPK</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Data SPK</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $spk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $spk->nama }}</td>
                    <td>{{ $spk->nilai }}</td>
                    <td>{{ $spk->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
