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
           <button class="btn" ip={{ $device->IP }}>Lampara: {{ $device->name }} <a href="{{ $device->IP }}"></a></button>
        @endforeach
        </div>
        @endisset

        @empty($device)
        
        @endempty
        
    @endauth
    
    @guest
    
    @endguest

    </div>
@endsection