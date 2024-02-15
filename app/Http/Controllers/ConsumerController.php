<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Bike;
use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Data Penjual | SIFATOR',
            'judul' => 'Data Penjual',
            'breadcumb1' => 'Master',
            'breadcumb2' => 'Data Penjual',
        ];
        return view('consumer.index', $data);
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
    public function show(Consumer $consumer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumer $consumer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumer $consumer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumer $consumer)
    {
        //
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Consumer::where('penjual', 'INDIVIDU')->get();
            // foreach ($query as $row) {
            //     $alamat = explode(' ', $row->alamat);
            //     $alamat2 = $alamat[0] . ' ' . $alamat[1];
            //     $row->alamat = $alamat2;
            // }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionButton = '
                <button class="btn btn-rounded btn-sm btn-info info-button-ktp" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Foto KTP" data-ktp="' . $row->photo_ktp . '"><i class="bi-person-badge"></i></button>
                <button class="btn btn-rounded btn-sm btn-primary info-button-individu" data-bs-toggle="tooltip" data-bs-placement="top" title="Riwayat Penjualan Individu" data-id="' . $row->id . '"><i class="bi-journal-text"></i></button>
                ';
                return $actionButton;
            })->make(true);
        }
    }

    public function dataTables2(Request $request)
    {
        if ($request->ajax()) {
            $query = Consumer::where('penjual', 'DEALER')->get();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionButton = '<button class="btn btn-rounded btn-sm btn-primary info-button-dealer" data-bs-toggle="tooltip" data-bs-placement="top" title="Riwayat Penjualan Non Individu" data-id="' . $row->id . '"><i class="bi-journal-text"></i></button>';
                return $actionButton;
            })->make(true);
        }
    }

    public function dataTablesMotor(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('bikes')
                ->join('buys', 'bikes.id', '=', 'buys.bike_id')
                ->where('bikes.consumer_id', '=', $request->id)
                ->get();

            foreach ($query as $row) {
                $row->tanggal_beli = tanggal_hari($row->tanggal_beli);
                $row->harga_beli = rupiah($row->harga_beli);
            }

            return DataTables::of($query)->make(true);
        }
    }
}
