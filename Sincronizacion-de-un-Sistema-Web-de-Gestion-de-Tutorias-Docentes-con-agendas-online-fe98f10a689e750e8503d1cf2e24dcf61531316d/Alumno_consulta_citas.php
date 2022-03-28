<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_consulta_citas</title>
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
			$sql = "SELECT id_sesion FROM `session` WHERE (`mail_alumno` = '" . $_COOKIE["mail"] . "');";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			$row = mysqli_fetch_array($result);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Alumno_Eliminar_Cita_Confirmacion = "Alumno_Eliminar_Cita_Confirmacion.php";
				$Alumno_Menu = "Alumno_Menu.php";
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
				// $Alumno_Eliminar_Cita_Confirmacion = "Login.php";
				// $Alumno_Menu = "Login.php";
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
		// $Alumno_Eliminar_Cita_Confirmacion = "Login.php";
		// $Alumno_Menu = "Login.php";
	}
	?>

	<div class="main-container">

		<div class="upper-container">

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
			$sql = "SELECT `idalumno` FROM `alumno` WHERE `mail_alumno` = '" . $_COOKIE["mail"] . "';";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row) {
				$id_alumno = $row["idalumno"];
			}
			$sql = "SELECT * FROM `slot` WHERE `id_alumno_fk`= '" . $id_alumno . "' AND `disponible` = '0' AND `dia` = CURDATE() ORDER BY `hora` ASC;";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

			?>
			<h2 class="generalseparator green">Citas programadas para hoy:</h2>

			<div class="gridinf-container">

				<?php

				foreach ($con->query($sql) as $row1) {

					$idfranja = $row1["id_franja_disponibilidad"];
					$sql2 = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja` = '" . $idfranja . "' AND `dia` = CURDATE();";
					$result = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');
					foreach ($con->query($sql2) as $row2) {

						$resultados = true;
						$idslot = $row1["id_slot_posicion"];
						$tipo_citas = $row2['tipo_citas'];
						$asignatura = $row2['asignatura'];
						$dia = $row1['dia'];
						$hora = $row1['hora'];
						$minutos = $row1['minutos'];
						if ($minutos == 0) $minutos = "00";
						if ($minutos == 5) $minutos = "05";
						$duracion = $row1['duracion'];
						$ubicacion = $row2['ubicacion'];

						// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
						// print "<b>" . $asignatura . "</b>\t";
						// echo "<br>";
						// print "Día: " . $dia . "\t";
						// print "Hora: " . $hora . ":" . $minutos . "\t";
						// echo "<br>";
						// print "Duración: " . $duracion . " mins.\t";
						// print "Ubicación: " . $ubicacion . "\n";

				?>

						<div class="cita-container">
							<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
							<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
							<p> <span class="black"><b>Duración: </b></span><?php echo $duracion ?> mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>

							<form action=<?php echo $Alumno_Eliminar_Cita_Confirmacion ?> method="POST" class="generalseparator">
								<input type="hidden" name="idfranja" value=<?php echo $idfranja ?>>
								<input type="hidden" name="idslot" value=<?php echo $idslot ?>>
								<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
								<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
								<input type="submit" name="Eliminar" value="Eliminar" class="functionality-button">
							</form>

						</div>

					<?php
					}
				}
				if (!$resultados) {

					?>
					<!-- Parrafo vacio para hacer que el texto esté en el centro -->
					<p></p> 
					<p class="generalseparator black"><b>No tiene aún citas programadas para el día de hoy.</b></p>
					<p></p>
					<!-- Parrafo vacio para hacer que el texto esté en el centro -->
				<?php

				}
				?>

			</div>

		</div>

		<div class="lower-container">

			<?php
			$sql = "SELECT * FROM `slot` WHERE `id_alumno_fk`= '" . $id_alumno . "' AND `disponible` = '0' AND `dia` > CURDATE() ORDER BY `dia`,`hora`,`minutos`;";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

			?>

			<h2 class="generalseparator green">Citas programadas para más adelante: </h2>

			<div class="gridinf-container">

				<?php

				foreach ($con->query($sql) as $row1) {

					$idfranja = $row1["id_franja_disponibilidad"];
					$sql2 = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja` = '" . $idfranja . "' AND `dia` > CURDATE();";
					$result = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');

					foreach ($con->query($sql2) as $row2) {

						$resultados2 = true;
						$idslot = $row1["id_slot_posicion"];
						$tipo_citas = $row2['tipo_citas'];
						$asignatura = $row2['asignatura'];
						$dia = $row1['dia'];
						$hora = $row1['hora'];
						$minutos = $row1['minutos'];
						if ($minutos == 0) $minutos = "00";
						if ($minutos == 5) $minutos = "05";
						$duracion = $row1['duracion'];
						$ubicacion = $row2['ubicacion'];
						// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
						// print "<b>" . $asignatura . "</b>\t";
						// echo "<br>";
						// print "Día: " . $dia . "\t";
						// print "Hora: " . $hora . ":" . $minutos . "\t";
						// echo "<br>";
						// print "Duración: " . $duracion . " mins.\t";
						// print "Ubicación: " . $ubicacion . "\n";
						// echo "<br>";
				?>
						<div class="cita-container">
							<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
							<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
							<p> <span class="black"><b>Duración: </b></span><?php echo $duracion ?> mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>

							<form action=<?php echo $Alumno_Eliminar_Cita_Confirmacion ?> method="POST" class="generalseparator">
								<input type="hidden" name="idfranja" value=<?php echo $idfranja ?>>
								<input type="hidden" name="idslot" value=<?php echo $idslot ?>>
								<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
								<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
								<input type="submit" name="Eliminar" value="Eliminar" class="functionality-button">
							</form>

						</div>

					<?php
					}
				}
				mysqli_close($con);
				if (!$resultados2) {

					?>
					<p></p>
					<p class="generalseparator black"><b>No tiene aún citas programadas para para más adelante.</b></p>
					<p></p>

				<?php

				}
				?>

			</div>

		</div>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Alumno_Menu ?> method="POST" class="backbutton">
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