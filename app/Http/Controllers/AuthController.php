<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    public function register()
    {
        return view('auth.register', ['roles' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:7',
            'password_confirmation' => 'required',
            'roles' => 'required'
        ];
        $pesan = [
            'name.required' => 'Nama Tidak Boleh Kosong',
            'email.required' => 'Email Tidak boleh kosong',
            'email.email' => 'Email Harus Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'password.required' => 'Password Tidak Boleh Kosong',
            'password.confirmed' => 'Password Tidak Sesuai/Sama',
            'password.min' => 'Password Minimal 7 Karakter',
            'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
            'roles.required' => 'Role Tidak Boleh Kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return redirect('./auth/create')->withErrors($validator);
        } else {
            $validatedData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            $validatedData['roles'] = strtoupper($request->roles);
            $validatedData['password'] = bcrypt($request->password);
            user::create($validatedData);
            return redirect('/user')->with('success', 'Akun Berhasil Didaftarkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, users $users)
    {
        //
    }
    public function update_password(Request $request)
    {
        $rules = [
            'password' => 'required|confirmed|min:7',
            'password_confirmation' => 'required',
        ];
        $pesan = [
            'password.required' => 'Password Tidak Boleh Kosong',
            'password.confirmed' => 'Password Tidak Sesuai/Sama',
            'password.min' => 'Password Minimal 7 Karakter',
            'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $validatedData = [
                'password' => bcrypt($request->password)
            ];
            User::where('id', $request->unique)->update($validatedData);
            return response()->json(['success' => 'Password Berhasil Diubah']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $users)
    {
        //
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }

    // public function authenticate(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ], [
    //         'email.required' => 'Email tidak boleh kosong',
    //         'email.email' => 'Email harus valid',
    //         'password.required' => 'Email tidak boleh kosong',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/');
    //     }

    //     return back()->with('error', 'Username atau Password Salah');
    // }
}
