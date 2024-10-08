<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Inicio</title>
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

	<div class="main-container">


		<h2 class="generalseparator marineblue">Bienvenido al sistema de tutorías de la ETSIINF</h2>

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
			echo "<b><big><font color='red'>Su sesión ha expirado. Por favor, vuelva a logearse.</font></big></b>";
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

		<form action="Login.php" method="POST" class="generalseparator">
			<input type="submit" name="Login" value="Login" class="functionality-button">
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>


</html>