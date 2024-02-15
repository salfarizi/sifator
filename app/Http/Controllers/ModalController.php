<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Sele;
use App\Models\Modal;
use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modal = Modal::first();
        $harga_beli = Sele::sum('harga_beli');
        $harga_beli_kredit = Kredit::sum('harga_beli');
        $harga_jual = Sele::sum('harga_jual');
        $harga_jual_kredit = Kredit::sum('harga_jual_kredit');
        $dp = Kredit::sum('dp');
        $pencairan = Kredit::sum('pencairan');
        $komisi = Kredit::sum('komisi');
        $saldo_bank = $modal->modal + ($harga_jual - $harga_beli) + $komisi;
        $data = [
            'title' => 'Informasi Modal | SIFATOR',
            'judul' => 'Informasi Modal',
            'breadcumb1' => 'Modal',
            'breadcumb2' => 'Informasi Modal',
            'data' => $modal,
            'bike_sele' => Modal::jumlah_asset(),
            'sisa_modal' => $modal->modal - Modal::jumlah_asset(),
            'laba' => $harga_jual - $harga_beli,
            'jumlah_unit' => Bike::where('status', 'READY STOCK')->count('id'),
            'semua_unit' => Bike::count('id'),
            'sisa_bank' => $saldo_bank,
            'laba_kredit' => $harga_jual_kredit - $harga_beli_kredit,
            'komisi' => $komisi,
        ];
        return view('modal.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modal $modal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modal $modal)
    {
        $rules = [
            'modal' => 'required',
        ];
        $pesan = [
            'modal.required' => 'Tidak boleh kosong'
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'modal' => preg_replace('/[,]/', '', $request->modal)
            ];
            Modal::where('unique', $request->unique)->update($data);
            return response()->json(['success' => 'Modal Berhasil Diset']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modal $modal)
    {
        //
    }

    public function refresh_page(Request $request)
    {
        $modal = Modal::first();
        $jumlah_asset =  DB::table('bikes')
            ->join('buys', 'bikes.id', '=', 'buys.bike_id')
            ->where('bikes.status', 'READY STOCK')
            ->sum('buys.harga_beli');
        $harga_beli = Sele::sum('harga_beli');
        $harga_jual = Sele::sum('harga_jual');
        $data = [
            'data' => $modal->modal,
            'bike_sele' => $jumlah_asset,
            'sisa_modal' => $modal->modal - $jumlah_asset,
            'laba' => $harga_jual - $harga_beli
        ];

        return response()->json(['success' => $data]);
    }
}
