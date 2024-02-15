<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buy;
use App\Models\Bike;
use App\Models\Sele;
use App\Models\Buyer;
use App\Models\Retur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Penjualan | SIFATOR',
            'judul' => 'Transaksi',
            'breadcumb1' => 'Transaksi',
            'breadcumb2' => 'Penjualan',
            'no_polisi' => DB::table('bikes')->select('no_polisi', 'id')->where('status', 'READY STOCK')->get()
        ];
        return view('penjualan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Transaksi Penjualan | SIFATOR',
            'judul' => 'Tambah Transaksi Penjualan',
            'breadcumb1' => 'Pembelian',
            'breadcumb2' => 'Tambah Transaksi Penjualan',

        ];
        return view('penjualan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function tambah_data(Request $request)
    {

        $rules = [
            'no_polisi' => 'required',
            'harga_jual' => 'required',
            'tanggal_jual' => 'required',
            'nama_pembeli' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
        ];
        $pesan = [
            'no_polisi.required' => 'Pilih Nomor Polisi',
            'harga_jual.required' => 'Tidak boleh kosong',
            'tanggal_jual.required' => 'Tidak boleh kosong',
            'nama_pembeli.required' => 'Tidak boleh kosong',
            'nik.required' => 'Tidak boleh kosong',
            'alamat.required' => 'Tidak boleh kosong',
            'no_telepon.required' => 'Tidak boleh kosong',
        ];
        //Mengubah base64 menjadi file image

        if ($request->photo_ktp) {
            $jenis_file = explode(":", $request->photo_ktp);
            $jenis_file2 = explode("/", $jenis_file[1]);
            $jenis_foto = $jenis_file2[0];
        }
        // Validasi Photo KTP
        $validator_photo_ktp = Validator::make([
            'photo_ktp' => base64_encode($request->photo_ktp),
        ], [
            'photo_ktp' => 'max:' . (2 * 1024 * 1024) // Batasan ukuran 2 megabyte
        ], [
            'photo_ktp.max' => 'Ukuran tidak boleh lebih dari 2MB.',
        ]);
        //Validasi base64 apakah sebuah gambar
        //Validasi Inputan yang Lain
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            $send_error = [
                'errors' => $validator->errors(),
            ];
            if ($validator_photo_ktp->fails()) {
                $send_error['error_ktp'] = $validator_photo_ktp->errors();
            }
            if ($request->photo_ktp && $jenis_foto != 'image') {
                $send_error['error_ktp_type'] = 'File harus berupa gambar';
            }
            return response()->json($send_error);
        } else if ($validator_photo_ktp->fails()) {
            $send_error = [
                'error_ktp' => $validator_photo_ktp->errors(),
            ];
            return response()->json($send_error);
        } else if ($request->photo_ktp && $jenis_foto != 'image') {
            return response()->json(['error_ktp_type' => 'File harus berupa gambar',]);
        } else {
            //Cek apakah nik yang dikirim terdaftar di table buyers
            $buyer = Buyer::where('nik', $request->nik)->first();
            //Jika nik terdaftar di table
            if (!$buyer) {
                //Jika nik tidak ada di table
                $data_buyer = [
                    'unique' => Str::orderedUuid(),
                    'nik' => $request->nik,
                    'nama' => ucwords(strtolower($request->nama_pembeli)),
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                ];
                //Upload jika ada gambar
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
                //Ambil id buyer yang baru saja dimasukan ke table
            }
            //Membuat random nota
            $trx = 'TRXSALE-00';
            $last_trx = Sele::latest()->first();;
            if ($last_trx == NULL) {
                $random_num = 1;
            } else {
                $last_nota = explode('-', $last_trx->nota);
                $random_num = $last_nota[1] + 1;
            }
            $nota = $trx . $random_num;
            $data_sele = [
                'unique' => Str::orderedUuid(),
                'nota' => $nota,
                'bike_id' => $request->no_polisi,
                'tanggal_jual' => $request->tanggal_jual,
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli),
                'harga_jual' => preg_replace('/[,]/', '', $request->harga_jual),
                // 'jumlah_bayar' => preg_replace('/[,]/', '', $request->jumlah_bayar),
            ];
            if ($buyer) {
                $data_sele['buyer_id'] = $buyer->id;
            } else if (!$buyer) {
                $last_input = Buyer::latest()->first();
                $data_sele['buyer_id'] = $last_input->id;
            }
            Sele::create($data_sele);
            Bike::where('id', $request->no_polisi)->update(['status' => 'TERJUAL']);
            return response()->json(['success' => 'Data Penjualan Berhasil Ditambahkan']);
        }
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

    public function get_data(Request $request)
    {
        $data = DB::table('seles')
            ->join('bikes', 'bikes.id', '=', 'seles.bike_id')
            ->join('buyers', 'buyers.id', '=', 'seles.buyer_id')
            ->where('seles.id', $request->id)
            ->first();
        return response()->json(['data' => $data]);
    }

    public function get_data_detail(Request $request)
    {
        $data = DB::table('seles')
            ->join('bikes', 'bikes.id', '=', 'seles.bike_id')
            ->join('buyers', 'buyers.id', '=', 'seles.buyer_id')
            ->where('seles.unique', $request->unique)
            ->first();
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function update_data(Request $request)
    {
        $rules = [
            'nik' => 'required',
            'nama_pembeli' => 'required',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'harga_jual' => 'required',
            'tanggal_jual' => 'required',
        ];
        $pesan = [
            'nik.required' => 'Tidak boleh kosong',
            'nama_pembeli.required' => 'Tidak boleh kosong',
            'alamat.required' => 'Tidak boleh kosong',
            'no_telepon.required' => 'Tidak boleh kosong',
            'harga_jual.required' => 'Tidak boleh kosong',
            'tanggal_jual.required' => 'Tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        // Validasi Photo KTP
        $validator_photo_ktp = Validator::make([
            'photo_ktp' => base64_encode($request->photo_ktp),
        ], [
            'photo_ktp' => 'max:' . (2 * 1024 * 1024) // Batasan ukuran 2 megabyte
        ], [
            'photo_ktp.max' => 'Ukuran tidak boleh lebih dari 2MB.',
        ]);
        //Validasi jenis file upload
        if ($request->photo_ktp) {
            $jenis_file = explode(":", $request->photo_ktp);
            $jenis_file2 = explode("/", $jenis_file[1]);
            $jenis_foto = $jenis_file2[0];
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else if ($validator_photo_ktp->fails()) {
            $send_error = [
                'error_ktp' => $validator_photo_ktp->errors(),
            ];
            return response()->json($send_error);
        } else if ($request->photo_ktp && $jenis_foto != 'image') {
            return response()->json(['error_ktp_type' => 'File harus berupa gambar',]);
        } else {
            //Cek apakah user mengganti nik dengan user lain yang terdaftar
            $cek_sele = Sele::where('id', $request->current_id)->first();
            $cek_buyer = Buyer::where('id', $cek_sele->buyer_id)->first();
            $cek_buyer_nik = Buyer::where('nik', "=", $request->nik)->where('nik', "!=", $cek_buyer->nik)->first();

            $data_penjualan = [
                'harga_jual' => preg_replace('/[,]/', '', $request->harga_jual),
                'tanggal_jual' => $request->tanggal_jual,
            ];
            if ($cek_buyer_nik) {
                $data_penjualan['buyer_id'] = $cek_buyer_nik->id;
            }
            $data_penjual = [
                'nik' => $request->nik,
                'nama' => $request->nama_pembeli,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ];
            //Jika ada  upload foto
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
                    Storage::delete($request->old_ktp);
                }
                $data_penjual['photo_ktp'] = $name_Image;
            }
            $buyer_id = Sele::where('id', $request->current_id)->first();
            if ($cek_buyer_nik) {
                Buyer::where('id', $cek_buyer_nik->buyer_id)->update($data_penjual);
            } else {
                Buyer::where('id', $buyer_id->buyer_id)->update($data_penjual);
            }
            Sele::where('id', $request->current_id)->update($data_penjualan);
            return response()->json(['success' => 'Data Penjualan Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($unique)
    {
        $query = Sele::where('unique', $unique)->first();
        $motor = Bike::where('id', $query->bike_id)->first();

        Sele::where('unique', $unique)->delete();
        Bike::where('unique', $motor->unique)->update(['status' => 'READY STOCK']);

        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    //Retur
    public function retur_motor(Request $request, Sele $sele)
    {
        $sele = Sele::where('unique', $sele->unique)->first();
        //Membuat random no retur
        $trx = 'RETUR-SELE-00';
        $last_trx = Retur::latest()->first();;
        if ($last_trx == NULL) {
            $random_num = 1;
        } else {
            $last_no_retur = explode('-', $last_trx->no_retur);
            $random_num = $last_no_retur[2] + 1;
        }
        $data = [
            'unique' => Str::orderedUuid(),
            'no_retur' => $trx . $random_num,
            'nota' => $sele->nota,
            'buyer_id' => $sele->buyer_id,
            'bike_id' => $sele->bike_id,
            'tanggal_jual' => $sele->tanggal_jual,
            'tanggal_retur' => Carbon::now(),
            'harga_beli' => $sele->harga_beli,
            'harga_jual' => $sele->harga_jual,
        ];
        Retur::create($data);
        Bike::where('id', $sele->bike_id)->update(['status' => 'READY STOCK']);
        Sele::where('unique', $sele->unique)->delete();

        return redirect('/penjualan')->with('success', 'Data Penjualan teleh Diretur');
    }

    public function cek_nik(Request $request)
    {
        $query = Buyer::where('nik', $request->nik)->first();
        return response()->json(['success' => $query]);
    }

    public function dataTables()
    {
        $query = DB::table('seles as a')
            ->join('bikes as b', 'b.id', '=', 'a.bike_id')
            ->join('buyers as c', 'c.id', '=', 'a.buyer_id')
            ->select('a.*', 'b.no_polisi', 'b.merek', 'b.warna', 'b.status', 'c.nama')
            ->get();
        foreach ($query as $row) {
            $row->tanggal_jual = tanggal_hari($row->tanggal_jual);
            $row->harga_jual = rupiah($row->harga_jual);
        }
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '<button class="btn btn-info btn-sm info-button-cash" title="Detail Penjualan Cash" data-unique="' . $row->unique . '"><i class="bi-info-circle"></i></button>
                <button class="btn btn-success btn-sm edit-button" title="Edit Data Penjualan Cash" data-id="' . $row->id . '"><i class="text-white bi-pencil"></i></button>
                <button type="button" class="btn btn-warning btn-sm retur-button" title="Retur Penjualan Cash"  data-id="' . $row->unique . '"><i class="text-white bi-arrow-repeat"></i></button>
                <form action="javascript:;" class="d-inline form-delete-penjualan-cash">
                    <button type="button" class="btn btn-danger btn-sm delete-button-penjualan-cash" title="Hapus Penjualan Cash" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="text-white bi-trash"></i>
                </form>';
            return $actionBtn;
        })->make(true);
    }

    // public function rules_penjualan(Request $request)
    // {
    //     if ($request->jenis_pembayaran == '') {
    //         $rules = [
    //             'no_polisi' => 'required',
    //             'jenis_pembayaran' => 'required',
    //             'tanggal_jual' => 'required',
    //             'nama_pembeli' => 'required',
    //             'nik' => 'required',
    //             'alamat' => 'required',
    //         ];
    //         $pesan = [
    //             'no_polisi.required' => 'Pilih Nomor Polisi',
    //             'jenis_pembayaran.required' => 'Pilih Jenis Pembayaran',
    //             'tanggal_jual.required' => 'Tidak boleh kosong',
    //             'nama_pembeli.required' => 'Tidak boleh kosong',
    //             'nik.required' => 'Tidak boleh kosong',
    //             'alamat.required' => 'Tidak boleh kosong',
    //         ];

    //         if ($request->photo_ktp) {
    //             $jenis_file = explode(":", $request->photo_ktp);
    //             $jenis_file2 = explode("/", $jenis_file[1]);
    //             $jenis_foto = $jenis_file2[0];
    //         }
    //         // Validasi Photo KTP
    //         $validator_photo_ktp = Validator::make([
    //             'photo_ktp' => base64_encode($request->photo_ktp),
    //         ], [
    //             'photo_ktp' => 'max:' . (2 * 1024 * 1024) // Batasan ukuran 2 megabyte
    //         ], [
    //             'photo_ktp.max' => 'Ukuran tidak boleh lebih dari 2MB.',
    //         ]);
    //         //Validasi base64 apakah sebuah gambar
    //         //Validasi Inputan yang Lain
    //         $validator = Validator::make($request->all(), $rules, $pesan);
    //         if ($validator->fails()) {
    //             $send_error = [
    //                 'errors' => $validator->errors(),
    //             ];
    //             if ($validator_photo_ktp->fails()) {
    //                 $send_error['error_ktp'] = $validator_photo_ktp->errors();
    //             }
    //             if ($request->photo_ktp && $jenis_foto != 'image') {
    //                 $send_error['error_ktp_type'] = 'File harus berupa gambar';
    //             }
    //             return response()->json($send_error);
    //         }
    //     }
    // }
    public function refresh_no_polisi()
    {
        $query = Bike::where('status', 'READY STOCK')->get();
        echo '
        <option label="&nbsp;"></option>    
        ';
        foreach ($query as $row) {
            echo '<option value="' . $row->id . '">' . $row->no_polisi . '</option>';
        }
    }
}
