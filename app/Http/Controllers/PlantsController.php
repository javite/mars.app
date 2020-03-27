<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Plant;

class PlantsController extends Controller
{
    public function getPlants(){
        $user = Auth::user();
        $user_id = $user->id;
        $plants = Plant::where("user_id","=",$user_id )->get();

        return $plants;
    }

    public function savePlant(Request $data){
        $user = Auth::user();
        $user_id = $user->id;
        $device_id = $data["device_id"];
        $plant_id = $data["plant_id"];
        $name = $data["plant_name"];
        $species = $data["species"];
        $comment = $data["comment"];

        try {
            $plant = Plant::findOrFail($plant_id);
            if(!empty($data["plant_name"])){
                $plant->name = $name;
            }
            if(!empty($data["species"])){
                $plant->species = $species;
            }
            if(!empty($data["comment"])){
                $plant->comment = $comment;
            }
            $plant->save();
            $response = $plant;
        } catch (\Throwable $th) {
            $response = -1;
        }
        
        return $response;
    }

    public function deletePlant(Request $data){
        $plant_id = $data["plant_id"];
        try {
            $plant = Plant::findOrFail($plant_id);
            $plant->delete();
            $response = 1;
        } catch (\Throwable $th) {
            $response = -1;
        }
    
        return $response;
    }

    public function newPlant(Request $data){
        $user = Auth::user();
        $user_id = $user->id;
        $device_id = $data["device_id"];
        $name = $data["plant_name"];
        $species = $data["species"];
        $comment = $data["comment"];

        $newPlant = new Plant();
        $newPlant->user_id  = $user_id;
        $newPlant->device_id  = $device_id;
        $newPlant->name = $name;
        if(isset($data["species"])){
            $newPlant->species = $species;
        }
        if(isset($data["comment"])){
            $newPlant->comment = $comment;
        }

        $newPlant->save();
        return $newPlant->id;
      }
}
