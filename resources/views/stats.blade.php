<?php
// require_once("init.php");

// if(!$authentication->isLogged()){
//     header("Location:login.php");exit;
// }
// $device_selected = $_SESSION["device_id"];

// include("head.php");
?>
@extends('layout')
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="css/style_main.css" />
@endsection
@section('js')
<script src="js/moment.js"></script>
<script src="js/chart.js"></script>
<script src="js/functions.js"></script>
<script src="js/stats.js" type="module"></script>
@endsection

@section('content')
    <div class="background-image"></div>
    <div class="container-fluid" id="stats-container"></div>
@endsection
