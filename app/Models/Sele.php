<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sele extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function buy()
    {
        return $this->belongsTo(Buy::class);
    }

    public function getRouteKeyName()
    {
        return 'unique';
    }

    public static function data_pertanggal($awal, $akhir)
    {
        $query = DB::table('seles as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->where('a.tanggal_jual', '>=', $awal)
            ->where('a.tanggal_jual', '<=', $akhir)
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_hari_ini($tanggal)
    {
        $query = DB::table('seles as a')
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
        $query = DB::table('seles as a')
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
        $query = DB::table('seles as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_jual', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }

    public static function data_bulan_ini_select($minggu_awal, $minggu_akhir)
    {
        $query = DB::table('seles as a')
            ->join('buyers as b', 'a.buyer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_jual', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_jual', 'DESC');
        return $query->get();
    }
}
