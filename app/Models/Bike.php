<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function consumer()
    {
        return $this->hasMany(Consumer::class);
    }

    public function getRouteKeyName()
    {
        return 'unique';
    }
}
