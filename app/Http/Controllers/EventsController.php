<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Event;

class EventsController extends Controller
{
    public function getEvents($plant_id){
        $events= Event::where("plant_id","=",$plant_id )->get();

        return $events;
    }

    public function saveEvent(Request $data){
        $event_id = $data["event_id"];
        $event_new = $data["event"];

        try {
            $event = Event::findOrfail($event_id);
            if(!empty($data["event"])){
                $event->event = $event_new;
                $event->save();
                $response = $event;
            } else $response = 0; //El evento esta vacio.
            
        } catch (\Throwable $th) {
            $response = -1;
        }
        
        return $response;
    }

    public function deleteEvent(Request $data){
        $event_id = $data["event_id"];
        try {
            $event = Event::findOrFail($event_id);
            $event->delete();
            $response = 1;
        } catch (\Throwable $th) {
            $response = -1;
        }
    
        return $response;
    }

    public function newEvent(Request $data){
        $plant_id = $data["plant_id"];
        $event = $data["event"];

        $newEvent = new Event();
        $newEvent->plant_id = $plant_id;
        $newEvent->event = $event;
        $newEvent->save();
        return $newEvent->id;
      }
}
