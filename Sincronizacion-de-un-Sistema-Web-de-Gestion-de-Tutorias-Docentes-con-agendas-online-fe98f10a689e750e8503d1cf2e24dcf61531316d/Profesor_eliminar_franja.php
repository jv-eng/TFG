<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Profesor_eliminar_franja</title>
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

		use Carbon\Carbon;

		require __DIR__ . '/vendor/autoload.php';

		function getClient()
		{
			$client = new Google_Client();
			$client->setApplicationName('Google Calendar API PHP TFG FI UPM');
			$client->setScopes(Google_Service_Calendar::CALENDAR);
			$client->setAuthConfig(__DIR__ . '/credentials.json');
			$client->setAccessType('offline');
			// Using "force" ensures that your application always receives a refresh token.
			// If you are not using offline access, you can omit this.
			$client->setApprovalPrompt('force');

			// Load previously authorized token from a file, if it exists.
			// The file token.json stores the user's access and refresh tokens, and is
			// created automatically when the authorization flow completes for the first
			// time.
			$tokenPath = 'token.json';
			if (file_exists($tokenPath)) {
				$accessToken = json_decode(file_get_contents($tokenPath), true);
				$client->setAccessToken($accessToken);
			}

			// If there is no previous token or it's expired.
			if ($client->isAccessTokenExpired()) {
				// Refresh the token if possible, else fetch a new one.
				if ($client->getRefreshToken()) {
					$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
				} else {
					// Request authorization from the user.
					$authUrl = $client->createAuthUrl();
					printf("Open the following link in your browser:\n%s\n", $authUrl);
					print 'Enter verification code: ';
					$authCode = trim(fgets(STDIN));

					// Exchange authorization code for an access token.
					$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
					$client->setAccessToken($accessToken);

					// Check to see if there was an error.
					if (array_key_exists('error', $accessToken)) {
						throw new Exception(join(', ', $accessToken));
					}

					// Save the token to a file.
					if (!file_exists(dirname($tokenPath))) {
						mkdir(dirname($tokenPath), 0700, true);
					}
					file_put_contents($tokenPath, json_encode($client->getAccessToken()));
				}
			}
			return $client;
		}


		// Get the API client and construct the service object.
		$client = getClient();
		$service = new Google_Service_Calendar($client);

		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');

		$id_profesor_fk_int = (int)$_POST["id_profesor_fk"];
		$sql = "SELECT calendarID FROM `profesor` WHERE (`id_profesor` = '" . $id_profesor_fk_int . "');";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result) {
			echo '<script>console.log("Se ha hecho la consulta correctamente.\n"); </script>';
		} else {
			echo '<script>console.log("No se ha podido hacer la consulta correctamente.\n"); </script>';
		}
		$row = $result->fetch_assoc();

		$calendarId = $row["calendarID"];

		$sql = "SELECT `id_slot_posicion` FROM `slot` WHERE `id_franja_disponibilidad` = '" . $_POST["idfranja"] . "' AND `disponible` = '0';";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		while ($row = mysqli_fetch_assoc($result)) {
			$sql2 = "SELECT `hora`, `minutos`, `comentarios_alumno` FROM `slot` WHERE `id_slot_posicion` = '" . $row["id_slot_posicion"] . "';";
			$result2 = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');
			$row2 = mysqli_fetch_assoc($result2);
			$fecha_cita = $_POST["dia"];
			$fecha_cita = date("Y-m-d\TH:i:sP", strtotime('+' . $row2["hora"] . ' hour +' . $row2["minutos"] . ' minutes', strtotime($fecha_cita))); //formato deseado por Google Calendar API
			//printf("la hora para fecha_cita es: %s </br></br>\n", $fecha_cita);

			$minutos_final = (int)$row2["minutos"] + (int)$_POST["duracion_slots"];
			$fecha_final = $_POST["dia"];
			$fecha_final = date("Y-m-d\TH:i:sP", strtotime('+' . $row2["hora"] . ' hour +' . $minutos_final . ' minutes', strtotime($fecha_final))); //formato deseado por Google Calendar API
			//printf("la hora para fecha_final es: %s </br></br>\n", $fecha_final);
			$eventodelete = $service->events->listEvents($calendarId, array('timeMin' => $fecha_cita, 'timeMax' => $fecha_final, 'q' => $row2["comentarios_alumno"]));
			echo "<script>console.log('eventodelete: " . $eventodelete->timeZone . "' );</script>";;
			$eventoId = $eventodelete->items[0]->getId();
			$service->events->delete($calendarId, $eventoId);
		}

		$sql = "SELECT * FROM `slot` WHERE `id_franja_disponibilidad` = '" . $_POST["idfranja"] . "' AND `disponible` = '0';";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		foreach ($con->query($sql) as $row) {
			// echo $row["id_alumno_fk"]."<br>";
			$sql = "SELECT * FROM `alumno` WHERE `idalumno` = '" . $row["id_alumno_fk"] . "';";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row1) {
			}
			$idfranja = $row["id_franja_disponibilidad"];
			$sql = "SELECT * FROM `franja_disponibilidad` WHERE `idfranja` = '" . $idfranja . "';";  // FD
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			foreach ($con->query($sql) as $row2) {
			}
			$fecha = date("Y-m-d");
			$hora = date("H");
			$minutos = date("i");
			$sql = "INSERT INTO `notificaciones_alumno` (`id_notificaciones_alumno`, `id_alumno_fk`, `mail_alumno`, `asignatura`, `tipo_citas`, `motivo`, `fecha_cita`, `hora_cita`, `minutos_cita`, `fecha_notif`, `hora_notif`, `minutos_notif`) 
														VALUES ('', '" . $row["id_alumno_fk"] . "', '" . $row1["mail_alumno"] . "', '" . $row2["asignatura"] . "', '" . $row2["tipo_citas"] . "', 'eliminación de franja de disponibilidad', '" . $row["dia"] . "', '" . $row["hora"] . "', '" . $row["minutos"] . "', '" . $fecha . "', '" . $hora . "', '" . $minutos . "')";
			$result = mysqli_query($con, $sql) or die('Error al enviar notificación');
		}
		$sql = "DELETE FROM `franja_disponibilidad` WHERE `idfranja` = '" . $_POST["idfranja"] . "';";
		$result1 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		$sql = "DELETE FROM `slot` WHERE `id_franja_disponibilidad` = '" . $_POST["idfranja"] . "';";
		$result2 = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');

		if ($result1 && $result2) {

		?>
			<p class="generalseparator marineblue"><b>La Franja de Disponibilidad se ha eliminado correctamente.</b></p>

		<?php

		} else {

		?>
			<p class="generalseparator green"><b>La Franja de Disponibilidad no se ha podido eliminar correctamente.</b></p>

		<?php
		}
		mysqli_close($con);
		?>

		<form action=<?php echo $Profesor_Consulta_Franjas ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
			<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
		</form>
	</div>

	<div class="backandforthbuttons">

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