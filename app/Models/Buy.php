<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buy extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    public function getRouteKeyName()
    {
        return 'unique';
    }

    public static function get_data($unique)
    {
        $query = DB::table('seles as a')
            ->join('bikes as b', 'a.bike_id', '=', 'b.id')
            ->join('buyers as c', 'a.buyer_id', '=', 'c.id')
            ->where('a.unique', $unique)
            ->first();
        return $query;
    }

    public static function data_pertanggal($awal, $akhir)
    {
        $query = DB::table('buys as a')
            ->join('consumers as b', 'a.consumer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->where('a.tanggal_beli', '>=', $awal)
            ->where('a.tanggal_beli', '<=', $akhir)
            ->orderBy('a.tanggal_beli', 'DESC');
        return $query->get();
    }

    public static function data_hari_ini($tanggal)
    {
        $query = DB::table('buys as a')
            ->join('consumers as b', 'a.consumer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->where('a.tanggal_beli', '=', $tanggal)
            ->orderBy('a.tanggal_beli', 'DESC');
        return $query->get();
    }

    public static function data_minggu_ini()
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();
        $query = DB::table('buys as a')
            ->join('consumers as b', 'a.consumer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_beli', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_beli', 'DESC');
        return $query->get();
    }

    public static function data_bulan_ini()
    {
        $minggu_awal =  Carbon::now()->startOfMonth()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfMonth()->toDateString();
        $query = DB::table('buys as a')
            ->join('consumers as b', 'a.consumer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_beli', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_beli', 'DESC');
        return $query->get();
    }

    public static function data_bulan_ini_select($minggu_awal, $minggu_akhir)
    {
        $query = DB::table('buys as a')
            ->join('consumers as b', 'a.consumer_id', '=', 'b.id')
            ->join('bikes as c', 'a.bike_id', '=', 'c.id')
            ->whereBetween('tanggal_beli', [$minggu_awal, $minggu_akhir])
            ->orderBy('a.tanggal_beli', 'DESC');
        return $query->get();
    }
}
