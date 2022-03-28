<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Profesor_modificar_Franja</title>
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
				$recibir_modificacion_franja_confirmacion = "recibir_modificacion_franja_confirmacion.php";
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
				// $recibir_modificacion_franja_confirmacion = "Login.php";
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
		// $recibir_modificacion_franja_confirmacion = "Login.php";
		// $Profesor_Consulta_Franjas = "Login.php";
	}

	?>

	<div class="main-container">

		<?php

		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		$idfranja = $_POST["idfranja"];

		$sql = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja`= '" . $idfranja . "'";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result && $result->num_rows > 0) {
			foreach ($con->query($sql) as $row) {
				$idfranja = $row["idfranja"];
				$tipo_citas = $row['tipo_citas'];
				$asignatura = $row['asignatura'];
				$dia = $row['dia'];
				$hora = $row['hora'];
				$minutos = $row["minutos"];
				$duracion_slots = $row['duracion_slots'];
				$numero_slots = $row['numero_slots'];
				$ubicacion = $row['ubicacion'];
			}
		}
		?>

		<form action=<?php echo $recibir_modificacion_franja_confirmacion ?> method="POST" class="generalseparator">
			<p>
				<b>Asignatura:</b> <input type="text" name="asignatura" required value="<?php echo $asignatura ?>" disabled></br>
				<b>Tipo:<b> <input type="text" name="tipo_citas" required value="<?php echo $tipo_citas ?>" disabled></br>
						<b>Hora de inicio:</b> <select name="hora" required>
							<option selected value=<?php echo $hora ?>><?php echo $hora ?></option>
							<option value="00">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
						</select>
						<select name="minutos" required>
							<option selected value=<?php echo $minutos ?>><?php echo $minutos ?></option>
							<option value="00">00</option>
							<option value="05">05</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="25">25</option>
							<option value="30">30</option>
							<option value="35">35</option>
							<option value="40">40</option>
							<option value="45">45</option>
							<option value="50">50</option>
							<option value="55">55</option>
						</select></br>
						<b>Día:</b> <input type="date" name="dia" required value="<?php echo $dia ?>"></br>
						<b>Número de citas:</b> <input type="number" name="numero_slots" min="0" max="10" required value="<?php echo $numero_slots ?>"></br>
						<b>Duración de las citas:</b> <input type="number" name="duracion_slots" min="0" required value="<?php echo $duracion_slots ?>"> minutos</br>
						<b>Ubicación:</b> <input type="text" name="ubicacion" required value="<?php echo $ubicacion ?>"></br>
			</p>
			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Enviar" value="Enviar" class="generalseparator functionality-button">
		</form>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="backbutton">
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