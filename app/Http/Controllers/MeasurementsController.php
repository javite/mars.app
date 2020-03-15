<?php

namespace App\Http\Controllers;

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
    
    public function getMeasurements($sensor_id) {
        $date_selected = strtotime($_GET['date']);
        $device_id = $_GET['device_id'];
        $limit = $_GET['limit'];

        $date_plus_1 = strtotime("+1 day", $date_selected);
        $today = date('Y-m-d H:i:s',$date_selected);
        $tomorrow = date('Y-m-d H:i:s',$date_plus_1);
        
        $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->limit($limit)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function getSensorMeasurements() {
        $date_selected = strtotime($_GET['date']);
        $sensor_id = $_GET['sensor_id'];
        $limit = $_GET['limit'];

        $date_plus_1 = strtotime("+1 day", $date_selected);
        $today = date('Y-m-d H:i:s',$date_selected);
        
        // $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->limit($limit)->orderBy('id','desc')->get();
        $measurements = Measurement::whereDate('created_at',$today)->where('sensor_id','=', $sensor_id)->limit($limit)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function newMeasurement(Request $data){ //Request

        if($data->has('device_id')){
            $device_id = $data->device_id;
            $device = Device::find($device_id);
            $array = [$data->temperature,$data->humidity,$data->soil_humidity_1];
    
            if($device->api_token == $data->api_token){
                for ($i=0; $i < 3; $i++) { //TODO enviar como matriz las mediciones.
                    $newMeasurement = new Measurement();
                    $newMeasurement->device_id = $device->id;
                    $newMeasurement->sensor_id = $i+1;
                    $newMeasurement->data = $array[$i];
                    $newMeasurement->save();
                }
                return $newMeasurement->id;
            } else {
                return -1;
            } 
        } else return -2;


    }
}
