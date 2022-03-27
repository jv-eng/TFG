<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Login</title>
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

		<div align="left"><a href="https://www.upm.es/" target="_blank"><img src="imagenes/logo_copia.png"></a></div>

		<div align="right"><a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a></div>

	</header>


	<div class="titulo">
		<h1>Sistema de reserva de tutorías</h1>
	</div>

	<div class="grid-container">

		<?php
		if (isset($_COOKIE["mail"]) && isset($_COOKIE["id_sesion"])) {
			$mail = $_COOKIE["mail"];
			setcookie("mail", "", time() - 84600);	//Eliminar cookie	 
			setcookie("id_sesion", "", time() - 84600);	//Eliminar cookie	
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}

			$query = $con->prepare("DELETE FROM `session` WHERE `mail_profesor`= ? OR `mail_alumno` = ?;");
  			mysqli_stmt_bind_param($query, "ss", $mail, $mail);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			mysqli_stmt_close($query);
			mysqli_close($con);
		} else if (isset($_POST["mail"])) {
			$mail = $_POST["mail"];
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}

			$query = $con->prepare("DELETE FROM `session` WHERE `mail_profesor`= ? OR `mail_alumno` = ?;");
  			mysqli_stmt_bind_param($query, "ss", $mail, $mail);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			mysqli_stmt_close($query);
			mysqli_close($con);
		} else {
		}
		?>

		<div class="etsiinf-container">

			<h2 class="generalseparator marineblue">Escuela Técnica Superior de Ingenieros Informaticos</h2>

			<img src="imagenes/logo_etsiinf_transparente.png" alt="">

			<form action="https://www.fi.upm.es/" target="_blank">
				<input type="submit" value="Ver sitio web" class="functionality-button" />
			</form>

		</div>

		<div class="login-container">

			<h3 class="generalseparator marineblue">Bienvenido a la plataforma de reserva de tutorías</h3>

			<div class="portallogin">

				<form action="recibir_login_general.php" method="POST">

					<p>Correo: <input type="text" name="mail" required></br></p>
					<p>Password: <input type="password" name="password" required></br></p>

					<input type="submit" name="Entrar" value="Iniciar Sesion" class="functionality-button">
				</form>

			</div>

			<hr class="marineblue">

			<div class="registro">

				<p class="generalseparator marineblue"><b>¿Aún no tienes cuenta? Pulsa aquí para crear una.</b></p>

				<form action="Alumno_profesor_alta.php" method="POST" class="generalseparator">

					<input type="submit" name="Registrarse" value="Registrarse" class="functionality-button">

				</form>

			</div>

		</div>

		<div class="upm-container">

			<h2 class="generalseparator marineblue">Universidad Politécnica de Madrid</h2>

			<img src="imagenes/logo_copia.png" alt="">

			<form action="https://www.upm.es/" target="_blank">
				<input type="submit" value="Ver sitio web" class="functionality-button" />
			</form>

		</div>
	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>