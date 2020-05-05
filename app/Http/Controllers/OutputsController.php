<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Output;
use App\Day;

class OutputsController extends Controller
{
    public function getOutputs($program_id){
        // $outputs = Output::where("program_id","=",$program_id )->get();
        $days = [];
        $outputs = DB::table('outputs_names')
            ->join('outputs', 'outputs.outputs_names_id', '=', 'outputs_names.id')
            ->where('program_id',$program_id )->get();
        foreach($outputs as $key => $output){
            $days[$key] = Day::where('output_id','=',$output->id)->get();
        }
        $response["outputs"] = $outputs;
        $response["days"] = $days;
        return json_encode($response); //no hace falta to Json, ya devuelve Json.
    }

    public function getOutputsBoard($program_id){
        $response = [];
        $outputs = DB::table('outputs_names')
            ->select('outputs.id','output_name', 'outputs.outputs_names_id','timerMode', 'period', 'duration')
            ->join('outputs', 'outputs.outputs_names_id', '=', 'outputs_names.id')
            ->where('program_id',$program_id )->get();
        foreach($outputs as $key => $output){
            $days = Day::select('day','hour_on', 'hour_off')->where('output_id','=',$output->id)->get();
            if(sizeof($days)>0){
                $days_ = [];
                $hours_on = [];
                $hours_off = [];
                foreach ($days as $key2 => $day) {
                    $days_[$key2] = $day->day;
                    $hours_on[$key2]= $day->hour_on;
                    $hours_off[$key2] = $day->hour_off;
                }
                $out = [
                    "id"        =>$output->outputs_names_id, 
                    "name"      =>$output->output_name, 
                    "timerMode" =>$output->timerMode, 
                    "days"      =>$days_,
                    "hours_on"  =>$hours_on, 
                    "hours_off" =>$hours_off
                ];
            } else $out = [
                    "id"        =>$output->outputs_names_id, 
                    "name"      =>$output->output_name, 
                    "timerMode" =>$output->timerMode, 
                    "days"      =>[-1], 
                    "hours_on"  =>[0], 
                    "hours_off"  =>[0]
            ];
            $outs[$key] = $out;
        }
        $response = $outs;
        return json_encode($response);
    }

    public function saveOutput(Request $data){
        $output_id = $data["output_id"];
        $out = $data["output"];
        $timer_mode = $data["timer_mode"];
        $output = Output::find($output_id);
        if ($output->id == null) {
            $response = 0;
        } else {
            $output->out = $out;
            $output->timerMode = $timer_mode;
            $output->save();
            $response = $output->id;
        }
    
        return $response;
    }

    public function newOutput(Request $data){ //implementado en ProgramsController -> newProgram
        $program_id = $data["program_id"];
        $out = $data["output"];
        $timer_mode = $data["timer_mode"];
        $newOutput = new Output();
        $newOutput->program_id = $program_id ;
        $newOutput->outputs_names_id = $out;
        $newOutput->timerMode = $timer_mode;
        $newOutput->save();
        
        return $newOutput->id; 
    }

    public function deleteOutput(Request $data){
        $output_id = $data["output_id"];
        $output = Output::find($output_id);
        $output->delete();

        return 1; //TODO: ver errores
    }
}
