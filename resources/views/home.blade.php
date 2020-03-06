@extends('layout')
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="/css/style_main.css" />
@endsection
@section('js')
<script src="/js/moment.js"></script>
<script src="/js/chart.js"></script>
<script src="/js/functions.js"></script>
@endsection
   
@section('content')
    <div class="background-image"></div>
    <div class="container-fluid">
            <form form action="main.php" method="get" class="form devices-list">
                <div class="form-group mx-sm-3">
                    <input type="hidden" name="device_id" value="0" >
                    <label for="devices">Growers</label>
                        <select name="device_id" class="mdb-select form-control md-form colorful-select dropdown-primary">
                            <option value="1" "selected">Grower-1</option>
                        </select>
                    <br>
                    <button type="submit" class="btn btn-primary" value="submit">Seleccionar</button>
                </div>
            </form>
        <div class="card-deck">
            <div class="card shadow bg-light text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="text-left">Temperatura</h4>
                            <h5 class="text-left">ambiente</h5>
                        </div>
                        <div class="card-value col">
                            <h3 class="card-text">23ºC</h3>
                            <img src="images/thermometer.svg" id="img-temp"alt="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted text-left">Actualizado: 20-01-2020</small>
                </div>
            </div>
            <div class="card shadow bg-light text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="text-left">Humedad</h4>
                            <h5 class="text-left">ambiente</h5>
                        </div>
                        <div class="card-value col">
                            <h3 class="card-text">80%</h3>
                            <img src="images/drop.svg" id="img-hum"alt="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted text-left">Actualizado: Actualizado: 20-01-2020</small>
                </div>
            </div>
            <div class="card shadow bg-light text-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h4 class="text-left">Humedad</h4>
                            <h5 class="text-left">de tierra</h5>
                        </div>
                        <div class="card-value col">
                            <h3 class="card-text">20%</h3>
                            <img src="images/soil_hum.svg" id="img-soil"alt="">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted text-left">Actualizado: 20-01-2020</small>
                </div>
                
            </div>
        </div>

        <div class="card-deck">
            <div class="card shadow text-success bg-dark mb-3 text-center">
                <h5 class="card-header">Iluminación</h5>
                <div class="card-body">
                    <img class="lamp" src="images/lamp.png" alt="Card image cap">
                    <h4 class="card-text text-success mb-3">Encendida</h4>
                    <a href="#" class="btn btn-secondary mb-2">Apagar</a>
                </div>
            </div>

            <div class="card shadow text-success bg-dark mb-3 text-center">
                <h5 class="card-header">Riego</h5>
                <div class="card-body">
                    <img class="lamp" src="images/riego.png" alt="Card image cap">
                    <h4 class="card-text text-success mb-3">Apagado</h4>
                    <a href="#" class="btn btn-secondary mb-2">Encender</a>
                </div>
            </div>

            <div class="card shadow text-success bg-dark mb-3 text-center">
                <h5 class="card-header">Ventilador</h5>
                <div class="card-body">
                    <img class="lamp" src="images/ventilador.png" alt="Card image cap">
                    <h4 class="card-text text-success mb-3">Encendido</h4>
                    <a href="#" class="btn btn-secondary mb-2">Apagar</a>
                </div>
            </div>
        </div>
    </div>
@endsection