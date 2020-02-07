@extends('layout')

@section('js')
<script src="js/test.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection
@section('content')
    @foreach($programs as $program)
    <h3>Program: {{$program->name}}</h3>
    <ul>
        @foreach($program->output as $output)
        <li>
            <a href="#">Output: {{$output->output_name->output_name}}</a>
            <ul>
            @foreach($output->days as $day)
            <li>
                <p>Day: {{$day->day}} </p>
            </li>
            @endforeach
            </ul>
        </li>
        @endforeach
    </ul>
    @endforeach
@endsection