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
    <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">
    <div id="state">esperando...</div>
    <div id='error'></div>
    <div id='error2'></div>
    <h4>Tus lámparas:</h4>
    <div id="lamps-container" class="d-flex flex-column justify-content-center align-items-center"></div>
    <br>
    <div class='container-fluid'>
        <div class='row'>
            <button class='btn btn-secondary' id='btnAdd' style="display: none;"></button>
        </div>
    </div>
    <br>
    <div class='container-fluid'>
        <div class='row'>
            <button class='btn btn-dark' id='btnUpdate' style="display: none;">Actualizar</button>
        </div>
    </div>

    <div class='container-fluid'>
        <div class='row'>
            <button class='btn btn-dark' id='test'>Test</button>
        </div>
    </div>
    
    @guest
    <h1>guest</h1>

    @endguest

    </div>
@endsection