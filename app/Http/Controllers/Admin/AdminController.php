<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Models\Kegiatan;
use App\Models\Fasilitas;
use App\Models\FasilitasImages;
use App\Models\KegiatanImages;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware untuk redirect jika sudah login saat akses /admin/login
        $this->middleware('guest:admin')->except(['dashboard', 'logout']);
        $this->middleware('auth:admin')->only(['dashboard']);
    }

    public function dashboard()
    {
        $totalPegawai = Pegawai::count();
        $totalSiswa = Siswa::count();
        $totalKegiatan = Kegiatan::count();
        $totalFasilitas = Fasilitas::count();
        $kegiatanImages = KegiatanImages::all();
        $fasilitasImages = FasilitasImages::all();
        $kegiatan = Kegiatan::all();
        $fasilitas = Fasilitas::all();

        return Inertia::render('Admin/AdminDashboard', [
            'totalPegawai' => $totalPegawai,
            'totalSiswa' => $totalSiswa,
            'totalKegiatan' => $totalKegiatan,
            'totalFasilitas' => $totalFasilitas,
            'kegiatanImages' => $kegiatanImages,
            'fasilitasImages' => $fasilitasImages,
            'kegiatan' => $kegiatan,
            'fasilitas' => $fasilitas,
        ]);
    }
    public function showLoginForm()
    {
        return Inertia::render('Admin/AdminLogin', [
            'csrf_token' => csrf_token(),
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return response()->noContent(); 
        }

        return response()->json([
            'message' => 'Email atau password salah.'
        ], 422);
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        //invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
