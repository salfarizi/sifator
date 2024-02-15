<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboards | SIFATOR',
            'judul' => 'Dashboards',
            'breadcumb1' => 'Dashboard',
            'breadcumb2' => 'Info Transaksi Showroom',
            'semua_unit' => Bike::count('id'),
            'total_jual' => Bike::where('status', 'TERJUAL')->count('id'),
        ];
        return view('index', $data);
    }
}
