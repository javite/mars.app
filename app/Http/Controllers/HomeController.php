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
        $devices = Device::where("user_id","=",$user_id )->get();
        $vac = compact('devices');
        $ip = 'http://'.$devices[0]->IP;
        // return redirect($ip,301);
        return view('home', $vac);

    }
}
