<?php

namespace App\Http\Controllers;

use App\Models\Regorder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\List_regorder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ListRegOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'nama_dealer' => 'required',
            'cmo' => 'required',
            'pic' => 'required',
            'jenis_transaksi' => 'required',
            'via' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tahun_pembuatan' => 'required',
            'otr' => 'required',
            'dp_po' => 'required',
            'pencairan' => 'required',
            'dp' => 'required',
            'angsuran' => 'required',
            'tenor' => 'required',
        ];
        $pesan = [
            'nama_dealer.required' => 'Nama dealer tidak boleh kosong',
            'cmo.required' => 'CMO tidak boleh kosong',
            'pic.required' => 'PIC/Seles tidak boleh kosong',
            'jenis_transaksi.required' => 'Jenis transaksi tidak boleh kosong',
            'via.required' => 'Kredit via tidak boleh kosong',
            'merk.required' => 'Merk tidak boleh kosong',
            'type.required' => 'Tipe motor tidak boleh kosong',
            'tahun_pembuatan.required' => 'Tahun pembuatan tidak boleh kosong',
            'dp.required' => 'DP tidak boleh kosong',
            'pencairan.required' => 'Nilai Pencairan jual tidak boleh kosong',
            'angsuran.required' => 'Angsuran tidak boleh kosong',
            'tenor.required' => 'Tenor tidak boleh kosong',
            'otr.required' => 'OTR tidak boleh kosong',
            'dp_po.required' => 'DP PO tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'regorder_id' => $request->unique_no_reg,
                'nama_dealer' => ucwords(strtolower($request->nama_dealer)),
                'cmo' => ucwords(strtolower($request->cmo)),
                'pic' => ucwords(strtolower($request->pic)),
                'jenis_transaksi' => $request->jenis_transaksi,
                'via' => $request->via,
                'merk' => ucwords(strtolower($request->merk)),
                'type' => ucwords(strtolower($request->type)),
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'otr' => preg_replace('/[,]/', '', $request->otr),
                'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                'dp' => preg_replace('/[,]/', '', $request->dp),
                'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                'tenor' => $request->tenor,
                'status' => 'DALAM PENGAJUAN',
            ];
            List_regorder::create($data);
            return response()->json(['success' => 'List Order Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(List_regorder $list_regorder)
    {
        //
    }

    public function get_data_list_order(Request $request)
    {
        $query = List_regorder::getDataListOrder($request->unique);
        return response()->json(['success' => $query]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(List_regorder $list_regorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, List_regorder $list_regorder)
    {
        //
    }

    public function get_data_list_order_update(Request $request)
    {
        $rules = [
            'nama_dealer' => 'required',
            'cmo' => 'required',
            'pic' => 'required',
            'jenis_transaksi' => 'required',
            'via' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'tahun_pembuatan' => 'required',
            'otr' => 'required',
            'dp_po' => 'required',
            'pencairan' => 'required',
            'dp' => 'required',
            'angsuran' => 'required',
            'tenor' => 'required',
        ];
        $pesan = [
            'nama_dealer.required' => 'Nama dealer tidak boleh kosong',
            'cmo.required' => 'CMO tidak boleh kosong',
            'pic.required' => 'PIC/Seles tidak boleh kosong',
            'jenis_transaksi.required' => 'Jenis transaksi tidak boleh kosong',
            'via.required' => 'Kredit via tidak boleh kosong',
            'merk.required' => 'Merk tidak boleh kosong',
            'type.required' => 'Tipe motor tidak boleh kosong',
            'tahun_pembuatan.required' => 'Tahun pembuatan tidak boleh kosong',
            'dp.required' => 'DP tidak boleh kosong',
            'pencairan.required' => 'Nilai Pencairan jual tidak boleh kosong',
            'angsuran.required' => 'Angsuran tidak boleh kosong',
            'tenor.required' => 'Tenor tidak boleh kosong',
            'otr.required' => 'OTR tidak boleh kosong',
            'dp_po.required' => 'DP PO tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'nama_dealer' => ucwords(strtolower($request->nama_dealer)),
                'cmo' => ucwords(strtolower($request->cmo)),
                'pic' => ucwords(strtolower($request->pic)),
                'jenis_transaksi' => $request->jenis_transaksi,
                'via' => $request->via,
                'merk' => ucwords(strtolower($request->merk)),
                'type' => ucwords(strtolower($request->type)),
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'otr' => preg_replace('/[,]/', '', $request->otr),
                'dp_po' => preg_replace('/[,]/', '', $request->dp_po),
                'pencairan' => preg_replace('/[,]/', '', $request->pencairan),
                'dp' => preg_replace('/[,]/', '', $request->dp),
                'angsuran' => preg_replace('/[,]/', '', $request->angsuran),
                'tenor' => $request->tenor,
            ];
            List_regorder::where('unique', $request->current_unique)->update($data);
            return response()->json(['success' => 'List Order Berhasil Diubah']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(List_regorder $list_regorder, $unique)
    {
        List_regorder::where('unique', $unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    public function dataTables(Request $request)
    {
        $query = List_regorder::dataTables($request->id);
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <button class="btn btn-rounded btn-sm btn-info info-button-list" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Data List Order" data-unique="' . $row->unique . '"><i class="bi-person-badge"></i></button>
                <button class="btn btn-warning btn-sm edit-list-order-button"  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data List Order" data-unique="' . $row->unique . '"><i class="bi-pencil-square"></i></button>
                <form action="javascript:;" class="d-inline form-delete-list-order">
                    <button type="button" class="btn btn-danger btn-sm delete-button-list-order" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="text-white bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data List Order"></i>
                </form>';
            return $actionBtn;
        })->addColumn('setatus', function ($row) {
            $actionBtn2 = '<div class="alert alert-warning" role="alert">' . $row->status . '</div>';
            return $actionBtn2;
        })->make(true);
    }

    public function status_setuju(Request $request)
    {
        List_regorder::where('unique', $request->unique)->update(['status' => 'DI SETUJUI']);
        return response()->json(['success' => 'Order Disetujui']);
    }
    public function status_tolak(Request $request)
    {
        List_regorder::where('unique', $request->unique)->update(['status' => 'DI TOLAK']);
        return response()->json(['success' => 'Order Ditolak']);
    }
    public function status_proses(Request $request)
    {
        List_regorder::where('unique', $request->unique)->update(['status' => 'DALAM PENGAJUAN']);
        return response()->json(['success' => 'Order Dalam Proses Pengajuan']);
    }
}
