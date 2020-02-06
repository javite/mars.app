@extends('layout')

@section('js')
<script src="js/test.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection
@section('content')
<form  id="program-form" >
    <select name="program" class="program-selector form-control">
        <option value="14">Tomates</option>
        <option value="4">pepes</option>
    </select>
    <input type="hidden" name="device_id" value="1">
    <input type="text" name="program_id" value="14">
    <input type="text" name="program_name" value="guille">
    <button type="submit" class="btn btn-secondary">submit</button>
</form>

@endsection