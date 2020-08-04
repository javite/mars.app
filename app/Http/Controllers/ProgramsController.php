<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Program;
use App\Outputs_name;
use App\Output;
use App\Device;
use App\Day;

class ProgramsController extends Controller
{
    public function getPrograms($device_id){
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
            $timer_mode = 1;
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

      public function loadProgram($device_id, $program_id){
        $device = Device::find($device_id);
        if ($device->id == null) {
            $response = 0;
        } else {
            $device->current_program_id = $program_id;
            $device->save();
            $response = 1;
        }
        return $response; 
      }

      public function outputList(){
        $programs = Program::all();
        $vac = compact('programs');
    
        return view('outputList', $vac);
    }

    public function getProgram($program_id){
      $response = [];
      $outs = [];
      $program = Program::find($program_id);
      $outputs = DB::table('outputs_names')
          ->select('outputs.id','output_name', 'outputs.outputs_names_id','timerMode', 'period', 'duration')
          ->join('outputs', 'outputs.outputs_names_id', '=', 'outputs_names.id')
          ->where('program_id','=', $program_id)->get();
          
      foreach($outputs as $key => $output){
          $days = Day::select('day','hour_on', 'hour_off')->where('output_id','=',$output->id)->get();
          if(sizeof($days) > 0){
              $days_ = [];
              $hours_on = [];
              $hours_off = [];
              foreach ($days as $key2 => $day) {
                  $days_[$key2] = $day->day;
                  $hour_on = explode(":", $day->hour_on); //separa valores por coma y transforma en array
                  $hour_on_float = floatval($hour_on[0]);
                  $minute_on_float = floatval($hour_on[1]) * 0.0166;
                  $hours_on[$key2]= round($hour_on_float + $minute_on_float, 4);
                  $hour_off = explode(":", $day->hour_off); //separa valores por coma y transforma en array
                  $hour_off_float = floatval($hour_off[0]);
                  $minute_off_float = floatval($hour_off[1]) * 0.0166;
                  $hours_off[$key2] = round($hour_off_float + $minute_off_float, 4);
              }
              $out = [
                  "timerMode" =>$output->timerMode,
                  "days"      =>$days_,
                  "hours_on"  =>$hours_on, 
                  "hours_off" =>$hours_off,
                  "out"       =>$output->outputs_names_id, 
                  "name"      =>$output->output_name, 
              ];
              $outs[] = $out;
          }
      }

      $response = array(
          "program" => $program,
          "ouputs"    => $outs
      );
      return json_encode($response);
  }
}
