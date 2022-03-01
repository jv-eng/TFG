<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Alumno_Crear_Cita</title>
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
				$recibir_publicacion_cita = "recibir_publicacion_cita.php";
				$Alumno_Seleccionar_Slot = "Alumno_Seleccionar_Slot.php";
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
				// $recibir_publicacion_cita = "Login.php";
				// $Alumno_Seleccionar_Slot = "Login.php";
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
		// $recibir_publicacion_cita = "Login.php";
		// $Alumno_Seleccionar_Slot = "Login.php";
	}

	?>

	<div class="main-container">

		<?php

		$sql1 = "SELECT * FROM `slot` WHERE `id_slot_posicion`= '" . $_POST["id_slot_posicion"] . "';";
		$result1 = mysqli_query($con, $sql1) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql1) as $row1) {
			$hora = $row1["hora"];
			$minutos = $row1["minutos"];
			if ($minutos == 0) $minutos = "00";
			if ($minutos == 5) $minutos = "05";
			$idfranja = $row1["id_franja_disponibilidad"];
			$duracion = $row1["duracion"];
			$sql2 = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja`= '" . $idfranja . "'";
			$result2 = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql2) as $row2) {
				$id_profesor_fk = $row2["id_profesor_fk"];
				$asignatura = $row2["asignatura"];
				$tipo = $row2["tipo_citas"];
				$ubicacion = $row2["ubicacion"];
				$dia = $row2["dia"];
			}
		}
		mysqli_close($con);
		?>

		<form action=<?php echo $recibir_publicacion_cita ?> method="POST" class="generalseparator">
			<p>
				Asignatura: <input type="text" name="asignatura" required value="<?php echo $asignatura ?>" disabled></br>
				Tipo: <input type="text" name="tipo_citas" required value="<?php echo $tipo ?>" disabled></br>
				Hora de inicio: <select name="hora" required disabled>
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
				<select name="minutos" required disabled>
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
				Día: <input type="date" name="dia" required value="<?php echo $dia ?>" disabled></br>
				Duración: <select name="duracion_slots" required disabled>
					<option selected value=<?php echo $duracion ?>><?php echo $duracion ?></option>
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
					<option value="60">60</option>
				</select></br>
				Ubicación: <input type="text" name="ubicacion" required value="<?php echo $ubicacion ?>" disabled></br>
				Comentarios: <textarea name="comentarios" rows="5" cols="40"></textarea></br>
			</p>
			<input type="hidden" name="dia" value=<?php echo $dia ?>>
			<input type="hidden" name="hora" value=<?php echo $hora ?>>
			<input type="hidden" name="minutos" value=<?php echo $minutos ?>>
			<input type="hidden" name="duracion_slots" value=<?php echo $duracion ?>>
			<input type="hidden" name="asignatura" value=<?php echo $asignatura ?>>
			<input type="hidden" name="ubicacion" value=<?php echo $ubicacion ?>>
			<input type="hidden" name="tipo_citas" value=<?php echo $tipo ?>>
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="id_slot_posicion" value=<?php echo $_POST["id_slot_posicion"] ?>>
			<input type="hidden" name="id_profesor_fk" value=<?php echo $id_profesor_fk ?>>
			<input type="submit" name="Reservar" value="Reservar" class="functionality-button">
		</form>
		<!-- <form action=<?php echo $Alumno_Seleccionar_Slot ?> method="POST">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>	
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="hidden" name="id_profesor" value=<?php echo $_POST["id_profesor"] ?>>
			<input type="hidden" name="idfranja" value=<?php echo $_POST["idfranja"] ?>>
			<input type="hidden" name="nombre" value=<?php echo $_POST["nombre"] ?>>
			<input type="hidden" name="ubicacion" value=<?php echo $_POST["ubicacion"] ?>>
			<input type="submit" name="Volver" value="Volver" class='backandforthbutton'>  
		</form> -->

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Alumno_Seleccionar_Slot ?> method="POST" class="backbutton">
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