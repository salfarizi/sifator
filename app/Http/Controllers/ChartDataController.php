<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buy; // Sesuaikan dengan model yang sesuai dengan struktur tabel dan kolom pada database

class ChartDataController extends Controller
{
    public function index()
    {
        $data = Buy::all(); // Mengambil semua data dari tabel ChartData

        return response()->json($data);
    }
}
