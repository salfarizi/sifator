<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kredit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'unique';
    }

    public static function get_data($unique)
    {
        $query = DB::table('kredits as a')
            ->join('bikes as b', 'a.bike_id', '=', 'b.id')
            ->join('buyers as c', 'a.buyer_id', '=', 'c.id')
            ->where('a.unique', $unique)
            ->first();
        return $query;
    }

    public static function data_pertanggal($awal, $akhir)
    {
        $query = DB::table('kredits as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->where('a.tanggal_jual', '>=', $awal)
            ->where('a.tanggal_jual', '<=', $akhir)
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_hari_ini($tanggal)
    {
        $query = DB::table('kredits as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->where('a.tanggal_jual', '=', $tanggal)
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_minggu_ini()
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();
        $query = DB::table('kredits as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_jual', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_bulan_ini()
    {
        $minggu_awal =  Carbon::now()->startOfMonth()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfMonth()->toDateString();
        $query = DB::table('kredits as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_jual', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_bulan_ini_select($minggu_awal, $minggu_akhir)
    {
        $query = DB::table('kredits as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_jual', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }
}
