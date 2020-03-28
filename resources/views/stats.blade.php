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
    <div class="container-fluid">
    <h4>Eventos</h4>
        <table class="table table-hover  table-sm table-striped ">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Planta</th>
                <th scope="col">Evento</th>
                <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Juana</th>
                    <td>La regue</td>
                    <td>2020-03-24 12:41</td>
                </tr>
                <tr>
                    <th scope="row">Juana</th>
                    <td>Le di mucho amor</td>
                    <td>2020-03-24 12:41</td>
                </tr>
                <tr>
                    <th scope="row">Juana</th>
                    <td >La sigo regando como loco y esta al sol</td>
                    <td>2020-03-24 12:41</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
