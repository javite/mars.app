<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Measurement;
use App\Device;
use App\Sensor;

class MeasurementsController extends Controller
{
    public function getMeasurement() {
        $sensor_id = $_GET['sensor_id'];
        $measurement = Measurement::where('sensor_id','=',$sensor_id)->latest()->first()->created_at->format('Y-m-d H:i:s');
        return $measurement;
    }

    public function getLastMeasurement($device_id) {
        $sensors = Sensor::where('device_id','=', $device_id)->get();
        $index = 0;
        $measurements = [];
        foreach ($sensors as $sensor) {
            $measurements[$sensor->name] = Measurement::where('sensor_id','=',$sensor->id)->latest()->first()->data;
            $created_at = Measurement::where('sensor_id','=',$sensor->id)->latest()->first()->created_at->format('Y-m-d H:i:s');
            $index++;
        }
        $measurements['created_at'] = $created_at;
        if(empty($measurements)){
            return -1;
        } else {
            return $measurements;
        }
      }
    
    public function getMeasurements() {
        $date_selected = strtotime($_GET['date']);
        $device_id = $_GET['device_id'];
        $limit = $_GET['limit'];

        $date_plus_1 = strtotime("+1 day", $date_selected);
        $today = date('Y-m-d H:i:s',$date_selected);
        $tomorrow = date('Y-m-d H:i:s',$date_plus_1);
        // $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->limit($limit)->orderBy('id','desc')->get();
        $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->limit($limit)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function getSensorMeasurements() {
        $date_selected = strtotime($_GET['date']);
        $sensor_id = $_GET['sensor_id'];
        $limit = $_GET['limit'];

        $date = date('Y-m-d H:i:s',$date_selected);
        
        $measurements = Measurement::whereDate('created_at',$date)->where('sensor_id','=', $sensor_id)->limit($limit)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function newMeasurement(Request $data){ 
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
