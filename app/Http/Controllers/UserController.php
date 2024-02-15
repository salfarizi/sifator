<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Data User | SIFATOR',
            'judul' => 'Data User',
            'breadcumb1' => 'Akses User',
            'breadcumb2' => 'Data User',
        ];
        return view('user.index', $data);
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    public function change_roles(Request $request)
    {
        $query = User::where('id', $request->id)->first();
        $roles = Role::all();
        echo '
        <form id="form_role">
            <input type="hidden" id="id_user" name="id_user" value="' . $request->id . '">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <div class="input-group mb-3">
                <select class="form-select" id="role_user" name="role">';
        foreach ($roles as $role) {
            echo '<option value="' . $role->name . '"';
            if ($query->roles == $role->name) {
                echo 'selected';
            } else {
                echo '';
            }
            echo '>' . $role->name . '</option>';
        }
        echo   '</select>
            </div>
        </form>
        ';
    }

    public function update_roles(Request $request)
    {
        $query = User::where('id', $request->id_user)->first();
        if ($query->name == "Admin") {
            return response()->json(['error' => 'Akun Ini Tidak Dapat Dimodivikasi']);
        } else {
            User::where('id', $request->id_user)->update(['roles' => $request->role]);
            return response()->json(['success' => 'Role Berhasil Diubah']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->name == 'Admin') {
            return response()->json(['error' => 'Akun Ini Tidak Dapat Dihapus']);
        } else {
            User::where('id', $user->id)->delete();
            return response()->json(['success' => 'User Berhasil Dihapus']);
        }
    }

    public function dataTablesUser(Request $request)
    {
        if ($request->ajax()) {
            $query = User::all();
            foreach ($query as $row) {
                $row->roles_edit = $row->id . '-' . $row->roles;
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '<button class="btn btn-warning btn-sm ubah-password-button" title="Ubah Password" data-unique="' . $row->id . '"><i class="bi-pencil-square"></i></button>
                    <button class="btn btn-danger text-white btn-sm warning-button hapus-button" title="Hapus User" data-unique="' . $row->id . '" data-token="' . csrf_token() . '"><i class="bi-x-circle"></i></button>
                    ';
                return $actionBtn;
            })->make(true);
        }
    }
}
