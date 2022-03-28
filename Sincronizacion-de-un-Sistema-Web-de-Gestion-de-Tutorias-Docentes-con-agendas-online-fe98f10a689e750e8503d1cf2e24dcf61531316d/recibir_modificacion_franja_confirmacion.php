<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_modificacion_franja_confirmacion</title>
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
				$recibir_modificacion_franja = "recibir_modificacion_franja.php";
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
				// $recibir_modificacion_franja = "Login.php";
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
		// $recibir_modificacion_franja = "Login.php";
		// $Profesor_Consulta_Franjas = "Login.php";
	}

	?>

	<div class="main-container">

		<h3 class="generalseparator red">¿Realmente quiere modificar esta Franja?</h3>

		<?php
		$idfranja = $_POST["idfranja"];
		$sql = "SELECT * FROM `franja_disponibilidad` WHERE (`idfranja` = '" . $idfranja . "');";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql) as $row) {
			$numero_slots = $row['numero_slots'];
		}
		$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = '" . $idfranja . "' AND `disponible` = '1';";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD2');
		foreach ($con->query($sql) as $row2) {
			$numero_slots_disp = mysqli_num_rows($result);
		}
		if (($row["hora"] == $_POST["hora"] &&
			$row["minutos"] == $_POST["minutos"] &&
			$row["duracion_slots"] == $_POST["duracion_slots"] &&
			$row["ubicacion"] == $_POST["ubicacion"] &&
			$row["numero_slots"] < $_POST["numero_slots"])) {	//SOLO AUMENTA EL NUMERO DE CITAS

		} else if ($numero_slots > $numero_slots_disp) {

			//Guardamos el numero de citas reservadas para esta franja
			$reservadas = $numero_slots - $numero_slots_disp;

		?>
			<p class="generalseparator black"><b>Esta Franja de Disponibilidad dispone de <span class="marineblue"> <?php echo $reservadas; ?></span>
					<?php if ($reservadas == 1) { ?> <span class="green">cita programada</span> <?php } ?>
					<?php if ($reservadas > 1) { ?> <span class="green">citas programadas</span> <?php } ?>
			</p>

			<p class="black"><b>Por lo que de modificar esta última, también se <span class="red"> eliminarán</span> las citas que contenga.</b></p>

		<?php

		} else {
		}
		?>
		<form action=<?php echo $recibir_modificacion_franja ?> method="POST" class="generalseparator">
			<input type="hidden" name="hora" value=<?php echo $_POST["hora"] ?>>
			<input type="hidden" name="minutos" value=<?php echo $_POST["minutos"] ?>>
			<input type="hidden" name="dia" value=<?php echo $_POST["dia"] ?>>
			<input type="hidden" name="numero_slots" value=<?php echo $_POST["numero_slots"] ?>>
			<input type="hidden" name="duracion_slots" value=<?php echo $_POST["duracion_slots"] ?>>
			<input type="hidden" name="ubicacion" value=<?php echo $_POST["ubicacion"] ?>>

			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Modificar" value="Sí" class='functionality-button'>
		</form>
		<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="submit" name="Cancelar" value="Cancelar" class='functionality-button'>
		</form>


	</div>


	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>