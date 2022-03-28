<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_publicacion_franja</title>
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
		if ($id_profesor != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}
			switch ($_POST["tipo"]) {
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
			switch ($_POST["duracion_slots"]) {
				case "05":
					$duracion_slots = 05;
					break;
				case "10":
					$duracion_slots = 10;
					break;
				case "15":
					$duracion_slots = 15;
					break;
				case "20":
					$duracion_slots = 20;
					break;
				case "25":
					$duracion_slots = 25;
					break;
				case "30":
					$duracion_slots = 30;
					break;
				case "35":
					$duracion_slots = 35;
					break;
				case "40":
					$duracion_slots = 40;
					break;
				case "45":
					$duracion_slots = 45;
					break;
				case "50":
					$duracion_slots = 50;
					break;
				case "55":
					$duracion_slots = 55;
					break;
				case "55":
					$duracion_slots = 55;
					break;
				default:
					$duracion_slots = 00;
					break;
			}
			$sql = "INSERT INTO `franja_disponibilidad` VALUES (NULL, '" . $id_profesor . "', '" . $_POST["asignatura"] . "', '" . $tipo . "', '" . $_POST["hora"] . "', '" . $_POST["minutos"] . "', '" . $_POST["duracion_slots"] . "', '" . $_POST["dia"] . "', '" . $_POST["numero_slots"] . "', '" . $_POST["ubicacion"] . "');";

			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD1');

			$idfranja = mysqli_insert_id($con);

			$numero_slots = $_POST["numero_slots"];
			$hora = $_POST["hora"];
			$minutos = $_POST["minutos"];
			if ($minutos == 0) $minutos = "00";
			if ($minutos == 5) $minutos = "05";

			$duracion_slots = $_POST["duracion_slots"];
			while ($numero_slots > 0) {
				$sql = "INSERT INTO `slot` (`id_slot_posicion`, `id_franja_disponibilidad`, `id_alumno_fk`, `hora`, `minutos`, `duracion`, `dia`, `disponible`, `comentarios_alumno`) 
								VALUES (NULL, '" . $idfranja . "', NULL, '" . $hora . "', '" . $minutos . "', '" . $duracion_slots . "', '" . $_POST["dia"] . "', '1', NULL)";

				$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD2');

				$numero_slots--;
				$minutos = $minutos + $duracion_slots;

				if ($minutos >= 60) {
					$hora = $hora + 1;
					if ($hora == 24) {
						$hora = "00";
					}
					$minutos = $minutos - 60;
					if ($minutos == 5) {
						$minutos = "05";
					}
					if ($minutos == 0) {
						$minutos = "00";
					}
				}
			}
			if ($result) {

		?>

				<h3 class="generalseparator green">La inserción de la Franja de Disponibilidad se ha realizado correctamente.</h3>

		<?php

			}
			mysqli_close($con);
		}
		?>
		<form action="Profesor_Consulta_Franjas.php" method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
		</form>
	</div>

	<div class="backandforthbuttons">

		<form action="Profesor_Publicar_Franja.php" method="POST" class="backbutton">
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