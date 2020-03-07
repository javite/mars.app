<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Device;

class DevicesController extends Controller
{
    public function getDevices($user_id){
        $devices = Device::where("user_id","=",$user_id )->get();

        return json_encode($devices);
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
        $device = Device::find($device_id);
        $device->delete();
    
        return 1; //TODO: ver errores
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
