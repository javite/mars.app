<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    public $guarded = [];

    public function program(){
        return $this->belongsTo("App\Program","program_id");
    }

    public function output_name(){
        return $this->belongsTo('App\Outputs_name', 'outputs_names_id');
    }

    public function days(){
        return $this->hasMany("App\Day","output_id");
    }
}
