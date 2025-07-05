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

class AdminController extends Controller
{
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

        return view('admin.dashboard', compact('totalPegawai', 'totalSiswa', 'totalKegiatan', 'totalFasilitas', 'fasilitasImages' , 'fasilitas', 'kegiatan', 'kegiatanImages'));
    }

    public function login(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if(Auth::guard('admin')->attempt(['email'=>$data['email'], 'password'=>$data['password']])){
                return redirect("admin/dashboard");
            }else{
                return redirect()->back( )->with('error', 'Invalid Email or Password');
            };
        }
        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}