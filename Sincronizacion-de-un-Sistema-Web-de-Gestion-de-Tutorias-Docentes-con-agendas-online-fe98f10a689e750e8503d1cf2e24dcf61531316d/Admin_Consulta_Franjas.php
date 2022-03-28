<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Admin_consulta_Franjas</title>
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
		<form action=<?php echo "Menu_admin.php" ?> method="POST">
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

			if ($row == []) {
	?>
				<div class="main-container">
					<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse.</h3>

					<form action="Login.php" method="POST" class="generalseparator">
						<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
					</form>

				</div>

			<?php
				exit();
			}

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Menu_admin = "Menu_admin.php";
				$Admin_consulta_citas2 = "Admin_consulta_citas2.php";
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
				// $Menu_admin = "Login.php";
				// $Admin_consulta_citas2 = "Login.php";
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
		// $Menu_admin = "Login.php";
		// $Admin_consulta_citas2 = "Login.php";
	}
	?>

	<div class="main-container">

		<h2 class="generalseparator green">Calendario de franjas de disponibilidad</h2>

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

			$sql = "SELECT * FROM `franja_disponibilidad` ORDER BY `dia`,`hora`,`minutos`;";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row) {
				$resultados = true;
				$sql = "SELECT `tbuscar` FROM `profesor` WHERE `id_profesor` = '" . $row["id_profesor_fk"] . "'";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
				foreach ($con->query($sql) as $row2) {
					$nombre = $row2["tbuscar"];
				}
				$idfranja = $row["idfranja"];
				$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = '" . $idfranja . "' AND `disponible` = '1';";
				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD2');
				foreach ($con->query($sql) as $row2) {
					$numero_slots_disp = mysqli_num_rows($result);
				}
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

				// print "<b><big>" . $tipo_citas . ":  </big></b>\t";
				// print "<b>" . $asignatura . "</b>\t";
				// echo "<br>";
				// print "Día: " . $dia . "\t";
				// print "Hora: " . $hora . ":" . $minutos . "\t";
				// echo "<br>";
				// print "Duración de citas: " . $duracion_slots . "; \t";
				// print "Ubicación: " . $ubicacion . "\n";
				// echo "<br>";
				// print "Número de citas: " . $numero_slots . "; \t";
				// print "Número de citas libres: " . $numero_slots_disp . "\t";
				// echo "<br>";
				// print "Profesor: " . $nombre . "\n";
				// echo "<br>";

			?>

				<div class="franja-container">

					<h3 class="generalseparator marineblue"><?php echo $tipo_citas ?> : <?php echo $asignatura ?></h3>
					<p> <span class="black"><b>Día: </b></span><?php echo $dia ?> <span class="black"><b>Hora: </b></span> <?php echo $hora ?>:<?php echo $minutos ?> </p>
					<p> <span class="black"><b>Duracion de citas: </b></span> <?php echo $duracion_slots ?>mins <span class="black"><b>Ubicación: </b></span><?php echo $ubicacion ?></p>
					<p> <span class="black"><b>Número de citas: </b></span><?php echo $numero_slots ?><span class="black"><b> ; Número de citas libres: </b></span><?php echo $numero_slots_disp; ?></p>
					<p> <span class="black"><b>Profesor: </b></span><?php echo $nombre ?></p>


					<form action=<?php echo $Admin_consulta_citas2 ?> method="POST" class="generalseparator">
						<input type="hidden" name="idfranja" value=<?php echo $idfranja ?>>
						<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
						<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
						<input type="submit" name="Consultar citas" value="Consultar Citas" class="functionality-button">
					</form>
				</div>

			<?php
			}
			mysqli_close($con);
			?>

		</div>

		<?php

		if (!$resultados) {

		?>
			<p class="generalseparator black"><b>No existen aún franjas publicadas en el Sistema.</b></p>

		<?php

		}
		?>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Menu_admin ?> method="POST" class="backbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Volver" value="Volver">
		</form>

		<form action="Login.php" method="POST">
			<input type="submit" name="Logout" value="< Logout" class="logoutbutton">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		</form>
	</div>


	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>