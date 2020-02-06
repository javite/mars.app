<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Program;

class ProgramsController extends Controller
{
    public function getPrograms($device_id ){
        $programs = Program::where("device_id","=",$device_id )->get();

        return json_encode($programs);
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
            $response = $program->id;
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
        } else {
          $id = 0;
        }

        return $id; //TODO: ver errores
      }
}
