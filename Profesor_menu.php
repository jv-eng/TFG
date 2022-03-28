<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Profesor_menu</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">
	<link rel="stylesheet" href="./calendarstyle.css">

	<script>
		document.addEventListener('DOMContentLoaded',
			function() {
				var calendarEl = document.getElementById('previewcalendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'timeGridDay',
					firstDay: 1,
					slotMinTime: "07:00:00",
					slotMaxTime: "20:00:00",
					nowIndicator: true,
					events: 'loadProfessorEvents.php',
					timeZone: 'Europe/Madrid',
					locale: "es",
					eventTimeFormat: { // like '14:30:00'
						hour: '2-digit',
						minute: '2-digit',
						hour12: false
					},
					slotLabelFormat: {
						hour: '2-digit',
						minute: '2-digit',
						omitZeroMinute: false,
						meridiem: 'long'
					},
					themeSystem: 'standard',
					height: "500px"

				});
				calendar.render();
			});
	</script>
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
	$bienvenido = "";
	$notificaciones = 0;

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

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {

				$query = $con->prepare("SELECT nombre FROM `profesor` WHERE (`mail` = ?);");
				mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
				mysqli_stmt_execute($query);
				$result2 = mysqli_stmt_get_result($query);
				$row2 = mysqli_fetch_array($result2);
				mysqli_stmt_close($query);

				$query = $con->prepare("SELECT COUNT(id_notificaciones_profesor) AS 'count' FROM `notificaciones_profesor` WHERE (`mail_profesor` = ?);");
				mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
				mysqli_stmt_execute($query);
				$result3 = mysqli_stmt_get_result($query);
				$row3 = mysqli_fetch_array($result3);
				mysqli_stmt_close($query);

				$notificaciones = $row3["count"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie

				if ($_COOKIE["mail"] == "admin@fi.upm.es") {
					$admin = true;
					$back_page = "Menu_admin.php";
				} else {
					$admin = false;
					$back_page = "Login.php";
				}
				$Profesor_Publicar_Franja = "Profesor_Publicar_Franja.php";
				$Profesor_Consulta_Franjas = "Profesor_Consulta_Franjas.php";
				$Profesor_consulta_citas = "Profesor_consulta_citas.php";
				$Profesor_buzon = "Profesor_buzon.php";
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
			<h3 class="generalseparator red">Sesión expirada. Por favor, vuelva a logearse. y</h3>

			<form action="Login.php" method="POST" class="generalseparator">
				<input type="submit" name="inicio" value="Volver al menú de inicio" class="functionality-button">
			</form>

		</div>

	<?php
		exit();
	}
	?>

	<div class="grid-container">

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

		$query = $con->prepare("SELECT * FROM `franja_disponibilidad` WHERE `id_profesor_fk`= ? AND `dia` LIKE ? LIMIT 1;");
		$date_ = date("Y-m-d");
		mysqli_stmt_bind_param($query, "is", $id_profesor,$date_);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_get_result($query);
		mysqli_stmt_close($query);

		echo '<script>console.log("La franja es: ' . $query . '"); </script>';

		foreach ($result as $row) {

		?>
			<?php
			$resultados = true;
			$idfranja = $row["idfranja"];

			$query = $con->prepare("SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = ? AND `disponible` = '1';");
			mysqli_stmt_bind_param($query, "i", $idfranja);
			mysqli_stmt_execute($query);
			$result2 = mysqli_stmt_get_result($query);
			mysqli_stmt_close($query);

			echo '<script>console.log("La franja es: ' . $query . '"); </script>';

			foreach ($result2 as $row2) {
				$numero_slots_disp = mysqli_num_rows($result2);
			}
			$tipo_citas = $row['tipo_citas'];
			$numero_slots = $row['numero_slots'];
			$ubicacion = $row['ubicacion'];
			$hora = $row['hora'];
			$minutos = $row['minutos'];
			if ($minutos == 0) $minutos = "00";
			if ($minutos == 5) $minutos = "05";
			?>
		<?php

		}
		?>
		<div class="franja-container">

			<?php
			if (!$resultados) {

			?>
				<p class="generalseparator marineblue"><b>No hay ninguna franja publicada para hoy</b></p>

				<p class="generalseparator">Si quiere ver si existen más franjas publicadas para esta semana, haga click en boton a continuación.</p>

			<?php

			} else {

			?>
				<div class="tablafranjas">

					<table>
						<tr>
							<th>Franjas para hoy</th>
						</tr>
						<tr>
							<td>Tipo:</td>
							<td><?php echo $tipo_citas ?></td>
						</tr>
						<tr>
							<td>Hora de inicio:</td>
							<td><?php echo $hora . ":" . $minutos ?></td>
						</tr>

						<tr>
							<td>Citas:</td>
							<td><?php echo $numero_slots_disp . "/" . $numero_slots ?> </td>
						</tr>

						<tr>
							<td>Ubicación:</td>
							<td><?php echo $ubicacion ?></td>
						</tr>
					</table>


				</div>

			<?php

			}
			?>

			<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Ver Más Franjas" value="Ver Más Franjas" class="functionality-button">
			</form>

			<p class="generalseparator marineblue"><b>¿Desea publicar una nueva franja?</b></p>

			<form action=<?php echo $Profesor_Publicar_Franja ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Ver Más Franjas" value="Publicar Nueva Franja" class="functionality-button">
			</form>

			<p class="generalseparator marineblue"><b>¿Desea crear una franja semestral de tutorías?</b></p>

			<form action=<?php echo "Profesor_Publicar_Franja_Semestral.php" ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Ver Más Franjas" value="Publicar Franja semestral" class="functionality-button">
			</form>



		</div>

		<div class="alumno-profesor-container">

			<h3 class="generalseparator marineblue">Bienvenido a la plataforma, <?php echo $bienvenido ?> </h3>

			<p class="generalseparator"><b>Usted tiene <?php echo $notificaciones ?>
					<?php if ($notificaciones > 1 || $notificaciones == 0) {
						echo "notificaciones";
					} else {
						echo "notificacion";
					}  ?> </b></p>


			<form action=<?php echo $Profesor_buzon ?> method="POST" class="generalseparator">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Buzon de notificaciones" value="Buzon de notificaciones" class="functionality-button">
			</form>

		</div>

		<div class="cita-container">

			<div id="previewcalendar" class="generalseparator"></div>


			<form action=<?php echo $Profesor_consulta_citas ?> method="POST">
				<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
				<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
				<input type="submit" name="Citas" value="Ver próximas citas" class="functionality-button">
			</form>

		</div>

	</div>

	<div class="backandforthbuttons">

		<form action="Login.php" method="POST" class="logoutbutton">
			<input type="submit" name="Logout" value="< Logout">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ;?>>
		</form>

	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>


</html>