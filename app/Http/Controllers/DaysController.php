<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Day;

class DaysController extends Controller
{
    public function getDays($output_id){
        $days = Day::where("output_id","=",$output_id )->get();

        return json_encode($days );
    }

    public function saveDay(Request $data){
        $day_id = $data["day_id"];
        $day_ = $data["day"];
        $hour_on = $data["hour_on"];
        $hour_off = $data["hour_off"];
        $day = Day::find($day_id);
        if ($day->id == null) {
            $response = 0;
        } else {
            $day->day = $day_ ;
            $day->hour_on = $hour_on;
            $day->hour_off = $hour_off ;
            $day->save();
            $response = $day->id;
        }
    
        return $response;
    }

    public function newDay(Request $data){
        $output_id = $data["output_id"];
        $day = $data["day"];
        $hour_on = $data["hour_on"];
        $hour_off = $data["hour_off"];
        $newDay = new Day();
        $newDay->output_id  = $output_id;
        $newDay->day= $day;
        $newDay->hour_on  = $hour_on;
        $newDay->hour_off = $hour_off;
        $newDay->save();
        
        return $newDay->id;
    }

    public function deleteDay(Request $data){
        $day_id = $data["day_id"];
        $day = Day::find($day_id);
        $day->delete();

        return 1; //TODO: ver errores
    }
}
