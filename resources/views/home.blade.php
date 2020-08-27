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
        <h4>Tus lámparas:</h4>
        <div id="lamps-container" class="d-flex flex-column justify-content-center align-items-center">
        @foreach($devices as $device)
        <a href=http://{{$device->IP}} class="lamp"> {{$device->name}}</a>
        @endforeach
        </div>
        @endisset

        @empty($devices)
        <h4>No hay lamparas asociadas al usuario</h4>
        @endempty
        
    @endauth
    
    @guest
    <h1>guest</h1>

    @endguest

    </div>
@endsection