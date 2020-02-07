@extends('layout')

@section('js')
<script src="js/test.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection
@section('content')
    @foreach($devices as $device)
    <h3>Device: {{$device->name}}</h3>
    <ul>
        @foreach($device->program as $program)
        <li>
            <a href="#">Programa: {{$program->name}}</a>
        </li>
        @endforeach
    </ul>
    @endforeach
@endsection