<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;
use App\Outputs_name;
use App\Output;

class ProgramsController extends Controller
{
    public function getPrograms($device_id ){
        $programs = Program::where("device_id","=",$device_id )->get();

        return $programs; //lo devuelve en json
    }

    public function saveProgram(Request $data){
        $program_id = $data["program_id"];
        $program_name = $data["program_name"];  
        $program = Program::find($program_id);

        if ($program->id == null) {
            $response = 0;
        } else {
            $program->name = $program_name;
            $program->save();
            $response = $program;
        }
    
        return $response; //TODO: ver errores
    }

      public function deleteProgram(Request $data){
        $program_id = $data["program_id"];
        $program = Program::find($program_id);
        $program->delete();
    
        return 1; //TODO: ver errores
      }

      public function newProgram(Request $data){
        $device_id = $data["device_id"];
        $program_name = $data["program_name"];
        $photo_periods = 1;
        $programs = Program::where('name', '=', $program_name)->get();
        
        if (sizeOf($programs) == 0) {  
          $newProgram = new Program();
          $newProgram->device_id  = $device_id;
          $newProgram->name  = $program_name;
          $newProgram->photo_periods = $photo_periods;
          $newProgram->save();
          $id = $newProgram->id;
          $outputs_names = Outputs_name::all();
          foreach ($outputs_names as $key => $outputs_name) { //se crean todas las salidas
            $program_id = $id;
            $out = $outputs_name->id;
            $timer_mode = 0;
            $newOutput = new Output();
            $newOutput->program_id = $program_id ;
            $newOutput->outputs_names_id = $out;
            $newOutput->timerMode = $timer_mode;
            $newOutput->save();
          }
        } else {
          $id = 0;
        }
        return $newProgram; //TODO: ver errores
      }

      public function outputList(){
        $programs = Program::all();
        $vac = compact('programs');
    
        return view('outputList', $vac);
    }
}
