<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regorder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'unique';
    }

    public static function dataTables()
    {
        $query = DB::table('regorders as a')
            ->join('buyers as b', 'a.buyer_id', 'b.id')
            ->select('a.*', 'b.nik', 'b.nama', 'b.tempat_lahir', 'b.tanggal_lahir', 'b.jenis_kelamin', 'b.alamat', 'b.no_telepon', 'b.photo_ktp');
        return $query->get();
    }

    public static function dataTables2()
    {
        $query = DB::table('regorders as a')
            ->join('buyers as b', 'a.buyer_id', 'b.id')
            ->join('list_regorders as c', 'a.id', 'c.regorder_id')
            ->select('a.*', 'b.nik', 'b.nama', 'b.tempat_lahir', 'b.tanggal_lahir', 'b.jenis_kelamin', 'b.alamat', 'b.no_telepon', 'b.photo_ktp', 'c.');
        return $query->get();
    }

    public static function getBuyer($unique = '')
    {
        $query = DB::table('regorders as a')
            ->join('buyers as b', 'a.buyer_id', 'b.id')
            ->select('a.*', 'b.nik', 'b.nama', 'b.tempat_lahir', 'b.tanggal_lahir', 'b.jenis_kelamin', 'b.alamat', 'b.no_telepon', 'b.photo_ktp')
            ->where('a.unique', $unique);
        return $query->first();
    }
}
