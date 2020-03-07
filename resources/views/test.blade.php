@extends('layout')

@section('js')
<script src="js/test.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection
@section('content')
<form  id="program-form" class="form col"  >
    @csrf
    <!-- <select name="program" class="program-selector form-control">
        <option value="19">Tomates</option>
        <option value="4">pepes</option>
    </select> -->
    <div class="form-group">
        <label for="user_id" class="form-control">user id</label>
        <input type="text" name="user_id" value="1" class="form-control">
    </div>
    <!-- <div class="form-group">
        <label for="device_id" class="form-control">Device id</label>
        <input type="text" name="device_id" value="6">
    </div> -->
    <div class="form-group">
        <label for="device_name" class="form-control">Device name</label>
        <input type="text" name="device_name" value="Grow-2">
    </div>
    <!-- <div class="form-group">
        <button type="submit" class="btn btn-secondary">submit</button>
    </div> -->

    <!-- <div class="form-group">
        <label for="output_id" class="form-control">Output id</label>
        <input type="text" name="output_id" value="1" class="form-control">
    </div>
    <div class="form-group">
        <label for="day_id" class="form-control">Day id</label>
        <input type="number" name="day_id" value="1" class="form-control">
    </div>
    <div class="row days-bundle" style="">
        <div class="day-bundle-0 col-sm-6">
            <div class="day-selector-bundle-0">
                <label class="label-day-selector label" for="days">Dia</label>
                <br>
                <select class="day-selector-0 form-control day"  name="day">
                    <option id="day-0-0" value="0">Domingo</option>
                    <option id="day-0-1" value="1">Lunes</option>
                    <option id="day-0-2" value="2">Martes</option>
                    <option id="day-0-3" value="3">Miercoles</option>
                    <option id="day-0-4" value="4">Jueves</option>
                    <option id="day-0-5" value="5">Viernes</option>
                    <option id="day-0-6" value="6">Sabado</option>
                    <option id="day-0-7" value="7" selected="selected">Todos los dias</option>
                </select>
            </div>
            <div class="row hour-bundle-0">
                <div class="col h-on-bundle-0">
                    <label class="label-h-on" for="hour_on">Hora encendido</label>
                    <input type="time" class="h-on form-control text-center time" name="hour_on" value="00:30">
                </div>
                <div class="col h-off-bundle-0">
                    <label class="label-h-off" for="hour_off">Hora apagado</label>
                    <input type="time" class="h-off form-control text-center time"name="hour_off" value="01:30">
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="form-group">
        <label for="program_id" class="form-control">Program id</label>
        <input type="text" name="program_id" value="13" class="form-control">
    </div>
    <div class="form-group">
        <label for="program_name" class="form-control">Nombre Programa</label>
        <input type="text" name="program_name" value="Albahaca" class="form-control">
    </div> -->
    <!-- <div class="form-group">
        <label for="output_id" class="form-control">Output id</label>
        <input type="text" name="output_id" value="32" class="form-control">
    </div>
    <div class="form-group">
        <label for="output" class="form-control">Output</label>
        <input type="text" name="output" value="2">
    </div>
    <div class="form-group">
        <label for="timer_mode" class="form-control">timer mode</label>
        <input type="text" name="timer_mode" value="0">
    </div> -->
    <div class="form-group">
        <br>
        <button type="button" class="btn btn-secondary" id="submit">submit</button>
    </div>
</form>

@endsection