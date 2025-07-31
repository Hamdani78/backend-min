<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\SpkNilai;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpkExport;

class SpkController extends Controller
{
    public function index()
    {
        $pendaftars = Pendaftar::with('user', 'spkNilai')->get();

        foreach ($pendaftars as $p) {
            $p->setAttribute('umur', $p->spkNilai->umur ?? null);
            $p->setAttribute('zonasi', $p->spkNilai->zonasi ?? null);
            $p->setAttribute('berkas', $p->spkNilai->berkas ?? null);
            $p->setAttribute('wawancara', $p->spkNilai->wawancara ?? null);
        }

        return Inertia::render('Admin/Spk/Index', [
            'pendaftars' => $pendaftars
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'umur' => 'required|numeric|min:0|max:0.5',
            'zonasi' => 'required|numeric|min:0|max:0.3',
            'berkas' => 'required|numeric|min:0|max:0.15',
            'wawancara' => 'required|numeric|min:0|max:0.05',
        ]);

        SpkNilai::updateOrCreate(
            ['pendaftar_id' => $request->pendaftar_id],
            $request->only('umur', 'zonasi', 'berkas', 'wawancara')
        );

        return redirect()->back()->with('status', 'Nilai SPK berhasil disimpan!');
    }

    public function proses()
    {
        $data = Pendaftar::with('spkNilai')->get();

        $maks = [
            'umur' => 0.5,
            'zonasi' => 0.3,
            'berkas' => 0.15,
            'wawancara' => 0.05,
        ];

        $bobot = [
            'umur' => 0.5,
            'zonasi' => 0.3,
            'berkas' => 0.15,
            'wawancara' => 0.05,
        ];

        $hasil = [];

        foreach ($data as $d) {
            $nilai = $d->spkNilai;

            if (!$nilai) continue;

            $umur = $nilai->umur;
            $zonasi = $nilai->zonasi;
            $berkas = $nilai->berkas;
            $wawancara = $nilai->wawancara;

            $v = (($umur / $maks['umur']) * $bobot['umur']) +
                (($zonasi / $maks['zonasi']) * $bobot['zonasi']) +
                (($berkas / $maks['berkas']) * $bobot['berkas']) +
                (($wawancara / $maks['wawancara']) * $bobot['wawancara']);

            $hasil[] = [
                'id' => $d->id, 
                'nama' => $d->nama,
                'nilai' => round($v, 4),
                'status' => $v >= 0.6 ? 'Lulus' : 'Tidak Lulus'
            ];
        }
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        return response()->json($hasil);
    }
    public function exportPdf()
    {
        $data = $this->proses()->getData();
        $pdf = Pdf::loadView('pdf.hasil_spk', ['data' => $data]);
        return $pdf->download('hasil_spk.pdf');
    }

    public function exportExcel()
    {
        $data = $this->proses()->getData();
        return Excel::download(new SpkExport($data), 'hasil_spk.xlsx');
    }

    public function terapkanHasil()
    {
        $peringkat = session('hasil_spk');
        if (!$peringkat) {
            return back()->with('error', 'Tidak ada data SPK untuk diterapkan.');
        }

        foreach ($peringkat as $item) {
            $pendaftar = \App\Models\Pendaftar::find($item['id']);
            if ($pendaftar) {
                $status = $item['nilai'] >= 0.6 ? 'lulus' : 'tidak_lulus';
                $pendaftar->update([
                    'nilai_spk' => $item['nilai'],
                    'status_lulus' => $status,
                ]);
            }
        }

        return back()->with('success', 'Hasil SPK berhasil diterapkan ke database.');
    }

    public function simpanKeSession(Request $request)
    {
        session(['hasil_spk' => $request->input('data')]);
        return response()->json(['message' => 'Hasil SPK disimpan ke session.']);
    }
}
