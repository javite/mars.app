<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outputs_name extends Model
{
    public $guarded = [];

    public function output(){
        return $this->hasMany('App\Output','outputs_names_id');
    }
}
