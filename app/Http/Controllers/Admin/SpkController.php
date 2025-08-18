<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Services\SpkAuto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SpkExport;
use Illuminate\Support\Facades\DB;

class SpkController extends Controller
{
    public function index()
    {
        // Ambil pendaftar + relasi yang dibutuhkan
        $pendaftars = Pendaftar::with(['spkNilai', 'berkas'])->orderBy('nama')->get();

        foreach ($pendaftars as $p) {
            SpkAuto::sync($p);
        }
        $pendaftars->load('spkNilai');

        // Susun data untuk tabel
        $rows = $pendaftars->map(fn($p) => [
            'id'        => $p->id,
            'nama'      => $p->nama,
            'umur'      => $p->spkNilai->umur ?? null,
            'zonasi'    => $p->spkNilai->zonasi ?? null,
            'berkas'    => $p->spkNilai->berkas ?? null,
            'wawancara' => $p->spkNilai->wawancara ?? null,
            'nilai_spk' => $p->nilai_spk,
        ]);

        return Inertia::render('Admin/Spk/Index', [
            'pendaftars' => $rows,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'wawancara'    => 'required|numeric|min:0|max:0.05',
        ]);

        $p = Pendaftar::findOrFail($data['pendaftar_id']);


        SpkAuto::setWawancara($p, (float) $data['wawancara']);

        return back()->with('success', 'Nilai wawancara disimpan & total SPK diperbarui.');
    }

    private function computeHasil(float $threshold = 0.6): array
    {
        $list = Pendaftar::with('spkNilai')->get();

        $hasil = [];
        foreach ($list as $p) {
            $n = $p->spkNilai;
            if (!$n) continue;

            $total =
                (float) $n->umur +
                (float) $n->zonasi +
                (float) $n->berkas +
                (float) $n->wawancara;

            $hasil[] = [
                'id'     => $p->id,
                'nama'   => $p->nama,
                'nilai'  => round($total, 4),
                'status' => $total >= $threshold ? 'Lulus' : 'Tidak Lulus',
            ];
        }

        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        return $hasil;
    }


    public function proses(Request $request)
    {
        $threshold = (float)($request->input('threshold', 0.6));
        return response()->json($this->computeHasil($threshold));
    }

    public function exportPdf(Request $request)
    {
        $threshold = (float)($request->input('threshold', 0.6));
        $data = $this->computeHasil($threshold);
        $pdf = Pdf::loadView('pdf.hasil_spk', ['data' => $data, 'threshold' => $threshold]);
        return $pdf->download('hasil_spk.pdf');
    }

    public function exportExcel(Request $request)
    {
        $threshold = (float)($request->input('threshold', 0.6));
        $data = $this->computeHasil($threshold);
        return Excel::download(new SpkExport($data), 'hasil_spk.xlsx');
    }

public function terapkanHasil(Request $request)
{
    $threshold = (float)($request->input('threshold', 0.6));
    $hasil = $this->computeHasil($threshold);

    DB::transaction(function () use ($hasil) {
        foreach ($hasil as $row) {
            $p = Pendaftar::find($row['id']);
            if (!$p) continue;

            $p->update([
                'nilai_spk'          => round($row['nilai'], 2), 
                'status_lulus'       => $row['status'] === 'Lulus' ? 'lulus' : 'tidak_lulus',
                'status_pendaftaran' => 'pengumuman', 
            ]);
        }
    });

    return back()->with('success', 'Hasil SPK diterapkan ke database.');
}
}
