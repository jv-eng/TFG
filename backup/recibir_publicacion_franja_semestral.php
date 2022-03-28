<!--	Author: Juan Viejo		-->

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

<div align="left"><a href="https://www.upm.es/" target="_blank"><img src="imagenes/logo_copia.png"></a></div>

<div align="right"><a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a></div>

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

			$query = $con->prepare("SELECT id_sesion FROM `session` WHERE (`mail_profesor` = ?);");
			mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			$row = mysqli_fetch_array($result);
			mysqli_stmt_close($query);

			if ($result) {
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
	}
	?>

	<div class="main-container">

		<?php

		$id_profesor = $_POST["id"];
		$tipo="tutoria";
		//variables de calculo de semestre
		$fecha_inicio = date_create($_POST["dia"]);//$_POST["dia"];
		$fecha_inicio_par = "";
		$fecha_fin = "";
		$year = date_format($fecha_inicio,"Y");
		$flag_semestre = 0;
		$flag_semestre_2 = 0;

		//solo modificamos la base de datos si es un profesor
		if ($id_profesor != "") {
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
			if (!$con) {
				echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
				echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
				echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
				exit;
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
			switch ($_POST["semestre"]) {
				case "semestre_impar":
					$fecha_fin = date_create($year . "-12-23");
					break;
				case "semestre_par":
					$fecha_fin = date_create($year . "-05-26");
					break;
				case "ambos":
					$fecha_fin = date_create($year . "-12-23");
					$fecha_inicio_par = date_add(date_create($year . "-01-28"), date_interval_create_from_date_string("1 year"));
					$flag_semestre = 1;
					$flag_semestre_2 = 1;
					break;
			}
			//insertar multiples franjas
			//meter bucle para que cada franja meta sus slots
			$result = 1;
			do {
				while ($fecha_inicio <= $fecha_fin) {
					$sql = "INSERT INTO `franja_disponibilidad` VALUES (NULL, '" . $id_profesor . "', '" . $_POST["asignatura"] . "', 'Tutoria', '" . $_POST["hora"] . "', '" . $_POST["minutos"] . "', '" . $_POST["duracion_slots"] . "', '" . date_format($fecha_inicio,"Y-m-d") . "', '" . $_POST["numero_slots"] . "', '" . $_POST["ubicacion"] . "');";

					$query = $con->prepare("INSERT INTO `franja_disponibilidad` 
						VALUES (NULL, ?, ?, 'Tutoria', ?, ?, ?, ?, ?, ?);");
					$date_ = date_format($fecha_inicio,"Y-m-d");
					mysqli_stmt_bind_param($query, "isiiisis", $id_profesor,$_POST["asignatura"],$_POST["hora"],$_POST["minutos"],$_POST["duracion_slots"],$date_,$_POST["numero_slots"],$_POST["ubicacion"]);
					mysqli_stmt_execute($query);
					$result = mysqli_stmt_get_result($query);
					//$row = mysqli_fetch_array($result);
					mysqli_stmt_close($query);

					$idfranja = mysqli_insert_id($con);

					$numero_slots = $_POST["numero_slots"];
					$hora = $_POST["hora"];
					$minutos = $_POST["minutos"];
					if ($minutos == 0) $minutos = "00";
					if ($minutos == 5) $minutos = "05";

					//crear slots para que los alumnos puedan solicitar tutorias
					$duracion_slots = $_POST["duracion_slots"];
					while ($numero_slots > 0) {

						$query = $con->prepare("INSERT INTO `slot` (`id_slot_posicion`, `id_franja_disponibilidad`, `id_alumno_fk`, `hora`, `minutos`, `duracion`, `dia`, `disponible`, `comentarios_alumno`) 
							VALUES (NULL, ?, NULL, ?, ?, ?, ?, '1', NULL);");

						$date_ = date_format($fecha_inicio,"Y-m-d");
						mysqli_stmt_bind_param($query, "iiiis", $idfranja, $hora, $minutos, $duracion_slots, $date_);
						mysqli_stmt_execute($query);
						mysqli_stmt_close($query);

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
					$fecha_inicio = date_add($fecha_inicio,date_interval_create_from_date_string("1 week"));
				}
				//control de flags
				$flag_semestre_2 = ($flag_semestre === 0 && $flag_semestre_2)?0:1;
				if ($flag_semestre === 1) {
					$flag_semestre = 0;
					$fecha_inicio = $fecha_inicio_par;
					$fecha_fin = date_add(date_create($year . "-05-26"), date_interval_create_from_date_string("1 year"));
				}
			} while ($flag_semestre_2);
			if (1) {

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