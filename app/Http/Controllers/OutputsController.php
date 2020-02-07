<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Output;

class OutputsController extends Controller
{
    public function getOutputs($program_id){
        $outputs = Output::where("program_id","=",$program_id )->get();
        // $outputs = DB::table('outputs')
        //     ->join('outputs_names', 'outputs.outputs_names_id', '=', 'outputs_names.id')
        //     ->where('program_id',$program_id )->get();

        return $outputs->toJson(); //no hace falta to Json, ya devuelve Json.
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

    public function newOutput(Request $data){
        $program_id = $data["program_id"];
        $out = $data["output"];
        $timer_mode = $data["timer_mode"];
        $newOutput = new Output();
        $newOutput->program_id = $program_id ;
        $newOutput->out = $out;
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
