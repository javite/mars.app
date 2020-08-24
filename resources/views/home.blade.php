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
        <div id="lamps-container" class="row">
        @foreach ($devices as $device)
        <spam>Lampara: </spam><a href=http://{{$device->IP}} class="col-md-6 lamp"> {{ $device->name }}</a>
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