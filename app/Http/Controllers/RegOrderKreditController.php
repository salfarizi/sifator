<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\List_regorder;
use App\Models\Regorder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RegOrderKreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Register Order Kredit | SIFATOR',
            'judul' => 'Register Order Kredit',
            'breadcumb1' => 'Reg Order Kredit',
            'breadcumb2' => 'Reg Order Kredit',
        ];
        return view('regorderkredit.index', $data);
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
            'nama_pembeli' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'no_telepon' => 'required',
        ];
        $pesan = [
            'nama_pembeli.required' => 'NIK tidak boleh kosong',
            'nik.required' => 'NIK tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'tempat_lahir.required' => 'Tempat lahir tidak boleh kosong',
            'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
            'no_telepon.required' => 'Jenis kelamin tidak boleh kosong',
        ];
        $validation = Validator::make($request->all(), $rules, $pesan);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        } else {
            //BUAT RANDOM NOTA
            $trx = 'REG-ORDER-00';
            $cek_last = Regorder::latest()->first();
            if ($cek_last == NULL) {
                $random_num = 1;
            } else {
                $last_nota = explode('-', $cek_last->no_reg);
                $random_num = $last_nota[2] + 1;
            }
            $nota = $trx . $random_num;

            // CEK APAKAH NIK TERDAFTAR
            $cek_nik = Buyer::where('nik', $request->nik)->first();
            if (!$cek_nik) {
                $data_buyer = [
                    'unique' => Str::orderedUuid(),
                    'nik' => $request->nik,
                    'nama' => ucwords(strtolower($request->nama_pembeli)),
                    'tempat_lahir' => ucwords(strtolower($request->tempat_lahir)),
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                ];

                if ($request->photo_ktp) {
                    $base_Image = $request->photo_ktp;  // your base64 encoded

                    $jenis_file = explode(":", $request->photo_ktp);
                    $jenis_file2 = explode("/", $jenis_file[1]);
                    $jenis_file3 = explode(";", $jenis_file2[1]);
                    $jenis_foto = $jenis_file3[0];
                    if ($jenis_foto == 'jpeg') {
                        $base_Image = str_replace('data:image/jpeg;base64,', '', $base_Image);
                    } else if ($jenis_foto == 'jpg') {
                        $base_Image = str_replace('data:image/jpg;base64,', '', $base_Image);
                    } else if ($jenis_foto == 'png') {
                        $base_Image = str_replace('data:image/png;base64,', '', $base_Image);
                    }
                    $base_Image = str_replace(' ', '+', $base_Image);
                    $name_Image = Str::random(40) . '.' . 'png';
                    File::put(storage_path() . '/app/public/ktp_pembeli/' . $name_Image, base64_decode($base_Image));
                    $data_buyer['photo_ktp'] = $name_Image;
                }
                Buyer::create($data_buyer);
            } else {
                if ($request->photo_ktp) {
                    $base_Image = $request->photo_ktp;  // your base64 encoded

                    $jenis_file = explode(":", $request->photo_ktp);
                    $jenis_file2 = explode("/", $jenis_file[1]);
                    $jenis_file3 = explode(";", $jenis_file2[1]);
                    $jenis_foto = $jenis_file3[0];
                    if ($jenis_foto == 'jpeg') {
                        $base_Image = str_replace('data:image/jpeg;base64,', '', $base_Image);
                    } else if ($jenis_foto == 'jpg') {
                        $base_Image = str_replace('data:image/jpg;base64,', '', $base_Image);
                    } else if ($jenis_foto == 'png') {
                        $base_Image = str_replace('data:image/png;base64,', '', $base_Image);
                    }
                    $base_Image = str_replace(' ', '+', $base_Image);
                    $name_Image = Str::random(40) . '.' . 'png';
                    File::put(storage_path() . '/app/public/ktp_pembeli/' . $name_Image, base64_decode($base_Image));
                    if ($request->old_ktp) {
                        Storage::delete("ktp_pembeli/" . $request->old_ktp);
                    }
                    $data_buyer['photo_ktp'] = $name_Image;
                    Buyer::where('nik', $cek_nik->nik)->update($data_buyer);
                }
            }
            $data_register = [
                'unique' => Str::orderedUuid(),
                'no_reg' => $nota,
            ];
            if ($cek_nik) {
                $data_register['buyer_id'] = $cek_nik->id;
            } else {
                $latest_buyer = Buyer::latest()->first();
                $data_register['buyer_id'] = $latest_buyer->id;
            }
            Regorder::create($data_register);
            return response()->json(['success' => 'Data Register Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Regorder $regorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regorder $regorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Regorder $regorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regorder $regorder, $unique)
    {
        Regorder::where('unique', $unique)->delete();
        List_regorder::where('regorder_id', $unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Regorder::dataTables();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '<button class="btn btn-secondary btn-sm register-button" title="Reg Order Kredit" data-unique="' . $row->unique . '"><i class="bi-folder-plus"></i></button>
                    <form action="javascript:;" class="d-inline form-delete">
                        <button type="button" class="btn btn-danger btn-sm delete-button-regorder" title="Hapus Reg Order Kredit" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="text-white bi-trash"></i>
                    </form>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function get_data_buyer(Request $request)
    {
        $query = Regorder::getBuyer($request->unique);
        return response()->json(['data' => $query]);
    }
}
