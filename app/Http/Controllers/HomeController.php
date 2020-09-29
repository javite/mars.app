<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Device;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        if(is_null($user->id)){
            return view('login');
        }
        $devices = Device::where("user_id","=",$user_id )->get();
        if(!$devices->isEmpty()){
            $vac = compact('devices');
            return view('home', $vac);
        }
        return view('home');
         
    }
}
