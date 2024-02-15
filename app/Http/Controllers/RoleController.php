<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Access;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Role | SIFATOR',
            'judul' => 'Buat Role',
            'breadcumb1' => 'Roles',
            'breadcumb2' => 'Role',
            'role' => Role::all(),
            'menus' => Menu::all(),
        ];
        return view('roles.index', $data);
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
        $rules = [
            'name' => 'required',
        ];
        $pesan = [
            'name.required' => 'Nama role tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'name' => strtoupper($request->name),
            ];
            Role::create($data);
            return response()->json(['success' => 'Roles Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Role::where('unique', $role->unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    public function dataTables()
    {
        $query = Role::where('name', '!=', 'SUPER ADMIN')->get();
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '<button class="btn btn-secondary btn-sm access-button" data-unique="' . $row->unique . '"><i class="bi-list-check"></i></button>
                
                <form action="javascript:;" class="d-inline form-delete-roles">
                    <button type="button" class="btn btn-danger btn-sm delete-button-roles" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="text-white bi-trash"></i>
                </form>';
            return $actionBtn;
            // <button class="btn btn-warning btn-sm edit-button" data-unique="' . $row->unique . '"><i class="bi-pencil-square"></i></button>
        })->make(true);
    }

    public function list_access(Request $request)
    {
        $query = Access::where('role_unique', $request->unique)->get();
        foreach ($query as $row) {
            echo '<button type="button" class="btn btn-primary">' . $row->menu_name . '</button>';
        }
    }
    public function tambah_access(Request $request)
    {
        $cek = Access::where('role_unique', $request->unique)
            ->where('menu_name', $request->menu)->first();
        if (!$cek) {
            $data = [
                'unique' => Str::orderedUuid(),
                'role_unique' => $request->unique,
                'menu_name' => $request->menu
            ];
            Access::create($data);
            return response()->json(['success' => 'Data Berhasil Ditambahkan']);
        }
        return response()->json(['success' => 'Data Berhasil Ditambahkan']);
    }
    public function hapus_access(Request $request)
    {
        $cek = Access::where('role_unique', $request->unique)
            ->where('menu_name', $request->menu)->first();
        if ($cek) {
            $data = [
                'role_unique' => $request->unique,
                'menu_name' => $request->menu
            ];
            Access::where('role_unique', $request->unique)->where('menu_name', $request->menu)->delete($data);
            return response()->json(['success' => 'Data Berhasil Dihapus']);
        }
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }
}
