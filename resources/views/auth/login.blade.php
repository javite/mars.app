<?php

// require_once("init.php");

$errores = [];
$email = "";
$mensajeContrasena = "";
$mensajeEmail = "";
$contrasena = "";

// if($authentication->isLogged()){
//     header("Location:main.php");exit;
// }

// if ($_POST) {
//   $errores = $validator->validarLogin($_POST);
//   if (empty($errores)) {
//     $authentication->login($_POST["email"]);
//     if (isset($_POST["remember-me"])) {
//       setcookie("user_email", $_POST["email"], time() + 60 * 60 * 24 * 7);
//     }
//     header("Location:main.php");exit;
//   } else{
//       if(isset($errores["password"])){
//         $mensajeContrasena = $errores["password"];
//       }
//       if(isset($errores["email"])){
//        $mensajeEmail = $errores["email"];
//       }
//   }
// }

?>

@extends('template')
@section('css')
<link rel="stylesheet" type="text/css" href="css/style_login.css">
@endsection
@section('content')
    <div class="fondo">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="main-div col-sm-6">
                    <div class="panel">
                        <h2>Logueate</h2>
                        <p>Ingresa tu usuario y contraseña</p>
                    </div>
                    <form id="Login" action="login.php" method="post">
                        <div class="form-group">
                            <input type="text" name="email" class="form-control" id="inputEmail" placeholder="email" value=<?=$email?>><span class="error"><?=$mensajeEmail?></span>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Contraseña" value=<?=$contrasena?>><span class="error"><?=$mensajeContrasena?></span>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="exampleCheck1" name="remember-me"> 
                            <label class="custom-control-label" for="exampleCheck1">Recordame</label>
                        </div>
                        <div class="forgot">
                            <a href="registracion.php">¿Olvidaste tu contraseña?</a>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
