<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Maintenance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\Bike;

class MaintenanceController extends Controller
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
            'jenis_perbaikan' => 'required',
            'tanggal_perbaikan' => 'required',
            'biaya' => 'required',
        ];
        $pesan = [
            'jenis_perbaikan.required' => 'Tidak boleh kosong',
            'tanggal_perbaikan.required' => 'Tidak boleh kosong',
            'biaya.required' => 'Tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $refresh = [
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli) + preg_replace('/[,]/', '', $request->biaya)
            ];
            $data = [
                'unique' => Str::orderedUuid(),
                'bike_id' => $request->bike_id,
                'jenis_perbaikan' => ucwords(strtolower($request->jenis_perbaikan)),
                'tanggal_perbaikan' => $request->tanggal_perbaikan,
                'biaya' => preg_replace('/[,]/', '', $request->biaya),
            ];

            Maintenance::create($data);
            Buy::where('bike_id', $request->bike_id)->update($refresh);

            return response()->json(['success' => 'Berhasil Melakukan Maintenance', 'refresh' => $refresh]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }

    public function get_maintenance(Request $request)
    {
        $query = Maintenance::where('unique', $request->unique)->first();
        return response()->json(['success' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $rules = [
            'jenis_perbaikan' => 'required',
            'tanggal_perbaikan' => 'required',
            'biaya' => 'required',
        ];
        $pesan = [
            'jenis_perbaikan.required' => 'Tidak boleh kosong',
            'tanggal_perbaikan.required' => 'Tidak boleh kosong',
            'biaya.required' => 'Tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'jenis_perbaikan' => ucwords(strtolower($request->jenis_perbaikan)),
                'tanggal_perbaikan' => $request->tanggal_perbaikan,
                'biaya' => preg_replace('/[,]/', '', $request->biaya),
            ];
            Maintenance::where('unique', $maintenance->unique)->update($data);
            $refresh = [
                'harga_beli' => preg_replace('/[,]/', '', $request->harga_beli) - $maintenance->biaya + preg_replace('/[,]/', '', $request->biaya)
            ];
            Buy::where('bike_id', $request->bike_id)->update($refresh);

            return response()->json(['success' => 'Berhasil Melakukan Update', 'refresh' => $refresh]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $motor = Buy::where('id', $maintenance->bike_id)->first();
        $data = [
            'harga_beli' => $motor->harga_beli - $maintenance->biaya
        ];
        Maintenance::where('unique', $maintenance->unique)->delete();
        Buy::where('id', $maintenance->bike_id)->update($data);
        return response()->json(['success' => 'Data Maintenance Berhasil Dihapus', 'refresh' => $data]);
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Maintenance::where('bike_id', '=', $request->id)->get();

            foreach ($query as $row) {
                $row->tanggal_perbaikan = tanggal_hari($row->tanggal_perbaikan);
                $row->biaya = rupiah($row->biaya);
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '<button type="button" class="btn btn-success btn-sm edit-maintenance-button" data-unique="' . $row->unique . '"><i class="bi-pencil"></i></button>
                    <form action="javascript:;" class="d-inline form-delete">
                        <button type="button" class="btn btn-danger btn-sm delete-maintenance-button" data-token="' . csrf_token() . '" data-unique="' . $row->unique . '"><i class="bi-trash"></i></button>
                    </form>';
                return $actionBtn;
            })->make(true);
        }
    }
}
