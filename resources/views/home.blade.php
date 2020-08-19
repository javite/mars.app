@extends('layout')
@section('css')
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
@endsection
@section('js')
    <!-- <script src="/js/home.js" ></script> -->
@endsection
   
@section('content')
    <div class="background-image"></div>
    <div class="container" id="home-container">
    @auth

        @isset($devices)
        <ul>
        @foreach ($devices as $device)
           <li><a href=http://{{ $device->IP }}> Lampara: {{ $device->name }} </a></li> 
        @endforeach
        </ul>
        @endisset

        @empty($device)
        
        @endempty
        
    @endauth
    
    @guest
    
    @endguest

    </div>
@endsection