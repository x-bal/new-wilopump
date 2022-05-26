<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function modbuses()
    {
        return $this->hasMany(Modbus::class);
    }

    public function digitalInputs()
    {
        return $this->hasMany(DigitalInput::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function merges()
    {
        return $this->hasMany(Merge::class);
    }
}
