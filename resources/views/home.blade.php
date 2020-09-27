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
    <div id="state">esperando...</div>
    <div id='error'></div>
    <div id='error2'></div>
    @auth
        <h4>Tus lámparas:</h4>
        <div id="lamps-container" class="d-flex flex-column justify-content-center align-items-center"></div>
        
    @endauth
    <br>
    <div class='container-fluid'>
        <div class='row'>
            <button class='btn btn-primary' id='btnAdd' style="display: none;">Baja la web-app</button>
        </div>
    </div>
    
    
    @guest
    <h1>guest</h1>

    @endguest

    </div>
@endsection