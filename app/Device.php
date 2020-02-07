<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $guarded = [];

    public function program(){
        return $this->hasMany("App\Program","device_id");
    }
    public function user(){
        return $this->belongsTo("App\User","user_id");
    }
}
