<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash as FacadesHash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = FacadesHash::make('12345678');
        $adminRecords = [
            ['name' => 'admin', 'email' => 'adminmin1@gmail.com', 'password' => $password]
        ];
        Admin::insert($adminRecords);
    }
}
