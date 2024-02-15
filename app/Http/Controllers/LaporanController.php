<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Laporan Penjualan | SIFATOR',
            'judul' => 'Laporan',
            'breadcumb1' => 'Laporan Penjualan',
            'breadcumb2' => 'Cetak Laporan',
        ];
        return view('laporanPenjualan.index', $data);
    }
    public function index_pembelian()
    {
        $data = [
            'title' => 'Laporan Pembelian | SIFATOR',
            'judul' => 'Laporan',
            'breadcumb1' => 'Laporan Pembelian',
            'breadcumb2' => 'Cetak Laporan',
        ];
        return view('laporanPembelian.index', $data);
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
