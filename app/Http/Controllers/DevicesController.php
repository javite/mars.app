<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Device;

class DevicesController extends Controller
{
    public function getDevices(){
        $user = Auth::user();
        
        if(is_null($user->id)){
            return view('login');
        }
        $user_id = $user->id;
        $devices = Device::where("user_id","=",$user_id )->get();

        return $devices;
    }

    public function getDevice($serial_number, $ip, $net_name){
        $device = Device::where("serial_number","=",$serial_number)->get()->first();
        if ($device == null){
            $newDevice = new Device();
            $newDevice->user_id  = 0;
            $newDevice->name = "";
            $newDevice->model = "";
            $newDevice->version = "";
            $newDevice->firmware_version = "";
            $newDevice->serial_number = $serial_number;
            $newDevice->IP = $ip;
            $newDevice->api_token = Str::random(60);
            if($newDevice->save()){
                $response = $newDevice;
            } else $response = -1;
            
        } else {
            if (strcmp($device->IP,$ip) !== 0) {
                $device->IP = $ip;
                $device->net_name = $net_name;
                $device->save();
            }
            $response = $device;
        }
        return $response;
    }

    public function saveDevice(Request $data){
        $device_id = $data["device_id"];
        $device_name = $data["device_name"];
        $device = Device::find($device_id);

        if ($device->id == null) {
            $response = 0;
        } else {
            $device->name = $device_name;
            $device->save();
            $response = $device->id;
        }
    
        return $response;
    }

      public function deleteDevice(Request $data){
        $device_id = $data["device_id"];
        try {
          $device = Device::findOrFail($device_id);
          $device->delete();
          $response = 1;
      } catch (\Throwable $th) {
          $response = -1;
      }
  
      return $response;

      }

      public function newDevice(Request $data){
        $user_id = $data["user_id"];
        $device_name = $data["device_name"];

        $newDevice = new Device();
        $newDevice->user_id  = $user_id;
        $newDevice->name = $device_name;
        $newDevice->model = "Grower-full";
        $newDevice->version = "V1.0.0";
        $newDevice->api_token = Str::random(60);
        $newDevice->save();
        return $newDevice->id;
      }

      public function programList(){
        $devices = Device::All();
        $vac = compact('devices');
    
        return view('programList', $vac);
    }
}
