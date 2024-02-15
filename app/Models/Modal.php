<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'unique';
    }

    public static function jumlah_asset()
    {
        return DB::table('bikes')
            ->join('buys', 'bikes.id', '=', 'buys.bike_id')
            ->where('bikes.status', 'READY STOCK')
            ->sum('buys.harga_beli');
    }
}
