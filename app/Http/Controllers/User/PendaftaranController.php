<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pendaftar;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class PendaftaranController extends Controller
{
    private function locked($pendaftar): bool
    {
        return (bool) optional($pendaftar)->is_verified;
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $pendaftar = $user->pendaftar()
            ->with(['orangTuas', 'ayah', 'ibu', 'wali'])
            ->first();

        if ($this->locked($pendaftar)) {
            return redirect()->route('user.pendaftaran.show');
        }

        return Inertia::render('User/Pendaftaran/Formulir', [
            'pendaftar' => $pendaftar,
            'ayah'      => $pendaftar?->ayah,
            'ibu'       => $pendaftar?->ibu,
            'wali'      => $pendaftar?->wali,
        ]);
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $pendaftar = $user->pendaftar()
            ->with(['orangTuas', 'ayah', 'ibu', 'wali'])
            ->first();

        if (!$this->locked($pendaftar)) {
            return redirect()->route('user.pendaftaran.create');
        }

        return Inertia::render('User/Pendaftaran/Detail', [
            'pendaftar' => $pendaftar,
            'ayah'      => $pendaftar?->ayah,
            'ibu'       => $pendaftar?->ibu,
            'wali'      => $pendaftar?->wali,
        ]);
    }

    public function store(Request $request)
    {
        return $this->storeDataDiri($request);
    }

    // ===== Data Diri =====
    public function storeDataDiri(Request $request)
    {
        $user = $request->user();
        $pendaftar = $user->pendaftar;

        if ($this->locked($pendaftar)) {
            return redirect()->route('user.pendaftaran.show');
        }

        $validated = $request->validate([
            'nama'                   => 'required|string|max:255',
            'tempat_lahir'           => 'required|string|max:100',
            'tanggal_lahir'          => 'required|date',
            'nik'                    => 'required|string|max:20',
            'no_kk'                  => 'required|string|max:20',
            'anak_ke'                => 'required|numeric',
            'jumlah_saudara'         => 'required|numeric',
            'jenis_kelamin'          => 'required|in:L,P',
            'agama'                  => 'required|string|max:50',
            'berat_badan'            => 'nullable|numeric',
            'tinggi_badan'           => 'nullable|numeric',
            'cita_cita'              => 'nullable|string|max:100',
            'hobi'                   => 'nullable|string|max:100',
            'bahasa'                 => 'nullable|string|max:100',
            'keadaan_jasmani'        => 'nullable|string|max:100',
            'alamat'                 => 'required|string|max:255',
            'provinsi'               => 'nullable|string|max:100',
            'kabupaten'              => 'nullable|string|max:100',
            'kecamatan'              => 'nullable|string|max:100',
            'kelurahan'              => 'nullable|string|max:100',
            'no_hp'                  => 'nullable|string|max:20',
            'tinggal_dengan'         => 'nullable|string|max:50',
            'pembiaya'               => 'nullable|string|max:50',
            'jarak_ke_madrasah'      => 'nullable|string|max:50',
            'kebutuhan_khusus'       => 'nullable|string|max:100',
            'kebutuhan_disabilitas'  => 'nullable|string|max:100',
            'pra_sekolah'            => 'nullable|string|max:50',
            'nama_pra_sekolah'       => 'nullable|string|max:100',
            'kip_nama'               => 'nullable|string|max:100',
            'kip_nomor'              => 'nullable|string|max:50',
            'imunisasi'              => 'nullable|array',
            'imunisasi.*'            => 'string',
            'foto'                   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $payload = collect($validated)->except('foto')->toArray();

        DB::transaction(function () use ($request, $user, &$pendaftar, $payload) {
            if ($pendaftar) {
                $pendaftar->update($payload);
            } else {
                $pendaftar = $user->pendaftar()->create($payload);
            }

            if ($request->hasFile('foto')) {
                if ($pendaftar->foto) {
                    Storage::disk('public')->delete($pendaftar->foto);
                }
                $path = $request->file('foto')->store('pendaftar', 'public');
                $pendaftar->update(['foto' => $path]);
            }

            if ($pendaftar->verification_note) {
                $pendaftar->update(['verification_note' => null]);
            }
        });

        return redirect()
            ->route('user.pendaftaran.create', ['tab' => 'orang-tua'])
            ->with('success', 'Data diri tersimpan.');
    }

    public function updateDataDiri(Request $request)
    {
        return $this->storeDataDiri($request);
    }

    // ===== ORANG TUA =====
    protected function upsertOrtu(Request $r, string $tipe)
    {
        $pendaftar = Pendaftar::where('user_id', auth()->id())->first();
        if (!$pendaftar) {
            return back()->with('error', 'Simpan Data Diri terlebih dahulu.');
        }

        if ($this->locked($pendaftar)) {
            return redirect()->route('user.pendaftaran.show');
        }

        $data = $r->validate([
            'nama'           => 'nullable|string|max:150',
            'status'         => 'nullable|in:Masih Hidup,Sudah Meninggal',
            'nik'            => 'nullable|string|max:30',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'pendidikan'     => 'nullable|string|max:100',
            'pekerjaan'      => 'nullable|string|max:100',
            'penghasilan'    => 'nullable|string|max:100',
            'no_hp'          => 'nullable|string|max:20',
            'tempat_tinggal' => 'nullable|string|max:100',
            'alamat'         => 'nullable|string',
            'provinsi'       => 'nullable|string|max:100',
            'kabupaten'      => 'nullable|string|max:100',
            'kecamatan'      => 'nullable|string|max:100',
            'kelurahan'      => 'nullable|string|max:100',
            'kks'            => 'nullable|string|max:100',
            'pkh'            => 'nullable|string|max:100',
        ]);

        $pendaftar->orangTuas()->updateOrCreate(
            ['tipe' => $tipe],
            array_merge($data, ['tipe' => $tipe])
        );

        if ($pendaftar->verification_note) {
            $pendaftar->update(['verification_note' => null]);
        }

        return back()->with('success', "$tipe disimpan.");
    }

    public function upsertAyah(Request $r)
    {
        return $this->upsertOrtu($r, 'Ayah');
    }

    public function upsertIbu(Request $r)
    {
        return $this->upsertOrtu($r, 'Ibu');
    }

    // ===== Wali =====
    public function upsertWali(Request $request)
    {
        $pendaftar = $request->user()->pendaftar;

        if ($this->locked($pendaftar)) {
            return redirect()->route('user.pendaftaran.show');
        }

        $data = $request->validate([
            'nama'               => 'nullable|string|max:255',
            'hubungan_keluarga'  => 'nullable|string|max:100',
        ]);

        $pendaftar->wali()->updateOrCreate([], $data);

        if ($pendaftar->verification_note) {
            $pendaftar->update(['verification_note' => null]);
        }

        return redirect()
            ->route('user.pendaftaran.create', ['tab' => 'wali'])
            ->with('success', 'Data wali tersimpan.');
    }

    public function cetakBukti()
    {
        $pendaftar = auth()->user()->pendaftar;
        abort_if(!$pendaftar, 404, 'Data pendaftar tidak ditemukan.');

        $pdf = Pdf::loadView('pdf.bukti-pendaftaran', compact('pendaftar'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'bukti-pendaftaran.pdf');
    }
}
