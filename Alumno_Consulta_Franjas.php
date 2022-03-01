<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_consulta_Franjas</title>
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
				$Alumno_Seleccionar_Slot = "Alumno_Seleccionar_Slot.php";
				$Alumno_Buscador = "Alumno_Buscador.php";
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
				// $Alumno_Seleccionar_Slot = "Login.php";
				// $Alumno_Buscador = "Login.php";
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
		// $Alumno_Seleccionar_Slot = "Login.php";
		// $Alumno_Buscador = "Login.php";
	}
	?>
	<div class="main-container">

		<div class="gridinf-container">

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

			$sql = "SELECT * FROM `franja_disponibilidad` WHERE `id_profesor_fk`= '" . $_POST["id_profesor"] . "' AND `dia` >= CURDATE() ORDER BY `dia`,`hora`,`minutos`;";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row) {
			?>
				<?php
				$resultados = true;
				$idfranja = $row["idfranja"];
				$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = '" . $idfranja . "' AND `disponible` = '1';";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD2');
				foreach ($con->query($sql) as $row2) {
					$numero_slots_disp = mysqli_num_rows($result);
				}
				if (!isset($numero_slots_disp)) $numero_slots_disp = 0;
				$tipo_citas = $row['tipo_citas'];
				$asignatura = $row['asignatura'];
				$dia = $row['dia'];
				$hora = $row['hora'];
				$minutos = $row['minutos'];
				if ($minutos == 0) $minutos = "00";
				if ($minutos == 5) $minutos = "05";
				$duracion_slots = $row['duracion_slots'];
				$numero_slots = $row['numero_slots'];
				$ubicacion = $row['ubicacion'];

				?>
				<div class="franja-container">

					<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
					<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
					<p> <span class="black"><b>Duracion de citas: </b></span> <?php echo $duracion_slots ?> mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>
					<p> <span class="black"><b>Número de citas: </b></span><?php echo $numero_slots ?> <span class="black"><b> ; Número de citas libres: </b></span><?php echo $numero_slots_disp; ?></p>

					<?php

					?>

					<form action=<?php echo $Alumno_Seleccionar_Slot ?> method="POST" class="generalseparator">
						<input type="hidden" name="id_profesor" value=<?php echo $id_profesor ?>>
						<input type="hidden" name="idfranja" value=<?php echo $idfranja ?>>
						<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
						<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
						<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
						<input type="hidden" name="ubicacion" value=<?php echo $ubicacion ?>>
						<input type="submit" name="Seleccionar" value="Seleccionar" class="functionality-button">
					</form>
				</div>
			<?php
			}
			mysqli_close($con);
			if (!$resultados) {

			?>	
			<!-- PARRAFOS VACIOS PARA CENTRAR EL TEXTO EN EL GRID -->
				<p></p>
				<div>
					<h3 class="generalseparator marineblue">Este profesor no tiene aún franjas publicadas.</h3>
					<p class="generalseparator black"><b>Vuelva a consultarlas más tarde.</b></p>
				</div>
				<p></p>

			<?php

			}
			?>

		</div>
	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Alumno_Buscador ?> method="POST" class="backbutton">
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