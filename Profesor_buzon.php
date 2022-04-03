<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Profesor_Buzon</title>
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
		<form action=<?php echo "Profesor_menu.php" ?> method="POST">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Sistema de reserva de tutorías" class="inicio-button">
		</form>
	</div>
	
	<?php
	// $recordatorio = "";

	if (isset($_COOKIE["mail"]) && isset($_COOKIE["id_sesion"])) {
		if ($_COOKIE["mail"] != "" && $_COOKIE["id_sesion"] != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}

			$query = $con->prepare("SELECT id_sesion FROM `session` WHERE (`mail_profesor` = ?);");
			mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			mysqli_stmt_close($query);

			if ($result) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $_COOKIE["id_sesion"], $time_click);	//Crear cookie
				$Profesor_Consulta_Franjas = "Profesor_Consulta_Franjas.php";
				$Profesor_menu = "Profesor_menu.php";
			} else {
	?>
				<div class="main-container">
					<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

					<form action="Login.php" method="POST" class="generalseparator">
						<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
					</form>

				</div>

		<?php
				exit();
				// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
				// $Profesor_Consulta_Franjas = "Login.php";
				// $Profesor_menu = "Login.php";
			}
		}
	} else {
		?>
		<div class="main-container">
			<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

			<form action="Login.php" method="POST" class="generalseparator">
				<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
			</form>

		</div>

	<?php
		exit();
		// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
		// $Profesor_Consulta_Franjas = "Login.php";
		// $Profesor_menu = "Login.php";
	}
	?>


	<div class="main-container">

		<h2 class="green generalseparator">Buzón de notificaciones</h2>

		<?php
		$resultados = false;
		$resultados2 = false;
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}

		$query = $con->prepare("SELECT * FROM `notificaciones_profesor` WHERE `id_profesor_fk`= ? AND `fecha_notif` >= CURDATE() ORDER BY `fecha_notif`,`hora_notif`,`minutos_notif`;");
		mysqli_stmt_bind_param($query, "i", $_POST["id"]);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_get_result($query);
		mysqli_stmt_close($query);

		foreach ($result as $row) {
			$resultados = true;
			$hora_notif = $row["hora_notif"];
			$minutos_notif = $row["minutos_notif"];
			if ($minutos_notif == 0) $minutos_notif = "00";
			if ($minutos_notif == 5) $minutos_notif = "05";
			$fecha_notif = $row["fecha_notif"];
			$tipo_citas = $row["tipo_citas"];
			$asignatura = $row["asignatura"];
			$fecha_cita = $row["fecha_cita"];
			$hora_cita = $row["hora_cita"];
			$minutos_cita = $row["minutos_cita"];
			if ($minutos_cita == 0) $minutos_cita = "00";
			if ($minutos_cita == 5) $minutos_cita = "05";

			//NUEVO FORMATO PARA LAS NOTIFICACIONES

		?>

			<p class="generalseparator black">
				<b>Su <span class="marineblue"><?php echo $tipo_citas ?> </span> de <span class="marineblue"> <?php echo $asignatura ?> </span>
					del dia <span class="marineblue"><?php echo $fecha_cita ?></span> a las <span class="marineblue"><?php echo $hora_cita ?>:<?php echo $minutos_cita ?></span>
					ha sido <span class="red">cancelada </span> el día <span class="marineblue"><?php echo $fecha_notif ?></span></b>
			</p>

		<?php

		}
		if (!$resultados) {

		?>

			<h3 class="generalseparator marineblue">No tiene notificaciones a día de hoy.</h3>
			<p class="generalseparator black"><b>Vuelva a consultar su buzón más tarde.</b></p>

		<?php

		}
		mysqli_close($con);

		?>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Profesor_menu ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>


		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_COOKIE["mail"] ?>>
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>