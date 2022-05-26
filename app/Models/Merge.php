<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merge extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function modbuses()
    {
        return $this->hasMany(Modbus::class);
    }
}
