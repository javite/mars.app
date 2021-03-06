<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public $guarded = [];

    public function output(){
        return $this->belongsTo("App\Output","output_id");
    }
}
