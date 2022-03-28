<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Validar_profesor</title>
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

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// echo "Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Continuar = "Admin_Cuentas.php";
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
				// $Continuar = "Login.php";
			}
			mysqli_close($con);
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
		// $Continuar = "Login.php";
	}
	?>

	<div class="main-container">

		<?php
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		$sql = "UPDATE `profesor` SET `Validado` = '1' WHERE `profesor`.`mail` = '" . $_POST["mail_profesor"] . "'";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result) {
		?>

			<h3 class="generalseparator green">Se ha validado la cuenta de <?php echo $_POST["mail_profesor"] ?></h3>
		<?php

		} else {
		?>

			<h3 class="generalseparator red">No se pudo validar la cuenta de <?php echo $_POST["mail_profesor"] ?></h3>
		<?php
		}

		use Carbon\Carbon;

		require __DIR__ . '/vendor/autoload.php';

		/*
				if (php_sapi_name() != 'cli') {
					throw new Exception('This application must be run on the command line.');
				}
				*/
		/**
		 * Returns an authorized API client.
		 * @return Google_Client the authorized client object
		 */
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

		$calendar = new Google_Service_Calendar_Calendar();
		$calendar->setSummary($_POST["mail_profesor"]);
		$calendar->setTimeZone('Europe/Madrid');


		$createdCalendar = $service->calendars->insert($calendar);
		$calendarID = $createdCalendar->getId();
		//echo $createdCalendar->getId();

		$sql = "UPDATE `profesor` SET `calendarID` = '" . $calendarID . "' WHERE `profesor`.`mail` = '" . $_POST["mail_profesor"] . "'";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result) {

			echo '<script>console.log("Se ha creado el calendario correctamente.\n"); </script>';
		} else {
			echo '<script>console.log("No se ha podido crear el calendario correctamente.\n"); </script>';
		}

		mysqli_close($con);
		?>

		<form action=<?php echo $Continuar ?> method="POST" class="generalseparator">
			<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
			<input type="hidden" name="id" value=<?php echo $_POST["id_profesor"] ?>>
			<input type="submit" name="Continuar" value="Continuar" class="functionality-button">
		</form>


	</div>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>