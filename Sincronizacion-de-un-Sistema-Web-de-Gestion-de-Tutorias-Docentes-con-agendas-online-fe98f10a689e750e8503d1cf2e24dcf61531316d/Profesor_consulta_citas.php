<html lang="es">
<!--	Author: Juan Borrero Carrón y Mohammad Saeid Shamkhali		-->

<head>
	<title>Profesor Consulta de Citas</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css">
	<link rel="stylesheet" href="./calendarstyle.css">

	<script>
		function todayDate() {
			var d = new Date();
			var n = d.getFullYear() + "  ";
			return document.getElementById("date").innerHTML = n;
		}
	</script>

	<script>
		document.addEventListener('DOMContentLoaded',
			function() {
				var calendarEl = document.getElementById('fullcalendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'timeGridWeek',
					firstDay: 1,
					slotMinTime: "07:00:00",
					slotMaxTime: "20:00:00",
					nowIndicator: true,
					events: 'loadProfessorEvents.php',
					timeZone: 'Europe/Madrid',
					locale: "es",
					eventTimeFormat: { // like '14:30:00'
						hour: '2-digit',
						minute: '2-digit',
						hour12: false
					},
					slotLabelFormat: {
						hour: '2-digit',
						minute: '2-digit',
						omitZeroMinute: false,
						meridiem: 'long'
					},
					themeSystem: 'standard',
					height: "765px"

				});
				calendar.render();
			});
	</script>


</head>

<body onload="todayDate()">

	<header>

		<a href="https://www.upm.es/" target="_blank"><img src="imagenes/Logo_UPM.png"></a>
		<a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a>


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
			$sql = "SELECT id_sesion FROM `session` WHERE (`mail_profesor` = '" . $_COOKIE["mail"] . "');";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			$row = mysqli_fetch_array($result);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Profesor_menu = "Profesor_menu.php";
				$Profesor_Consulta_Franjas = "Profesor_Consulta_Franjas.php";
			} else {
	?>
				<div class="main-container">
					<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

					<form action="Login.php" method="POST" class="generalseparator">
						<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
					</form>

				</div>

		<?php
				// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
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
		// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
		// $Profesor_menu = "Login.php";
	}
	?>

	<div class="main-container">

		<h2 class="green generalseparator titulofullcalendar">Calendario de citas</h2>


		<?php

		$fechaActual = date('Y-m-d');
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		$resultados = false;
		$resultados2 = false;
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		$sql = "SELECT `id_profesor` FROM `profesor` WHERE `mail` = '" . $_COOKIE["mail"] . "';";

		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql) as $row) {
			$id_profesor = $row["id_profesor"];
		}
		$sql = "SELECT * FROM `franja_disponibilidad` WHERE `id_profesor_fk` = '" . $id_profesor . "' AND `dia` = CURDATE() ORDER BY `hora` ASC;";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

		foreach ($con->query($sql) as $row1) {

			$idfranja = $row1["idfranja"];
			$sql2 = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad`= '" . $idfranja . "' AND `disponible` = '0';";
			$result = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');

			foreach ($con->query($sql2) as $row2) {
				$id_alumno = $row2['id_alumno_fk'];
				$sql3 = "SELECT * FROM `alumno` WHERE `idalumno`= '" . $id_alumno . "';";
				$result = mysqli_query($con, $sql3) or die('Error en la consulta a la BDD');
				foreach ($con->query($sql3) as $row3) {
					$resultados = true;
					$idslot = $row2["id_slot_posicion"];
					$tipo_citas = $row1['tipo_citas'];
					$asignatura = $row1['asignatura'];
					$dia = $row2['dia'];
					$hora = $row2['hora'];
					$minutos = $row2['minutos'];
					if ($minutos == 0) $minutos = "00";
					if ($minutos == 5) $minutos = "05";
					$duracion = $row2['duracion'];
					$ubicacion = $row1['ubicacion'];
					$notas = $row2['comentarios_alumno'];
					$nombre_alumno = $row3['nombre_alumno'];
					$apellidos_alumno = $row3['apellidos_alumno'];
					// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
					// print "<b>" . $asignatura . "</b>\t";
					// echo "<br>";
					// print "Día: " . $dia . "\t";
					// print "Hora: " . $hora . ":" . $minutos . "\t";
					// echo "<br>";
					// print "Duración: " . $duracion . " mins\t";
					// print "Ubicación: " . $ubicacion . "\n";
					// echo "<br>";
					// print "Nombre del alumno: " . $nombre_alumno . " " . $apellidos_alumno . "\t";
					// echo "<br>";
					// print "Notas: " . $notas . "\n";
					// echo "<br></br>";
				}
			}
		}
		if (!$resultados) {
			// echo "No tiene aún citas programadas para el día de hoy.";
			// echo "<br></br>";
		}

		$sql = "SELECT * FROM `franja_disponibilidad` WHERE `id_profesor_fk` = '" . $id_profesor . "' AND `dia` > CURDATE()  ORDER BY `dia`;;";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

		foreach ($con->query($sql) as $row1) {

			$idfranja = $row1["idfranja"];
			$sql2 = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad`= '" . $idfranja . "' AND `disponible` = '0';";
			$result = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');

			foreach ($con->query($sql2) as $row2) {
				$id_alumno = $row2['id_alumno_fk'];
				$sql3 = "SELECT * FROM `alumno` WHERE `idalumno`= '" . $id_alumno . "';";
				$result = mysqli_query($con, $sql3) or die('Error en la consulta a la BDD');
				foreach ($con->query($sql3) as $row3) {
					$resultados2 = true;
					$idslot = $row2["id_slot_posicion"];
					$tipo_citas = $row1['tipo_citas'];
					$asignatura = $row1['asignatura'];
					$dia = $row2['dia'];
					$hora = $row2['hora'];
					$minutos = $row2['minutos'];
					if ($minutos == 0) $minutos = "00";
					if ($minutos == 5) $minutos = "05";
					$duracion = $row2['duracion'];
					$ubicacion = $row1['ubicacion'];
					$notas = $row2['comentarios_alumno'];
					$nombre_alumno = $row3['nombre_alumno'];
					$apellidos_alumno = $row3['apellidos_alumno'];
					// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
					// print "<b>" . $asignatura . "</b>\t";
					// echo "<br>";
					// print "Día: " . $dia . "\t";
					// print "Hora: " . $hora . ":" . $minutos . "\t";
					// echo "<br>";
					// print "Duración: " . $duracion . " mins\t";
					// print "Ubicación: " . $ubicacion . "\n";
					// echo "<br>";
					// print "Nombre del alumno: " . $nombre_alumno . " " . $apellidos_alumno . "\t";
					// echo "<br>";
					// print "Notas: " . $notas . "\n";
					// echo "<br></br>";
				}
			}
		}
		mysqli_close($con);

		?>

		<div id="fullcalendar"></div>

		<hr class="generalseparator">

		<h3 class="generalseparator black"><b>¿Desea modificar o eliminar una franja?</h3>

		<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Ver Mis Franjas" value="Ver Mis Franjas" class="functionality-button">
		</form>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Profesor_menu ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>


		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		</form>

	</div>

</body>

</html>