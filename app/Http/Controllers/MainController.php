<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;

class MainController extends Controller
{
    public function home (){
        return "hola";
        $devices = Device::all();
        $vac = compact($devices);

        return view('home', $vac);
    }
}
