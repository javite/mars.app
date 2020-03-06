<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Measurement;

class MeasurementsController extends Controller
{
    public function getLastMeasurement() {
        $measurement = Measurement::latest()->first();
        return $measurement;
      }
    
    public function getMeasurements() {
        $obj = json_decode($_GET["x"], false);
        return $obj;
        $date_selected = strtotime($obj->date);
        $date_plus_1 = strtotime("+1 day", $date_selected);
        $today = date('Y-m-d H:i:s',$date_selected);
        $tomorrow = date('Y-m-d H:i:s',$date_plus_1);
        $device_id = $obj->device_id;

        $measurements = Measurement::whereBetween('created_at',[$today, $tomorrow])->where('device_id','=', $device_id)->orderBy('id','desc')->get();
        return $measurements;

    }

    public function saveMeasurement($data){
        $idDevice = $data->idDevice;
        $temperature = $data->temperature;
        $humidity = $data->humidity;
        $soil_humidity_1 = $data->soil_humidity_1;

        $consulta = $this->dataBase->prepare("INSERT into measurements values (null, :id_device, :temperature, :humidity, :soil_humidity_1, default)");
        $consulta->bindValue(':id_device',$idDevice,PDO::PARAM_INT);
        $consulta->bindValue(':temperature',$temperature ,PDO::PARAM_INT);
        $consulta->bindValue(':humidity',$humidity ,PDO::PARAM_INT);
        $consulta->bindValue(':soil_humidity_1',$soil_humidity_1 ,PDO::PARAM_INT);
        $consulta->execute();

    }
}
