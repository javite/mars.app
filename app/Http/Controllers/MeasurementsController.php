<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Measurement;
use App\Device;

class MeasurementsController extends Controller
{
    public function getLastMeasurement($device_id) {
        $measurement = Measurement::where('device_id','=',$device_id)->latest()->first();
        $tmp = (array) $measurement;
        if(empty($tmp)){
            return -1;
        } else {
            return $measurement;
        }
      }
    
    public function getMeasurements() {
        $date_selected = strtotime($_GET['date']);
        $device_id = $_GET['device_id'];
        $limit = $_GET['limit'];

        $date_plus_1 = strtotime("+1 day", $date_selected);
        $today = date('Y-m-d H:i:s',$date_selected);
        $tomorrow = date('Y-m-d H:i:s',$date_plus_1);
        
        $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->limit($limit)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function newMeasurement(Request $data){
        $device_id = $data->device_id;
        $device = Device::find($device_id);

        if($device->api_token == $data->api_token){
            $temperature = $data->temperature;
            $humidity = $data->humidity;
            $soil_humidity_1 = $data->soil_humidity_1;
    
            $newMeasurement = new Measurement();
            $newMeasurement->device_id = $device_id;
            $newMeasurement->temperature = $temperature;
            $newMeasurement->humidity = $humidity;
            $newMeasurement->soil_humidity_1 = $soil_humidity_1;
            $newMeasurement->save();
            return $newMeasurement->id;
        } else {
            return -1;
        } 

    }
}
