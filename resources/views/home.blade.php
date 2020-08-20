@extends('layout')
@section('css')
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
@endsection
@section('js')
    <script src="/js/home.js" ></script>
@endsection
   
@section('content')
    <div class="background-image"></div>
    <div class="container" id="home-container">
    @auth

        @isset($devices)
        <div id="lamps-container">
        @foreach ($devices as $device)
           <button class="btn" ip={{ $device->IP }}>Lampara: {{ $device->name }}</button>
        @endforeach
        </div>
        <div class="container-fluid hidden" id="program-container">
        <form class="row form-program" id="form-program"  >
            <input type="number" name="photo_periods" value="2" style="display: none;">
            <div class=" col-md-6">
                <h4 class="title">Primer Fotoperíodo</h4>
                <div class="form-group date-container">
                    <div class="g-1">
                        <label for="fecha-inicio"  class="col-form-label">Comienza el:</label>
                        <input type="date" name="init_period_1" id="fecha-inicio-1" class="input-date" value="">
                    </div>
                    <div class="g-1">
                        <label for="fecha-fin" class="col-form-label">Duración del ciclo:</label>
                        <input type="number" id="duracion-1" class="input-num" value="0"><spam  id="duracion-ciclo-1"> días</spam>
                        <input type="date" name="end_period_1" id="fecha-fin-1" class="hidden" value="">
                    </div>
                    <div class="g-1">
                        <label for="fecha-fin" class="col-form-label">Termina el:</label>
                        <input type="date" id="fecha-fin-1-indicator" class="input-date" value="" disabled>
                    </div>
                     <div class="g-1">
                        <label for="hour_on_1"  class="col-form-label">Enciende a las:</label>
                        <input type="time" name="hour_on_1" id="hora-inicio-1" class="input-time" value="">
                    </div>
                    <div class="g-1">
                        <label for="duracion-hora-1"  class="col-form-label">Durante:</label>
                        <input type="number" id="duracion-hora-1" class="input-time" value="0"><spam> horas</spam>
                        <input type="time" name="hour_off_1" id="hora-fin-1" class="hidden" value="">
                    </div>
                </div> 
            </div> 
     
            <div class=" col-md-6">
                <h4 class="title">Segundo Fotoperíodo</h4>
                <div class="form-group date-container">
                    <div class="g-1">
                        <label for="fecha-inicio"  class="col-form-label">Comienza el:</label>
                        <input type="date" name="init_period_2" id="fecha-inicio-2" class="input-date" value="" >
                    </div>
                    <div class="g-1">
                        <label for="fecha-fin" class="col-form-label">Duración del ciclo:</label>
                        <input type="number" id="duracion-2" class="input-num" value="0"><spam  id="duracion-ciclo-2"> días</spam>
                        <input type="date" name="end_period_2" id="fecha-fin-2" class="hidden" value="">
                    </div>
                    <div class="g-1">
                        <label for="fecha-fin" class="col-form-label">Termina el:</label>
                        <input type="date" id="fecha-fin-2-indicator" class="input-date" value="" disabled>
                    </div>
                     <div class="g-1">
                        <label for="hour_on_2"  class="col-form-label">Enciende a las:</label>
                        <input type="time" name="hour_on_2" id="hora-inicio-2" class="input-time" value="">
                    </div>
                    <div class="g-1">
                        <label for="hour_off_2"  class="col-form-label">Durante:</label>
                        <input type="number" id="duracion-hora-2" class="input-time" value="0"><spam id=""> horas</spam>
                        <input type="time" name="hour_off_2" id="hora-fin-2" class="hidden" value="">
                    </div>
                </div> 
            </div> 

            <div class=" col-md-6">
                <button type="submit" class="submit btn btn-primary" id="submit-program">Activar el programa</button>
            </div> 
        </form> 

        <div class="d-flex flex-row">
            <button type="submit" class="btn btn-primary" id="toggleButton" onclick="onPress()" disabled>Encender Luces</button>
            <div class="indicator" id="indicator"></div>
        </div>
    </div>
        @endisset

        @empty($device)
        
        @endempty
        
    @endauth
    
    @guest
    
    @endguest

    </div>
@endsection