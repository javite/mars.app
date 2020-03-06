<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Day;
use App\Days_name;
use App\Outputs_name;
use Illuminate\Support\Facades\DB;

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
            $response = $day;
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
        
        return $newDay;
    }

    public function deleteDay(Request $data){
        $day_id = $data["day_id"];
        $day = Day::find($day_id);
        $day->delete();

        return 1; //TODO: ver errores
    }

    public function getDaysNames(){
        $days_names = Days_name::all();

        foreach($days_names as $index => $days_name){
            $days_names_a[$index ] = $days_name->day_name;
        }
        // $outputs_names = DB::table('outputs_names')->get();
        $outputs_names = Outputs_name::all();

        foreach($outputs_names as $index => $outputs_name){
            $outputs_names_a[$index ] = $outputs_name->output_name;
        }
        $result[0] = $days_names_a;
        $result[1]= $outputs_names_a;
      
        return json_encode($result); 
    }
}
