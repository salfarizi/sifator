<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $count = Setting::count();
        $data = [
            'title' => 'Setting | SIFATOR',
            'judul' => 'Setting',
            'breadcumb1' => 'Setting',
            'breadcumb2' => 'Detail Setting',
            'count' => $count
        ];
        if ($count > 0) {
            $data['setting'] = Setting::first();
        }
        return view('setting.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_toko' => 'required',
            'nama_pemilik' => 'required',
            'kota' => 'required',
            'alamat_toko' => 'required',
        ];
        $pesan = [
            'nama_toko.required' => 'Nama toko tidak boleh kosong',
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong',
            'alamat_toko.required' => 'Nama toko tidak boleh kosong',
            'kota.required' => 'Kota tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return redirect()->back()->with('pesan2', 'Silahkan input data yang benar')->withErrors($validator);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'nama_toko' => strtoupper($request->nama_toko),
                'nama_pemilik' => ucwords(strtolower($request->nama_pemilik)),
                'alamat_toko' => ucwords(strtolower($request->alamat_toko)),
                'kota' => ucwords(strtolower($request->kota)),
                'kontak' => $request->kontak,
            ];
            Setting::create($data);
            return redirect('/setting')->with('pesan', 'Data berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'nama_toko' => 'required',
            'nama_pemilik' => 'required',
            'kota' => 'required',
            'alamat_toko' => 'required',
        ];
        $pesan = [
            'nama_toko.required' => 'Nama toko tidak boleh kosong',
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong',
            'alamat_toko.required' => 'Nama toko tidak boleh kosong',
            'kota.required' => 'Kota tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return redirect()->back()->with('pesan2', 'Silahkan input data yang benar')->withErrors($validator);
        } else {
            $data = [
                'nama_toko' => strtoupper($request->nama_toko),
                'nama_pemilik' => ucwords(strtolower($request->nama_pemilik)),
                'alamat_toko' => ucwords(strtolower($request->alamat_toko)),
                'kota' => ucwords(strtolower($request->kota)),
                'kontak' => $request->kontak,
            ];
            Setting::where('unique', $setting->unique)->update($data);
            return redirect('/setting')->with('pesan', 'Data berhasil diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
