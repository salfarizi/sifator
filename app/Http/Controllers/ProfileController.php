<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Detail Profile | SIFATOR',
            'judul' => 'Detail Profile',
            'breadcumb1' => 'Profile',
            'breadcumb2' => 'Detail Profile',
        ];
        return view('profile.index', $data);
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
    }

    public function update_data(Request $request)
    {
        $cek_password = password_verify($request->password, auth()->user()->password);
        if (!$cek_password) {
            return redirect()->back()->with('pesan2', 'Password Tidak Sesuai');
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|email:dns',
            'photo' => 'image|file|max:2072',
        ];
        $pesan = [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email harus valid',
            'photo.image' => 'File Harus Berupa Gambar',
            'photo.max' => 'Gambar Maksimal Berukuran 2MB',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->new_password) {
                $data['password'] = bcrypt($request->new_password);
            }
            if ($request->photo) {
                if ($request->oldPhoto) {
                    $data['photo'] = $request->file('photo')->store('photo_user');
                    Storage::delete($request->oldPhoto);
                } else {
                    $data['photo'] = $request->file('photo')->store('photo_user');
                }
            }
            users::where('id', auth()->user()->id)->update($data);
            return redirect()->back()->with('pesan', 'Profile Berhasil Diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
