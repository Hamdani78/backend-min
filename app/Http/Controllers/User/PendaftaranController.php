<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;


class PendaftaranController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        if ($user->pendaftar) {
            return redirect()->route('user.pendaftaran.show');
        }

        return inertia('User/Pendaftaran/Formulir');
    }

    public function show()
    {
        $user = auth()->user();
        $pendaftar = $user->pendaftar->load(['orangTuas', 'wali']);

        return Inertia::render('User/Pendaftaran/Detail', [
            'pendaftar' => $pendaftar,
        ]);
    }

    public function edit()
    {
        $pendaftar = auth()->user()->pendaftar->load(['orangTuas', 'wali']);

        return Inertia::render('User/Pendaftaran/Edit', [
            'pendaftar' => $pendaftar,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'anak_ke' => 'required|numeric',
            'jumlah_saudara' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'cita_cita' => 'required|string|max:100',
            'hobi' => 'required|string|max:100',
            'bahasa' => 'required|string|max:100',
            'keadaan_jasmani' => 'required|string|max:100',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tinggal_dengan' => 'required|string',
            'pembiaya' => 'required|string',
            'jarak_ke_madrasah' => 'required|string',
            'kebutuhan_khusus' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'pra_sekolah' => 'required|string',
            'nama_pra_sekolah' => 'required|string|max:100',
            'kip_nama' => 'nullable|string',
            'kip_nomor' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4086',
            'imunisasi' => 'nullable|array',
            'imunisasi.*' => 'string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_pendaftar', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status_pendaftaran'] = 'formulir';

        $pendaftar = Pendaftar::create($validated);

        if ($request->has('orang_tuas')) {
            foreach ($request->input('orang_tuas') as $orangTuaData) {
                $pendaftar->orangTuas()->create($orangTuaData);
            }
        }

        if ($request->filled('wali')) {
            $pendaftar->wali()->create($request->input('wali'));
        }

        return redirect()->route('user.dashboard')->with('status', 'Pendaftaran berhasil dikirim.');
    }

    public function update(Request $request)
    {
        $pendaftar = auth()->user()->pendaftar;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'no_kk' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'anak_ke' => 'required|numeric',
            'jumlah_saudara' => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'cita_cita' => 'required|string|max:100',
            'hobi' => 'required|string|max:100',
            'bahasa' => 'required|string|max:100',
            'keadaan_jasmani' => 'required|string|max:100',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tinggal_dengan' => 'required|string',
            'pembiaya' => 'required|string',
            'jarak_ke_madrasah' => 'required|string',
            'kebutuhan_khusus' => 'nullable|string',
            'kebutuhan_disabilitas' => 'nullable|string',
            'pra_sekolah' => 'required|string',
            'nama_pra_sekolah' => 'required|string|max:100',
            'kip_nama' => 'nullable|string',
            'kip_nomor' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:4086',
            'imunisasi' => 'nullable|array',
            'imunisasi.*' => 'string',
        ]);

        if ($request->hasFile('foto')) {
            if ($pendaftar->foto && Storage::disk('public')->exists($pendaftar->foto)) {
                Storage::disk('public')->delete($pendaftar->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto_pendaftar', 'public');
        }

        $pendaftar->update($validated);

        $pendaftar->orangTuas()->delete();
        foreach ($request->input('orang_tuas', []) as $ortu) {
            $pendaftar->orangTuas()->create($ortu);
        }

        $pendaftar->wali()->delete();
        if ($request->filled('wali')) {
            $pendaftar->wali()->create($request->input('wali'));
        }

        return redirect()->route('user.pendaftaran.show')->with('success', 'Data berhasil diperbarui.');
    }

    public function cetakBukti()
    {
        $user = auth()->user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            abort(404, 'Data pendaftar tidak ditemukan.');
        }

        $pdf = Pdf::loadView('pdf.bukti-pendaftaran', compact('pendaftar'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'bukti-pendaftaran.pdf');
    }
}
