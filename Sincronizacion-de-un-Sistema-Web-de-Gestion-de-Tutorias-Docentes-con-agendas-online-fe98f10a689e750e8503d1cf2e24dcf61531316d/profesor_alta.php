<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>profesor_alta</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">

</head>

<script>
	function todayDate() {
		var d = new Date();
		var n = d.getFullYear() + "  ";
		return document.getElementById("date").innerHTML = n;
	}
</script>

<body onload="todayDate()">

	<header>

		<a href="https://www.upm.es/" target="_blank"><img src="imagenes/Logo_UPM.png"></a>
		<a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a>


	</header>

	<div class="titulo">
		<h1>Sistema de reserva de tutorías</h1>
	</div>

	<div class="main-container">

		<h3 class="generalseparator marineblue">Rellene los campos a continuación para completar su registro.</h3>

		<form action="recibir_profesor_alta.php" method="POST" class="generalseparator">
			<p>
				<b>Nombre:</b> <input type="text" name="nombre" required></br>
				<b>Apellidos:</b> <input type="text" name="apellido" required></br>
				<b>Correo:</b> <input type="text" name="mail" required>
				<select name="tipo_mail" required>
					<option selected value=""> Elige un dominio </option>
					<option value="@upm.es">@upm.es</option>
					<option value="@fi.upm.es">@fi.upm.es</option>
				</select></br>
				<b>Despacho:</b> <input type="text" name="Despacho"></br>
				<b>Contraseña:</b> <input type="password" name="password1" required minlength=8></br>
				<b>Confirmar contraseña:</b> <input type="password" name="password2" required minlength=8></br>
			</p>
			<input type="submit" name="Registrarse" value="Registrarse" class="functionality-button generalseparator">
		</form>

	</div>

	<div class="backandforthbuttons">

		<form action="Alumno_profesor_alta.php" method="POST" class="backbutton">
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>
</body>

</html>