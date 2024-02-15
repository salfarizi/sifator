<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Buyer;
use App\Models\Retur;
use App\Models\Kredit;
use App\Models\Regorder;
use App\Models\List_regorder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Penjualan Kredit | SIFATOR',
            'judul' => 'Transaksi',
            'breadcumb1' => 'Penjualan',
            'breadcumb2' => 'Penjualan Kredit',
            'no_polisi' => DB::table('bikes')->select('no_polisi', 'id')->where('status', 'READY STOCK')->get(),
            'orders' => Regorder::dataTables(),
        ];
        return view('kredit.index', $data);
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
            'no_polisi' => 'required',
            'tanggal_jual' => 'required',
            'nama_pembeli' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'dp_bayar' => 'required',
            'pencairan' => 'required',
            'angsuran' => 'required',
            'tenor' => 'required',
            'komisi' => 'required',
            'otr_leasing' => 'required',
            'dp_po' => 'required',
            'harga_jual_kredit' => 'required',
            'no_telepon' => 'required',

        ];
        $pesan = [
            'no_polisi.required' => 'Pilih Nomor Polisi',
            'tanggal_jual.required' => 'Tangal jual tidak boleh kosong',
            'nama_pembeli.required' => 'Nama tidak boleh kosong',
            'nik.required' => 'NIK tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'tempat_lahir.required' => 'Tempat lahir tidak boleh kosong',
            'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong',
            'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
            'dp_bayar.required' => 'DP tidak boleh kosong',
            'pencairan.required' => 'Nilai Pencairan jual tidak boleh kosong',
            'angsuran.required' => 'Angsuran tidak boleh kosong',
            'tenor.required' => 'Tenor tidak boleh kosong',
            'komisi.required' => 'Komisi tidak boleh kosong',
            'otr_leasing.required' => 'OTR tidak boleh kosong',
            'dp_po.required' => 'DP PO tidak boleh kosong',
            'harga_jual_kredit.required' => 'Harga jual tidak boleh kosong',
            'no_telepon.required' => 'DP PO tidak boleh kosong',
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
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
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
            } else {
                if (!$buyer->tanggal_lahir && !$buyer->tempat_lahir && !$buyer->jenis_kelamin) {
                    Buyer::where('unique', $buyer->unique)->update([
                        'tempat_lahir' => $request->tempat_lahir,
                        'tanggal_lahir' => $request->tanggal_lahir,
                        'jenis_kelamin' => $request->jenis_kelamin,
                    ]);
                } else if (!$buyer->tanggal_lahir) {
                    Buyer::where('unique', $buyer->unique)->update([
                        'tanggal_lahir' => $request->tanggal_lahir,
                    ]);
                } else if (!$buyer->tempat_lahir) {
                    Buyer::where('unique', $buyer->unique)->update([
                        'tempat' => $request->tempat,
                    ]);
                } else if (!$buyer->jenis_kelamin) {
                    Buyer::where('unique', $buyer->unique)->update([
                        'jenis_kelamin' => $request->jenis_kelamin,
                    ]);
                }
            }
            //Membuat random nota
            $trx = 'TRX-KRDT-00';
            $last_trx = Kredit::latest()->first();
            if ($last_trx == NULL) {
                $random_num = 1;
            } else {
                $last_nota = explode('-', $last_trx->nota);
                $random_num = $last_nota[2] + 1;
            }
            $nota = $trx . $random_num;
            $data_kredit = [
                'unique' => Str::orderedUuid(),
                'nota' => $nota,
                'bike_id' => $request->no_polisi,
                'tanggal_jual' => $request->tanggal_jual,
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli),
                'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                'otr_leasing' => preg_replace('/[,]/', '', $request->otr_leasing),
                'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                'tenor' => $request->tenor,
                'harga_jual_kredit' => preg_replace('/[,]/', '', $request->harga_jual_kredit),
                'komisi' => preg_replace('/[,]/', '', $request->komisi),
            ];

            if ($buyer) {
                $data_kredit['buyer_id'] = $buyer->id;
                //DATA REGISTER ORDER
                //cek apakah data pembeli sudah terdaftar di register order
                $cek_reg = Regorder::where('buyer_id', $buyer->id)->first();
                //BUAT RANDOM NOTA
                $trx = 'REG-ORDER-00';
                $cek_last = Regorder::latest()->first();
                if ($cek_last == NULL) {
                    $random_num = 1;
                } else {
                    $last_nota = explode('-', $cek_last->no_reg);
                    $random_num = $last_nota[2] + 1;
                }
                $nota_reg = $trx . $random_num;
                //Ambil Data Motor
                $motor2 = Bike::where('id', $request->no_polisi)->first();
                if (!$cek_reg) {
                    $data_reg = [
                        'unique' => Str::orderedUuid(),
                        'no_reg' => $nota_reg,
                        'buyer_id' => $buyer->id
                    ];
                    Regorder::create($data_reg);
                    $latest_reg = Regorder::latest()->first();
                    $data_list = [
                        'unique' => Str::orderedUuid(),
                        'regorder_id' => $latest_reg->unique,
                        'nama_dealer' => '',
                        'cmo' => '',
                        'pic' => '',
                        'jenis_transaksi' => 'KREDIT',
                        'via' => '',
                        'merk' => $motor2->merek,
                        'type' => $motor2->type,
                        'tahun_pembuatan' => $motor2->tahun_pembuatan,
                        'otr' => preg_replace('/[,]/', '', $request->otr_leasing),
                        'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                        'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                        'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                        'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                        'tenor' => $request->tenor,
                        'status' => 'DI SETUJUI',
                    ];
                    List_regorder::create($data_list);
                } else {
                    $data_list = [
                        'unique' => Str::orderedUuid(),
                        'regorder_id' => $cek_reg->unique,
                        'nama_dealer' => '',
                        'cmo' => '',
                        'pic' => '',
                        'jenis_transaksi' => 'KREDIT',
                        'via' => '',
                        'merk' => $motor2->merek,
                        'type' => $motor2->type,
                        'tahun_pembuatan' => $motor2->tahun_pembuatan,
                        'otr' => preg_replace('/[,]/', '', $request->otr_leasing),
                        'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                        'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                        'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                        'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                        'tenor' => $request->tenor,
                        'status' => 'DI SETUJUI',
                    ];
                    List_regorder::create($data_list);
                }
            } else if (!$buyer) {
                $last_input = Buyer::latest()->first();
                $data_kredit['buyer_id'] = $last_input->id;

                $cek_reg = Regorder::where('buyer_id', $last_input->id)->first();
                //BUAT RANDOM NOTA
                $trx = 'REG-ORDER-00';
                $cek_last = Regorder::latest()->first();
                if ($cek_last == NULL) {
                    $random_num = 1;
                } else {
                    $last_nota = explode('-', $cek_last->no_reg);
                    $random_num = $last_nota[2] + 1;
                }
                $nota_reg = $trx . $random_num;
                //Ambil Data Motor
                $motor2 = Bike::where('id', $request->no_polisi)->first();
                if (!$cek_reg) {
                    $data_reg = [
                        'unique' => Str::orderedUuid(),
                        'no_reg' => $nota_reg,
                        'buyer_id' => $last_input->id
                    ];
                    Regorder::create($data_reg);
                    $latest_reg = Regorder::latest()->first();
                    $data_list = [
                        'unique' => Str::orderedUuid(),
                        'regorder_id' => $latest_reg->unique,
                        'nama_dealer' => '',
                        'cmo' => '',
                        'pic' => '',
                        'jenis_transaksi' => 'KREDIT',
                        'via' => '',
                        'merk' => $motor2->merek,
                        'type' => $motor2->type,
                        'tahun_pembuatan' => $motor2->tahun_pembuatan,
                        'otr' => preg_replace('/[,]/', '', $request->otr_leasing),
                        'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                        'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                        'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                        'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                        'tenor' => $request->tenor,
                        'status' => 'DI SETUJUI',
                    ];
                    List_regorder::create($data_list);
                } else {
                    $data_list = [
                        'unique' => Str::orderedUuid(),
                        'regorder_id' => $cek_reg->unique,
                        'nama_dealer' => '',
                        'cmo' => '',
                        'pic' => '',
                        'jenis_transaksi' => 'KREDIT',
                        'via' => '',
                        'merk' => $motor2->merek,
                        'type' => $motor2->type,
                        'tahun_pembuatan' => $motor2->tahun_pembuatan,
                        'otr' => preg_replace('/[,]/', '', $request->otr_leasing),
                        'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                        'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                        'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                        'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                        'tenor' => $request->tenor,
                        'status' => 'DI SETUJUI',
                    ];
                    List_regorder::create($data_list);
                }
            }

            Kredit::create($data_kredit);
            Bike::where('id', $request->no_polisi)->update(['status' => 'TERJUAL']);
            return response()->json(['success' => 'Data Penjualan Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kredit $kredit)
    {
        //
    }

    public function get_data(Request $request)
    {
        $data = Kredit::get_data($request->unique);
        $data->unique = $request->unique;
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kredit $kredit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kredit $kredit)
    {
        $rules = [
            'tanggal_jual' => 'required',
            'nama_pembeli' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'dp_bayar' => 'required',
            'pencairan' => 'required',
            'angsuran' => 'required',
            'tenor' => 'required',
            'komisi' => 'required',
            'otr_leasing' => 'required',
            'dp_po' => 'required',
            'harga_jual_kredit' => 'required',
            'no_telepon' => 'required',

        ];
        $pesan = [
            'tanggal_jual.required' => 'Tidak boleh kosong',
            'nama_pembeli.required' => 'Tidak boleh kosong',
            'nik.required' => 'Tidak boleh kosong',
            'alamat.required' => 'Tidak boleh kosong',
            'tempat_lahir.required' => 'Tidak boleh kosong',
            'tanggal_lahir.required' => 'Tidak boleh kosong',
            'jenis_kelamin.required' => 'Tidak boleh kosong',
            'dp_bayar.required' => 'Tidak boleh kosong',
            'pencairan.required' => 'Tidak boleh kosong',
            'angsuran.required' => 'Tidak boleh kosong',
            'tenor.required' => 'Tidak boleh kosong',
            'komisi.required' => 'Tidak boleh kosong',
            'otr_leasing.required' => 'OTR tidak boleh kosong',
            'dp_po.required' => 'DP PO tidak boleh kosong',
            'harga_jual_kredit.required' => 'Harga jual kredit tidak boleh kosong',
            'no_telepon.required' => 'DP PO tidak boleh kosong',
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
            $cek_sele = Kredit::where('unique', $request->current_unique)->first();
            $cek_buyer = Buyer::where('id', $cek_sele->buyer_id)->first();
            $cek_buyer_nik = Buyer::where('nik', "=", $request->nik)->where('nik', "!=", $cek_buyer->nik)->first();

            $data_penjualan = [
                'tanggal_jual' => $request->tanggal_jual,
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli),
                'dp' => preg_replace('/[,]/', '', $request->dp_bayar),
                'otr_leasing' => preg_replace('/[,]/', '', $request->otr_leasing),
                'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                'tenor' => $request->tenor,
                'harga_jual_kredit' => preg_replace('/[,]/', '', $request->harga_jual_kredit),
                'komisi' => preg_replace('/[,]/', '', $request->komisi),
            ];
            if ($cek_buyer_nik) {
                $data_penjualan['buyer_id'] = $cek_buyer_nik->id;
            }
            $data_penjual = [
                'nik' => $request->nik,
                'nama' => ucwords(strtolower($request->nama_pembeli)),
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
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
            $buyer_id = Kredit::where('unique',  $request->current_unique)->first();
            if ($cek_buyer_nik) {
                Buyer::where('id', $cek_buyer_nik->buyer_id)->update($data_penjual);
            } else {
                Buyer::where('id', $buyer_id->buyer_id)->update($data_penjual);
            }
            Kredit::where('unique', $request->current_unique)->update($data_penjualan);
            return response()->json(['success' => 'Data Penjualan Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($unique)
    {
        $query = Kredit::where('unique', $unique)->first();
        $motor = Bike::where('id', $query->bike_id)->first();

        Kredit::where('unique', $unique)->delete();
        Bike::where('unique', $motor->unique)->update(['status' => 'READY STOCK']);

        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }
    public function dataTables()
    {
        $query = DB::table('kredits as a')
            ->join('bikes as b', 'b.id', '=', 'a.bike_id')
            ->join('buyers as c', 'c.id', '=', 'a.buyer_id')
            ->select('a.*', 'b.no_polisi', 'b.merek', 'b.warna', 'b.status', 'c.nama')
            ->get();
        foreach ($query as $row) {
            $row->tanggal_jual = tanggal_hari($row->tanggal_jual);
            $row->harga_jual_kredit = rupiah($row->harga_jual_kredit);
        }
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '<button class="btn btn-info btn-sm info-button-kredit" title="Detail Penjualan Kredit" data-unique="' . $row->unique . '"><i class="bi-info-circle"></i></button>
                <button class="btn btn-success btn-sm edit-button-kredit" title="Edit Penjualan Kredit" data-unique="' . $row->unique . '"><i class="bi-pencil"></i></button>
                <button type="button" class="btn btn-warning btn-sm retur-button" title="Retur Penjualan Kredit"  data-unique="' . $row->unique . '"><i class="bi-arrow-repeat"></i></button>
                <form action="javascript:;" class="d-inline form-delete-kredit">
                    <button type="button" class="btn btn-danger btn-sm delete-button-kredit" title="Hapus Penjualan Kredit" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="bi-trash"></i></button>
                </form>';
            return $actionBtn;
        })->make(true);
    }

    public function retur_motor(Request $request, Kredit $kredit)
    {
        $kredit = Kredit::where('unique', $kredit->unique)->first();
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
            'nota' => $kredit->nota,
            'buyer_id' => $kredit->buyer_id,
            'bike_id' => $kredit->bike_id,
            'tanggal_jual' => $kredit->tanggal_jual,
            'tanggal_retur' => Carbon::now(),
            'harga_beli' => $kredit->harga_beli,
        ];
        Retur::create($data);
        Bike::where('id', $kredit->bike_id)->update(['status' => 'READY STOCK']);
        Kredit::where('unique', $kredit->unique)->delete();

        return redirect('/kredit')->with('success', 'Data Penjualan teleh Diretur');
    }

    public function get_list_order(Request $request)
    {
        $query = List_regorder::where('regorder_id', $request->unique)->where('status', 'DI SETUJUI')->get();
        echo '<option label="&nbsp;"></option>';
        foreach ($query as $row) {
            echo '<option value="' . $row->unique . '">' . $row->merk . '&nbsp; - &nbsp;' . $row->type . '</option>';
        }
    }

    public function get_list_order_kredit(Request $request)
    {
        $query = $query = DB::table('list_regorders as a')
            ->join('regorders as b', 'a.regorder_id', '=', 'b.unique')
            ->join('buyers as c', 'b.buyer_id', '=', 'c.id')
            ->where('a.unique', $request->unique);
        return response()->json(['success' => $query->first()]);
    }
}
