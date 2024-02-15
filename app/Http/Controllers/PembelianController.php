<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Bike;
use App\Models\Modal;
use App\Models\Consumer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pembelian | SIFATOR',
            'judul' => 'Pembelian',
            'breadcumb1' => 'Transaksi',
            'breadcumb2' => 'Pembelian',
            'modal' => Modal::first(),
        ];
        return view('pembelian.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Transaksi Pembelian | SIFATOR',
            'judul' => 'Tambah Transaksi Pembelian',
            'breadcumb1' => 'Pembelian',
            'breadcumb2' => 'Tambah Transaksi Pembelian',

        ];
        return view('pembelian.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->penjual == "INDIVIDU") {
            // Validasi jika penjual adalah individu
            $rules = [
                'penjual' => 'required',
                'nik' => 'required',
                'nama' => 'required',
                'no_telepon' => 'required',
                'alamat' => 'required',
                'merek' => 'required',
                'tahun_pembuatan' => 'required',
                'warna' => 'required',
                'no_rangka' => 'required|max:17',
                'no_mesin' => 'required|max:12',
                'bpkb' => 'required',
                'nama_bpkb' => 'required',
                'alamat_bpkb' => 'required',
                'type' => 'required',
                'no_polisi' => 'required',
                'berlaku_sampai' => 'required',
                'perpanjang_stnk' => 'required',
                'harga_beli' => 'required',
                'tanggal_beli' => 'required',
                'photo_stnk' => 'image|file|max:3072',
                'photo_bpkb' => 'image|file|max:3072',
                'photo_ktp' => 'image|file|max:3072',
            ];
            $pesan = [
                'penjual.required' => 'Tidak boleh Kosong',
                'nik.required' => 'Tidak boleh Kosong',
                'nama.required' => 'Tidak boleh Kosong',
                'no_telepon.required' => 'Tidak boleh Kosong',
                'alamat.required' => 'Tidak boleh Kosong',
                'merek.required' => 'Tidak boleh Kosong',
                'tahun_pembuatan.required' => 'Tidak boleh Kosong',
                'warna.required' => 'Tidak boleh Kosong',
                'no_rangka.required' => 'Tidak boleh Kosong',
                'no_rangka.max' => 'Nomor rangka maximal 17 Karakter',
                'no_mesin.required' => 'Tidak boleh Kosong',
                'no_mesin.max' => 'Nomor rangka maximal 12 Karakter',
                'bpkb.required' => 'Tidak boleh Kosong',
                'alamat_bpkb.required' => 'Tidak boleh Kosong',
                'nama_bpkb.required' => 'Tidak boleh Kosong',
                'type.required' => 'Tidak boleh Kosong',
                'no_polisi.required' => 'Tidak boleh Kosong',
                'berlaku_sampai.required' => 'Tidak boleh Kosong',
                'perpanjang_stnk.required' => 'Tidak boleh kosong',
                'harga_beli.required' => 'Tidak boleh Kosong',
                'tanggal_beli.required' => 'Tidak boleh Kosong',
                'photo_stnk.image' => 'File Harus Berupa Gambar',
                'photo_bpkb.image' => 'File Harus Berupa Gambar',
                'photo_stnk.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_bpkb.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_ktp.image' => 'File Harus Berupa Gambar',
                'photo_ktp.max' => 'Gambar Minimal Berukuran 3MB',

            ];
        } else if ($request->penjual == "DEALER") {
            // Validasi apabila penjual adalah dealer
            $rules = [
                'penjual' => 'required',
                'dealer' => 'required',
                'nama_kang' => 'required',
                'merek' => 'required',
                'tahun_pembuatan' => 'required',
                'warna' => 'required',
                'no_rangka' => 'required|max:17',
                'no_mesin' => 'required|max:12',
                'bpkb' => 'required',
                'alamat_bpkb' => 'required',
                'nama_bpkb' => 'required',
                'type' => 'required',
                'no_polisi' => 'required',
                'berlaku_sampai' => 'required',
                'perpanjang_stnk' => 'required',
                'harga_beli' => 'required',
                'tanggal_beli' => 'required',
                'photo_stnk' => 'image|file|max:3072',
                'photo_bpkb' => 'image|file|max:3072',
            ];
            $pesan = [
                'penjual.required' => 'Tidak boleh Kosong',
                'dealer.required' => 'Tidak boleh Kosong',
                'nama_kang.required' => 'Tidak boleh Kosong',
                'merek.required' => 'Tidak boleh Kosong',
                'tahun_pembuatan.required' => 'Tidak boleh Kosong',
                'warna.required' => 'Tidak boleh Kosong',
                'no_rangka.required' => 'Tidak boleh Kosong',
                'no_mesin.required' => 'Tidak boleh Kosong',
                'no_rangka.max' => 'Nomor rangka maximal 17 Karakter',
                'no_mesin.max' => 'Nomor rangka maximal 12 Karakter',
                'bpkb.required' => 'Tidak boleh Kosong',
                'nama_bpkb.required' => 'Tidak boleh Kosong',
                'alamat_bpkb.required' => 'Tidak boleh Kosong',
                'type.required' => 'Tidak boleh Kosong',
                'no_polisi.required' => 'Tidak boleh Kosong',
                'berlaku_sampai.required' => 'Tidak boleh Kosong',
                'perpanjang_stnk.required' => 'Tidak boleh kosong',
                'harga_beli.required' => 'Tidak boleh Kosong',
                'tanggal_beli.required' => 'Tidak boleh Kosong',
                'photo_stnk.image' => 'File Harus Berupa Gambar',
                'photo_bpkb.image' => 'File Harus Berupa Gambar',
                'photo_stnk.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_bpkb.max' => 'Gambar Minimal Berukuran 3MB',
            ];
        } else if ($request->penjual == "") {
            //Validasi jika input penjual kosong
            $rules = [
                'penjual' => 'required',
            ];
            $pesan = [
                'penjual.required' => 'Tidak boleh Kosong',
            ];
        }
        //Inisialisasi Validator
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            // Mengirim pesan validasi apabila ada inputan yang tidak tervalidasi
            return redirect()
                ->back()
                ->with('error', 'Pastikan anda menginput dengan benar')
                ->withErrors($validator)
                ->withInput();
        } else {
            // Ambil Modal
            $modal = Modal::first();
            //Ambil semua asset
            $asset = Modal::jumlah_asset();
            //Jika Modal tidak mencukupi
            if (($modal->modal - $asset) < preg_replace('/[,]/', '', $request->harga_beli)) {
                return redirect()
                    ->back()
                    ->with('error', 'Modal tidak mencukupi')
                    ->withErrors($validator)
                    ->withInput();
            }
            //JIKA SEMUA SUDAH TERVALIDASI------------------
            //Cek apakah ada data costumer individu yang terdaftar di database
            $consumer = Consumer::where('nama', '=', $request->nama)->where('nik', '=', $request->nik)->first();
            //Cek apakah ada data costumer dealer yang terdaftar di database
            $consumer2 = Consumer::where('nama', '=', $request->nama_kang)->where('dealer', '=', $request->dealer)->first();

            // Jika dealer dan individu tidak ada dalam table maka create new consumer
            if ($consumer == NULL && $consumer2 == NULL) {
                //Jika ternyata penjual adalah individu
                if ($consumer == NULL && $request->dealer == NULL) {
                    if (!$request->photo_ktp) {
                        $data_consumer = [
                            'unique' => Str::orderedUuid(),
                            'penjual' => $request->penjual,
                            'nik' => $request->nik,
                            'nama' => ucwords(strtolower($request->nama)),
                            'no_telepon' => $request->no_telepon,
                            'alamat' => $request->alamat,
                        ];
                    } else if ($request->photo_ktp) {
                        $data_consumer = [
                            'unique' => Str::orderedUuid(),
                            'penjual' => $request->penjual,
                            'nik' => $request->nik,
                            'nama' => ucwords(strtolower($request->nama)),
                            'no_telepon' => $request->no_telepon,
                            'alamat' => $request->alamat,
                            'photo_ktp' => $request->file('photo_ktp')->store('ktp')
                        ];
                    }

                    Consumer::create($data_consumer);
                    //Jika ternyata penjual adalah dealer
                } else if ($consumer2 == NULL && $request->nik == NULL) {
                    $data_consumer = [
                        'unique' => Str::orderedUuid(),
                        'penjual' => $request->penjual,
                        'nama' => ucwords(strtolower($request->nama_kang)),
                        'dealer' => strtoupper($request->dealer),
                    ];
                    Consumer::create($data_consumer);
                }
            }
            // Jika dealer dan individu ternyata ada dalam table
            if ($consumer || $consumer2) {
                //Jika data individu yang ada di table
                if ($consumer) {
                    //ambil current id dari individu
                    $consumer_id = $consumer->id;
                    //Apabila user memasukan photo ktp
                    if ($request->photo_ktp) {
                        //masukan photo ktp ke storage dan ke table jika sebelumnya user tidak memiliki foto ktp
                        Consumer::where('id', $consumer_id)->update(['photo_ktp' => $request->file('photo_ktp')->store('ktp')]);
                        //menghapus foto ktp sebelumnya dari storage dan di ganti dengan yang baru apabila sebelumnya terdapat foto ktp
                        if ($request->oldKTP) {
                            Storage::delete($request->oldKTP);
                        }
                    }
                    //Jika data dealer yang ada di table
                } else if ($consumer2) {
                    //ambil current id dari dealer
                    $consumer_id = $consumer2->id;
                }
                // Jika dealer dan individu ternyata tidak ada dalam table maka ambil id penjual yang baru saja di input ke table
            } else if ($consumer == NULL || $consumer2 == NULL) {
                $last_consumer = Consumer::latest()->first();
                $consumer_id = $last_consumer->id;
            }

            //MEMASUKAN DATA MOTOR KE TABLE BIKES
            //Jika user menginputkan foto stnk dan bpkb
            if ($request->file('photo_stnk') != NULL && $request->file('photo_bpkb') != NULL) {
                $path1 = $request->file('photo_stnk')->store('stnk');
                $path2 = $request->file('photo_bpkb')->store('bpkb');
                $data_motor = [
                    'unique' => Str::orderedUuid(),
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'type' => $request->type,
                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'status' => 'READY STOCK',
                    'photo_stnk' => $path1,
                    'photo_bpkb' => $path2,
                    'consumer_id' => $consumer_id,
                ];
                //Jika user menginputkan foto bpkb dan tidak menginputkan foto bpkb
            } else if ($request->file('photo_stnk') == NULL && $request->file('photo_bpkb') != NULL) {
                $path2 = $request->file('photo_bpkb')->store('bpkb');
                $data_motor = [
                    'unique' => Str::orderedUuid(),
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'type' => $request->type,
                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'status' => 'READY STOCK',
                    'photo_bpkb' => $path2,
                    'consumer_id' => $consumer_id,
                ];
                //Jika user menginputkan foto stnk dan tidak menginputkan foto stnk
            } else if ($request->file('photo_stnk') != NULL && $request->file('photo_bpkb') == NULL) {
                $path2 = $request->file('photo_stnk')->store('stnk');
                $data_motor = [
                    'unique' => Str::orderedUuid(),
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'type' => $request->type,
                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'status' => 'READY STOCK',
                    'photo_stnk' => $path2,
                    'consumer_id' => $consumer_id,
                ];
                //Jika user tidak samasekali menginputkan foto stnk dan foto bpkb
            } else if ($request->file('photo_stnk') == NULL && $request->file('photo_bpkb') == NULL) {
                $data_motor = [
                    'unique' => Str::orderedUuid(),
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'type' => $request->type,
                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'status' => 'READY STOCK',
                    'consumer_id' => $consumer_id,
                ];
            }
            //Input data motor ke table
            Bike::create($data_motor);

            //INPUT DATA PEMBELIAN KE TABLE BUYS
            //Membuat generate nota
            $trx = 'TRXBUY-00';
            $last_trx = Buy::latest()->first();
            if ($last_trx == NULL) {
                $random_num = 1;
            } else {
                $last_nota = explode('-', $last_trx->nota);
                $random_num = $last_nota[1] + 1;
            }
            $nota = $trx . $random_num;
            $last_motor = Bike::latest()->first();

            $data_transaksi = [
                'unique' => Str::orderedUuid(),
                'consumer_id' => $consumer_id,
                'nota' => $nota,
                'bike_id' => $last_motor->id,
                'tanggal_beli' => $request->tanggal_beli,
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli),
            ];

            Buy::create($data_transaksi);

            return redirect('/pembelian')->with('success', 'Data Pembelian Berhasil Ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Buy $buy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buy $buy)
    {
    }

    public function page_edit(Buy $buy)
    {

        $data = [
            'title' => 'Edit Pembelian | SIFATOR',
            'judul' => 'Edit Data Pembelian',
            'breadcumb1' => 'Pembelian',
            'breadcumb2' => 'Edit Data Pembelian',
            'beli' => $buy,
            'motor' => $buy->bike,
            'consumer' => $buy->consumer,

        ];
        return view('pembelian.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $buy)
    {
        if ($request->penjual == "INDIVIDU") {
            $rules = [
                'penjual' => 'required',
                'nik' => 'required',
                'nama' => 'required',
                'no_telepon' => 'required',
                'alamat' => 'required',
                'merek' => 'required',
                'tahun_pembuatan' => 'required',
                'warna' => 'required',
                'no_rangka' => 'required|max:17',
                'no_mesin' => 'required|max:12',
                'bpkb' => 'required',
                'alamat_bpkb' => 'required',
                'nama_bpkb' => 'required',
                'type' => 'required',
                'no_polisi' => 'required',
                'berlaku_sampai' => 'required',
                'perpanjang_stnk' => 'required',
                'harga_beli' => 'required',
                'tanggal_beli' => 'required',
                'photo_stnk' => 'image|file|max:3072',
                'photo_bpkb' => 'image|file|max:3072',
                'photo_ktp' => 'image|file|max:3072',
            ];
            $pesan = [
                'penjual.required' => 'Tidak boleh Kosong',
                'nik.required' => 'Tidak boleh Kosong',
                'nama.required' => 'Tidak boleh Kosong',
                'no_telepon.required' => 'Tidak boleh Kosong',
                'alamat.required' => 'Tidak boleh Kosong',
                'merek.required' => 'Tidak boleh Kosong',
                'tahun_pembuatan.required' => 'Tidak boleh Kosong',
                'warna.required' => 'Tidak boleh Kosong',
                'no_rangka.required' => 'Tidak boleh Kosong',
                'no_rangka.max' => 'Nomor rangka maximal 17 Karakter',
                'no_mesin.max' => 'Nomor rangka maximal 12 Karakter',
                'no_mesin.required' => 'Tidak boleh Kosong',
                'bpkb.required' => 'Tidak boleh Kosong',
                'alamat_bpkb.required' => 'Tidak boleh Kosong',
                'nama_bpkb.required' => 'Tidak boleh Kosong',
                'type.required' => 'Tidak boleh Kosong',
                'no_polisi.required' => 'Tidak boleh Kosong',
                'berlaku_sampai.required' => 'Tidak boleh Kosong',
                'perpanjang_stnk.required' => 'Tidak boleh Kosong',
                'harga_beli.required' => 'Tidak boleh Kosong',
                'tanggal_beli.required' => 'Tidak boleh Kosong',
                'photo_stnk.image' => 'File Harus Berupa Gambar',
                'photo_bpkb.image' => 'File Harus Berupa Gambar',
                'photo_stnk.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_bpkb.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_ktp.image' => 'File Harus Berupa Gambar',
                'photo_ktp.max' => 'Gambar Minimal Berukuran 3MB',
            ];
        } else if ($request->penjual == "DEALER") {
            $rules = [
                'penjual' => 'required',
                'dealer' => 'required',
                'nama_kang' => 'required',
                'merek' => 'required',
                'tahun_pembuatan' => 'required',
                'warna' => 'required',
                'no_rangka' => 'required|max:17',
                'no_mesin' => 'required|max:12',
                'alamat_bpkb' => 'required',
                'bpkb' => 'required',
                'nama_bpkb' => 'required',
                'type' => 'required',
                'no_polisi' => 'required',
                'berlaku_sampai' => 'required',
                'perpanjang_stnk' => 'required',
                'harga_beli' => 'required',
                'tanggal_beli' => 'required',
                'photo_stnk' => 'image|file|max:3072',
                'photo_bpkb' => 'image|file|max:3072',
            ];
            $pesan = [
                'penjual.required' => 'Tidak boleh Kosong',
                'dealer.required' => 'Tidak boleh Kosong',
                'nama_kang.required' => 'Tidak boleh Kosong',
                'merek.required' => 'Tidak boleh Kosong',
                'tahun_pembuatan.required' => 'Tidak boleh Kosong',
                'warna.required' => 'Tidak boleh Kosong',
                'no_rangka.required' => 'Tidak boleh Kosong',
                'no_mesin.required' => 'Tidak boleh Kosong',
                'no_rangka.max' => 'Nomor rangka maximal 17 Karakter',
                'no_mesin.max' => 'Nomor rangka maximal 12 Karakter',
                'bpkb.required' => 'Tidak boleh Kosong',
                'nama_bpkb.required' => 'Tidak boleh Kosong',
                'alamat_bpkb.required' => 'Tidak boleh Kosong',
                'type.required' => 'Tidak boleh Kosong',
                'no_polisi.required' => 'Tidak boleh Kosong',
                'berlaku_sampai.required' => 'Tidak boleh Kosong',
                'perpanjang_stnk.required' => 'Tidak boleh Kosong',
                'harga_beli.required' => 'Tidak boleh Kosong',
                'tanggal_beli.required' => 'Tidak boleh Kosong',
                'photo_stnk.image' => 'File Harus Berupa Gambar',
                'photo_bpkb.image' => 'File Harus Berupa Gambar',
                'photo_stnk.max' => 'Gambar Minimal Berukuran 3MB',
                'photo_bpkb.max' => 'Gambar Minimal Berukuran 3MB',
            ];
        };
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('error', 'Pastikan anda menginput dengan benar')
                ->withErrors($validator)
                ->withInput();
        } else {
            $beli = Buy::where('unique', $buy)->first();
            $motor = Bike::where('id', $beli->bike_id)->first();
            $consumer = Consumer::where('id', $beli->consumer_id)->first();
            $cek_consumer_lain = Consumer::where('nik', "=", $request->nik)->where('nik', "!=", $consumer->nik)->first();
            // dd($cek_consumer_lain);
            //Update Consumer
            if ($request->penjual == "INDIVIDU") {
                if (!$cek_consumer_lain) {
                    $data_consumer = [
                        'nik' => $request->nik,
                        'nama' => ucwords(strtolower($request->nama)),
                        'no_telepon' => $request->no_telepon,
                        'alamat' => $request->alamat,
                    ];
                    if ($request->photo_ktp) {
                        $data_consumer['photo_ktp'] = $request->file('photo_ktp')->store('ktp');
                        if ($request->oldKTP) {
                            Storage::delete($request->oldKTP);
                        }
                    }
                    Consumer::where('id', $consumer->id)->update($data_consumer);
                }
            } else if ($request->penjual == "DEALER") {
                $data_consumer = [
                    'penjual' => $request->penjual,
                    'nama' => ucwords(strtolower($request->nama_kang)),
                    'dealer' => strtoupper($request->dealer),
                ];
                Consumer::where('id', $consumer->id)->update($data_consumer);
            }


            //  Update Motor
            if ($request->file('photo_stnk') == NULL && $request->file('photo_bpkb') != NULL) {
                $data_motor = [
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'type' => $request->type,
                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'photo_bpkb' => $request->file('photo_bpkb')->store('bpkb'),
                ];
                if ($request->oldImageBPKB != NULL) {
                    Storage::delete($request->oldImageBPKB);
                }
                if ($cek_consumer_lain) {
                    $data_motor['consumer_id'] = $cek_consumer_lain->id;
                }
                Bike::where('id', $motor->id)->update($data_motor);
            } elseif ($request->file('photo_stnk') != NULL && $request->file('photo_bpkb') == NULL) {
                $data_motor = [
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'type' => $request->type,

                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'photo_stnk' => $request->file('photo_stnk')->store('stnk'),
                ];
                if ($request->oldImageSTNK != NULL) {
                    Storage::delete($request->oldImageSTNK);
                }
                if ($cek_consumer_lain) {
                    $data_motor['consumer_id'] = $cek_consumer_lain->id;
                }
                Bike::where('id', $motor->id)->update($data_motor);
            } elseif ($request->file('photo_stnk') == NULL && $request->file('photo_bpkb') == NULL) {
                $data_motor = [
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'type' => $request->type,

                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                ];
                if ($cek_consumer_lain) {
                    $data_motor['consumer_id'] = $cek_consumer_lain->id;
                }
                Bike::where('id', $motor->id)->update($data_motor);
            } elseif ($request->file('photo_stnk') != NULL && $request->file('photo_bpkb') != NULL) {
                $data_motor = [
                    'merek' => ucwords(strtolower($request->merek)),
                    'tahun_pembuatan' => $request->tahun_pembuatan,
                    'warna' => ucwords(strtolower($request->warna)),
                    'no_rangka' => $request->no_rangka,
                    'no_mesin' => $request->no_mesin,
                    'bpkb' => $request->bpkb,
                    'alamat_bpkb' => $request->alamat_bpkb,
                    'nama_bpkb' => $request->nama_bpkb,
                    'type' => $request->type,

                    'no_polisi' => strtoupper($request->no_polisi),
                    'berlaku_sampai' => $request->berlaku_sampai,
                    'perpanjang_stnk' => $request->perpanjang_stnk,
                    'photo_bpkb' => $request->file('photo_bpkb')->store('bpkb'),
                    'photo_stnk' => $request->file('photo_stnk')->store('stnk'),
                ];
                if ($request->oldImageSTNK != NULL) {
                    Storage::delete($request->oldImageSTNK);
                }
                if ($request->oldImageBPKB != NULL) {
                    Storage::delete($request->oldImageBPKB);
                }
                if ($cek_consumer_lain) {
                    $data_motor['consumer_id'] = $cek_consumer_lain->id;
                }
                Bike::where('id', $beli->id)->update($data_motor);
            }

            $data_pembelian = [
                'tanggal_beli' => $request->tanggal_beli,
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli),
            ];
            if ($cek_consumer_lain) {
                $data_pembelian['consumer_id'] = $cek_consumer_lain->id;
            }
            Buy::where('unique', $buy)->update($data_pembelian);
            return redirect('/pembelian')->with('success', 'Data Pembelian Berhasil Diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buy $buy, $unique)
    {
        $query = Buy::where('unique', $unique)->first();
        $motor = Bike::where('id', $query->bike_id)->first();

        Buy::where('unique', $unique)->delete();
        Bike::where('unique', $motor->unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    public function cek_nik(Request $request)
    {
        $data = Consumer::where('nik', $request->nik)->first();

        if ($data) {
            return response()->json(['success' => $data]);
        } else {
            return response()->json(['errors' => 'data belum terdaftar']);
        }
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('bikes')
                ->join('buys', 'bikes.id', '=', 'buys.bike_id');
            $data = $query->get();
            $no_urut = 1;
            foreach ($data as $row) {
                $no_urut + 1;
                $row->tgl_beli = tanggal_hari($row->tanggal_beli);
                $row->harga = rupiah($row->harga_beli);
            }

            return DataTables::of($data)->addColumn('action', function ($row) {
                if ($row->status == 'READY STOCK') {
                    $actionBtn =
                        '<button class="btn btn-info btn-sm info-button" title="Detail Transaksi Pembelian" data-id="' . $row->id . '"><i class="bi-info-circle"></i>
                        </button>
                    <a href="/edit-transaksi/' . $row->unique . '" class="btn btn-success btn-sm edit-button" title="Edit Data Pembelian" data-id="' . $row->id . '"><i class="bi-pencil"></i></a>
                    
                    <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)" class="d-inline form-delete">
                        <button type="button" class="btn btn-danger btn-sm delete-button-pembelian" title="Hapus Data Pembelian" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="bi-trash"></i></button>
                    </form>';
                } else {
                    $actionBtn =
                        '<button class="btn btn-info btn-sm info-button" data-id="' . $row->id . '"><i class="bi-info-circle"></i>
                        </button>
                        <a href="javascript:;" class="btn btn-dark btn-sm edit-button" data-id="' . $row->id . '"><i class="bi-pencil"></i></a>
                        <button type="button" class="btn btn-dark btn-sm"><i class="bi-trash"></i></button>';
                }

                return $actionBtn;
            })
                ->make(true);
        }
    }

    public function get_transaksi(Request $request)
    {
        $buy = Buy::where('id', $request->id)->first();
        $motor = Bike::where('id', $buy->bike_id)->first();
        $consumer = Consumer::where('id', $buy->consumer_id)->first();

        $data = [
            'beli' => $buy,
            'motor' => $motor,
            'consumer' => $consumer,
            'harga' => rupiah($buy->harga_beli),
            'tanggal_beli' => tanggal_hari($buy->tanggal_beli),
            'berlaku_sampai' => tanggal_hari($motor->berlaku_sampai),
            'perpanjang_stnk' => tanggal_hari($motor->perpanjang_stnk)
        ];

        return response()->json(['success' => $data]);
    }
}
