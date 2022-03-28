<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_Seleccionar_Slot</title>
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
		<form action=<?php echo "Alumno_Menu.php" ?> method="POST">
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
			if ($result) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $_COOKIE["id_sesion"], $time_click);	//Crear cookie
				$Alumno_Crear_Cita = "Alumno_Crear_Cita.php";
				$recibir_busqueda_profesor = "recibir_busqueda_profesor.php";
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
				// $Alumno_Crear_Cita = "Login.php";
				// $recibir_busqueda_profesor = "Login.php";
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
		// $Alumno_Crear_Cita = "Login.php";
		// $recibir_busqueda_profesor = "Login.php";
	}
	?>

	<div class="cita-container">

		<h2 class="green">Horas de disponibilidad para esta Franja</h2>


	</div>

	<div class="horasalumno-container">

		<?php
		$resultados = false;
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		$id_profesor = $_POST["id"];

		$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad`= '" . $_POST["idfranja"] . "' AND `disponible` = '1' ";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql) as $row) {
		?>
			<?php
			$resultados = true;
			$id_slot_posicion = $row["id_slot_posicion"];
			$dia = $row['dia'];
			$hora = $row['hora'];
			$minutos = $row['minutos'];
			if ($minutos == 0) $minutos = "00";
			if ($minutos == 5) $minutos = "05";
			$duracion_slots = $row['duracion'];
			$ubicacion = $_POST["ubicacion"];

			// print "Día: " . $dia . "\t";
			// print "Hora: " . $hora . ":" . $minutos . "\t";
			// echo "<br>";
			// print "Duración de la cita: " . $duracion_slots . "\t";
			// print "Ubicación: " . $ubicacion . "\n";
			// echo "<br>";

			?>
			<!-- <div class="slot"> -->

			<div class="franja-container">

				<p class=><span class="black"><b>Día: </b></span> <?php echo $dia ?></p>
				<p><span class="black"><b>Hora: </b></span><?php echo $hora ?>:<?php echo $minutos ?> </p>
				<p><span class="black"><b>Duración de la cita: </b></span><?php echo $duracion_slots ?> mins</p>
				<p><span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>



				<form action=<?php echo $Alumno_Crear_Cita ?> method="POST" class="generalseparator">
					<input type="hidden" name="id_slot_posicion" value=<?php echo $id_slot_posicion ?>>
					<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
					<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
					<input type="hidden" name="id_profesor" value=<?php echo $id_profesor ?>>
					<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
					<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
					<input type="hidden" name="ubicacion" value=<?php echo $_POST["ubicacion"] ?>>
					<input type="submit" name="Reservar" value="Reservar" class="functionality-button">
				</form>

			</div>


			<!-- </div> -->

		<?php
		}
		mysqli_close($con);
		if (!$resultados) {

		?>

			<p class="generalseparator red"><b>No hay citas disponibles para esta Franja.</b></p>

		<?php

		}
		?>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $recibir_busqueda_profesor ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id_profesor" value=<?php echo $_POST["id_profesor"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
			<input type="hidden" name="ubicacion" value=<?php echo $_POST["ubicacion"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>
	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>