<?php

require_once("init.php");

$errores = [];
$nombreDefault = "";
$apellidoDefault = "";
$emailDefault = "";
$telefonoDefault = "";
$respuestaSDefault = "";

// Vine por POST?
if ( $_POST ) {
	// VALIDAR
	$errores = $validator->validarRegistracion($_POST);
	$nombreDefault = $_POST["name"];
	$apellidoDefault = $_POST["last_name"];
	$emailDefault = $_POST["email"];
	
	if (empty($errores) ) {
		// REGISTRAR
		$user = new Usuario($_POST);
		$db->crearUsuario($user);

		//GUARDAR LA FOTO

		// $ext = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
		// move_uploaded_file($_FILES["avatar"]["tmp_name"], "img/" . trim($_POST["email"]) . "." . $ext);

		// REDIRIGIRLO
		header("Location:main.php");exit;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registracion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style_login.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
</head>
<body>
	<div class="fondo">
		<div class="container-fluid">
			<div class="row justify-content-center">
			<div class="main-div col-sm-6">
				<div class="panel">
					<h2>Registrate!</h2>
				</div>
				<form action="registracion.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<?php if ( isset($errores["name"]) ) : ?>
							<input type="text" class="error form-control" placeholder="Nombre *" value="" name="name" />
							<p class="mensaje-error"><?=$errores["name"]?></p>
						<?php else : ?>
							<input type="text" class="form-control" placeholder="Nombre *" value="<?=$nombreDefault?>" name="name" />
						<?php endif; ?>
					</div>
					<div class="form-group">
						<?php if ( isset($errores["last_name"]) ) : ?>
							<input type="text" class="error form-control" placeholder="Apellido *" value="" name="last_name" />
							<p class="mensaje-error"><?=$errores["last_name"]?></p>
						<?php else : ?>
							<input type="text" class="form-control" placeholder="Apellido *" value="<?=$apellidoDefault?>" name="last_name" />
						<?php endif; ?>
					</div>
					<div class="form-group">
						<?php if ( isset($errores["password"]) ) : ?>
							<input type="password" class="error form-control" placeholder="Contraseña *" value="" name="password" />
							<p class="mensaje-error"><?=$errores["password"]?></p>
						<?php else : ?>
							<input type="password" class="form-control" placeholder="Contraseña *" value="" name="password" />
						<?php endif; ?>
					</div>
					<div class="form-group">
						<?php if ( isset($errores["cpassword"]) ) : ?>
							<input type="password" class="error form-control" placeholder="Confirmar Contrasenia *" value="" name="cpassword" />
							<p class="mensaje-error"><?=$errores["cpassword"]?></p>
						<?php else : ?>
							<input type="password" class="form-control" placeholder="Confirmar Contraseña *" value="" name="cpassword" />
						<?php endif; ?>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Tu Email *" value="" name="email" />
					</div>
					<div class="form-group btn-foto">
						<input type="file" id="img" style="display:none;" value="Avatar" name="avatar" />
						<label for="img" class = "btn btn-secondary ">Subir foto</label>
					</div>
					<input type="submit" class="btn btn-primary"  value="Registrarse"/>
				</form>
			</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
