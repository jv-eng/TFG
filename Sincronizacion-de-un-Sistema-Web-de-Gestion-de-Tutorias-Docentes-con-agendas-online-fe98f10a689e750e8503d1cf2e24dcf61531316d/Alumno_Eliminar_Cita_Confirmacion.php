<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_Eliminar_Cita_Confirmación</title>
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
				$sql = "SELECT id_sesion FROM `session` WHERE (`mail_alumno` = '" . $_COOKIE["mail"] . "');";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				if ($result) {
					// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
					setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
					$time = time();
					$time_click = $time + 3600;
					setcookie("id_sesion", $_COOKIE["id_sesion"], $time_click);	//Crear cookie
					$Alumno_Eliminar_Cita = "Alumno_Eliminar_Cita.php";
					$Alumno_Consulta_Citas = "Alumno_Consulta_Citas.php";
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
					// $Alumno_Eliminar_Cita = "Login.php";
					// $Alumno_Consulta_Citas = "Login.php";
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
			// $Alumno_Eliminar_Cita = "Login.php";
			// $Alumno_Consulta_Citas = "Login.php";
		}
		?>

		<h3 class="generalseparator red">¿Realmente quiere eliminar esta Cita?</h3>

		<?php
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		$sql = "SELECT * FROM `slot` WHERE (`id_slot_posicion` = '" . $_POST["idslot"] . "');";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result) {
			foreach ($con->query($sql) as $row1) {
				$idslot = $_POST["idslot"];
				$sql = "SELECT * FROM `franja_disponibilidad` WHERE (`idfranja` = '" . $_POST["idfranja"] . "');";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				if ($result) {
					foreach ($con->query($sql) as $row2) {
						$idfranja = $row2["idfranja"];
						$id_profesor_fk = $row2["id_profesor_fk"];
						$tipo_citas = $row2['tipo_citas'];
						$asignatura = $row2['asignatura'];
						$dia = $row1['dia'];
						$hora = $row1['hora'];
						$minutos = $row1["minutos"];
						if ($minutos == 0) $minutos = "00";
						if ($minutos == 5) $minutos = "05";
						$duracion_slots = $row1['duracion'];
						$ubicacion = $row2['ubicacion'];

						// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
						// print "<b>" . $asignatura . "</b>\t";
						// echo "<br>";
						// print "Día: " . $dia . "\t";
						// print "Hora: " . $hora . ":" . $minutos . "\t";
						// echo "<br>";
						// print "Duración de citas: " . $duracion_slots . " mins.\t";
						// print "Ubicación: " . $ubicacion . "\n";

		?>


						<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
						<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
						<p> <span class="black"><b>Duración de la cita: </b></span> <?php echo $duracion_slots ?> mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>

		<?php

					}
				}
			}
		}
		?>
		<form action=<?php echo $Alumno_Eliminar_Cita ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="hidden" name="idslot" value=<?php echo $idslot ?>>
			<input type="hidden" name="dia" value=<?php echo $dia ?>>
			<input type="hidden" name="hora" value=<?php echo $hora ?>>
			<input type="hidden" name="minutos" value=<?php echo $minutos ?>>
			<input type="hidden" name="duracion_slots" value=<?php echo $duracion_slots ?>>
			<input type="hidden" name="id_profesor_fk" value=<?php echo $id_profesor_fk ?>>
			<input type="submit" name="Eliminar" value="Eliminar" class="functionality-button">
		</form>

		<form action=<?php echo $Alumno_Consulta_Citas ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="idslot" value=<?php echo $idslot ?>>
			<input type="submit" name="Cancelar" value="Cancelar" class="functionality-button">

		</form>

	</div>
	<div class="backandforthbuttons">

		<form action=<?php echo $Alumno_Eliminar_Cita ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>

		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		</form>

	</div>
	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>