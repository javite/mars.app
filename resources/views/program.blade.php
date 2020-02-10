
<?php
// require_once("init.php");

?>
@extends('layout')
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection

@section('js')
<script src="js/functions.js"></script>
<script src="js/program.js" type="module"></script>
@endsection

@section('content')
<div class="container" id="program-container">
    <form class ='form-program' id='program-form'>@csrf</form>
</div>
@endsection



