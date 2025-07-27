<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'kepsek') {
            return inertia('User/KepsekDashboard');
        }

        return inertia('User/PendaftarDashboard');
    }
}
