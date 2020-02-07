<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public $guarded = [];

    public function device(){
        return $this->belongsTo("App\Device","device_id");
    }

    public function output(){
        return $this->hasMany("App\Output","program_id");
    }
}
