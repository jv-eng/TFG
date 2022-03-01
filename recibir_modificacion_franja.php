<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_modificacion_franja</title>
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
			if ($result) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $_COOKIE["id_sesion"], $time_click);	//Crear cookie
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
				exit();
				// echo "<b><big>Sesión expirada. Por favor, vuelva a logearse.</big></b>";
				// $Profesor_Consulta_Franjas = "Login.php";
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
	}
	?>

	<div class="main-container">

		<?php

		$id_profesor = $_POST["id"];
		$idfranja = $_POST["idfranja"];

		if ($id_profesor != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}
			$sql = "SELECT * FROM `franja_disponibilidad` WHERE (`idfranja` = '" . $idfranja . "');";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			if ($result && $result->num_rows > 0) {
				foreach ($con->query($sql) as $row) {
				}
			}
			switch ($row["tipo_citas"]) {
				case 1:
					$tipo = "Tutoria";
					break;
				case 2:
					$tipo = "Correccion de examenes";
					break;
				default:
					$tipo = "Correccion de practicas";
					break;
			}

			$numero_slots = $_POST["numero_slots"];
			$hora = $_POST["hora"];
			$minutos = $_POST["minutos"];
			if ($minutos == 0) $minutos = "00";
			if ($minutos == 5) $minutos = "05";
			$duracion_slots = $_POST["duracion_slots"];
			$tipo_citas = $row['tipo_citas'];
			$asignatura = $row['asignatura'];

			if (
				$row["hora"] == $_POST["hora"] &&
				$row["minutos"] == $_POST["minutos"] &&
				$row["duracion_slots"] == $_POST["duracion_slots"] &&
				$row["ubicacion"] == $_POST["ubicacion"] &&
				$row["numero_slots"] < $numero_slots
			) {

				$sql = "UPDATE `franja_disponibilidad` SET `hora` = '" . $_POST["hora"] . "', `minutos` = '" . $_POST["minutos"] . "', `duracion_slots` = '" . $_POST["duracion_slots"] . "', `dia` = '" . $_POST["dia"] . "', `numero_slots` = '" . $_POST["numero_slots"] . "', `ubicacion` = '" . $_POST["ubicacion"] . "' WHERE `idfranja` = " . $_POST["idfranja"] . ";";
				$result1 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD11');

				$citas_diferencia = $numero_slots - $row["numero_slots"];

				//Aquí hay que recalcular la hora de la última cita y sumarle su duración para insertar a esa hora las siguientes. 

				$sql = "SELECT `hora`,`minutos` FROM `slot` WHERE `id_franja_disponibilidad`= '" . $_POST["idfranja"] . "' AND `id_slot_posicion` = (SELECT MAX(id_slot_posicion) FROM `slot` WHERE `id_franja_disponibilidad`= '" . $_POST["idfranja"] . "');";
				$result2 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD12');
				if ($result2 && $result2->num_rows > 0) {
					foreach ($con->query($sql) as $row2) {
						$hora_ultima = $row2["hora"];
						$minutos_ultima = $row2["minutos"];
						if ($minutos_ultima == 0) $minutos_ultima = "00";
						if ($minutos_ultima == 5) $minutos_ultima = "05";
					}
				}

				$minutos_ultima = $minutos_ultima + $duracion_slots;
				if ($minutos_ultima >= 60) {
					$hora_ultima = $hora_ultima + 1;
					if ($hora_ultima == 24) {
						$hora_ultima = "00";
					}
					$minutos_ultima = $minutos_ultima - 60;
					if ($minutos_ultima < 10) {
						$minutos_ultima = "05";
					}
				}

				while ($citas_diferencia > 0) {
					$sql = "INSERT INTO `slot` (`id_slot_posicion`, `id_franja_disponibilidad`, `id_alumno_fk`, `hora`, `minutos`, `duracion`, `dia`, `disponible`, `comentarios_alumno`) 
									VALUES (NULL, '" . $_POST["idfranja"] . "', NULL, '" . $hora_ultima . "', '" . $minutos_ultima . "', '" . $_POST["duracion_slots"] . "', '" . $_POST["dia"] . "', '1', NULL)";
					$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
					$citas_diferencia--;
					$minutos_ultima = $minutos_ultima + $duracion_slots;
					if ($minutos_ultima >= 60) {
						$hora_ultima = $hora_ultima + 1;
						if ($hora_ultima == 24) {
							$hora_ultima = "00";
						}
						$minutos_ultima = $minutos_ultima - 60;
						if ($minutos_ultima < 10) {
							$minutos_ultima = "05";
						}
					}
				}
			} else {

				$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = '" . $_POST["idfranja"] . "' AND `disponible` = '0';";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				foreach ($con->query($sql) as $row) {
					$sql = "SELECT * FROM `alumno` WHERE `idalumno` = '" . $row["id_alumno_fk"] . "';";
					$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
					foreach ($con->query($sql) as $row1) {
					}
					$idfranja = $row["id_franja_disponibilidad"];
					$sql = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja` = '" . $idfranja . "';";  // FD
					$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
					foreach ($con->query($sql) as $row2) {
					}
					$fecha = date("Y-m-d");
					$hora_notif = date("H");
					$minutos_notif = date("i");
					$sql = "INSERT INTO `notificaciones_alumno` (`id_notificaciones_alumno`, `id_alumno_fk`, `mail_alumno`, `asignatura`, `tipo_citas`, `motivo`, `fecha_cita`, `hora_cita`, `minutos_cita`, `fecha_notif`, `hora_notif`, `minutos_notif`) 
														VALUES ('', '" . $row["id_alumno_fk"] . "', '" . $row1["mail_alumno"] . "', '" . $row2["asignatura"] . "', '" . $row2["tipo_citas"] . "', 'modificación de franja de disponibilidad', '" . $row["dia"] . "', '" . $row["hora"] . "', '" . $row["minutos"] . "', '" . $fecha . "', '" . $hora_notif . "', '" . $minutos_notif . "')";
					$result = mysqli_query($con, $sql) or die('Error al enviar notificación');
				}

				$sql = "UPDATE `franja_disponibilidad` SET `hora` = '" . $_POST["hora"] . "', `minutos` = '" . $_POST["minutos"] . "', `duracion_slots` = '" . $_POST["duracion_slots"] . "', `dia` = '" . $_POST["dia"] . "', `numero_slots` = '" . $_POST["numero_slots"] . "', `ubicacion` = '" . $_POST["ubicacion"] . "' WHERE `idfranja` = " . $_POST["idfranja"] . ";";
				$result1 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD21');
				$sql = "DELETE FROM `slot` WHERE `id_franja_disponibilidad` = '" . $_POST["idfranja"] . "';";
				$result2 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD22');

				while ($numero_slots > 0) {
					$sql = "INSERT INTO `slot` (`id_slot_posicion`, `id_franja_disponibilidad`, `id_alumno_fk`, `hora`, `minutos`, `duracion`, `dia`, `disponible`, `comentarios_alumno`) 
									VALUES (NULL, '" . $_POST["idfranja"] . "', NULL, '" . $hora . "', '" . $minutos . "', '" . $_POST["duracion_slots"] . "', '" . $_POST["dia"] . "', '1', NULL)";
					$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
					$numero_slots--;
					$minutos = $minutos + $duracion_slots;
					if ($minutos >= 60) {
						$hora = $hora + 1;
						if ($hora == 24) {
							$hora = "00";
						}
						$minutos = $minutos - 60;
						if ($minutos < 10) {
							$minutos = "05";
						}
					}
				}
			}
			if ($result1 && $result2) {

		?>

				<p class="generalseparator marineblue"> <b>La modificación de la Franja de Disponibilidad se ha realizado correctamente.</b></p>

				<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="generalseparator">
					<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
					<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
					<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
				</form>


		<?php

			}
			mysqli_close($con);
		}
		?>



	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>

	</div>


	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>