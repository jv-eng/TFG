<html lang="es">
<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>Admin_Cuentas</title>
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
			$query = $con->prepare("SELECT id_sesion FROM `session` WHERE (`mail_profesor` = ?);");
  			mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
			mysqli_stmt_execute($query);
			$result = mysqli_stmt_get_result($query);
			$row = mysqli_fetch_array($result);
			mysqli_stmt_close($query);

			if ($result && $row != [] && $row["id_sesion"] == md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR'])) {
				// $recordatorio = "<p class= " . "recordatorio" . ">Usted está logeado como: " . $_COOKIE["mail"];
				setcookie("mail", $_POST["mail"], time() + 3600);	//Renovar cookie
				$time = time();
				$time_click = $time + 3600;
				setcookie("id_sesion", $row["id_sesion"], $time_click);	//Renovar cookie
				$Menu_admin = "Menu_admin.php";
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
	}
	?>

	<div class="main-container">

		<div class="upper-container">

		<?php
			use Carbon\Carbon;

			require __DIR__ . '/vendor/autoload.php';
	
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
	
			function getDataLDAP($elem) {
				$ldaphost = "ldap://192.168.1.35";  // servidor LDAP
				$ldapport = 389;                 // puerto del servidor LDAP
				$info = '';

				// Conexión al servidor LDAP
				$ldapconn = ldap_connect($ldaphost, $ldapport) or die("error");
				ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);
				ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

				if ($ldapconn) {
					//Autenticación  LDAP: 
					$ldapbind =  ldap_bind($ldapconn);
					//Búsqueda LDAP: 
					$search = ldap_search($ldapconn, 'ou='. $elem . ',dc=test-tfg', "uid=*");
					$info = ldap_get_entries($ldapconn, $search);
					ldap_close($ldapconn);
				} else {
					echo 'error';
				}
				return $info;
			}

			// Get the API client and construct the service object.
			$client = getClient();
			$service = new Google_Service_Calendar($client);

			//obtener datos del ldap
			$get_LDAP_info_profesores = getDataLDAP("profesores");
			$get_LDAP_info_alumnos = getDataLDAP("alumnos");

			//meter datos
			$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias') or die('Error en conexion al servidor');

			//profesores
			for ($i = 0; $i < $get_LDAP_info_profesores["count"]; $i++) {
				//crear id calendario
				if ($get_LDAP_info_profesores[$i]["cn"] != "Administrador") {
					$calendar = new Google_Service_Calendar_Calendar();
					$calendar->setSummary($get_LDAP_info_profesores[$i]["mail"][0]);
					$calendar->setTimeZone('Europe/Madrid');
					$createdCalendar = $service->calendars->insert($calendar);
					$calendarID = $createdCalendar->getId();

					//obtener datos
					$nombre = "";

					for ($j = 0; $get_LDAP_info_profesores[$i]["cn"][0][$j] != " "; $j++) {
						$nombre = $nombre . $get_LDAP_info_profesores[$i]["cn"][0][$j];
					}

					$apellidos = $get_LDAP_info_profesores[$i]["sn"][0];
					$tbuscar = $get_LDAP_info_profesores[$i]["cn"][0];
					$mail = $get_LDAP_info_profesores[$i]["mail"][0];
					$despacho = $get_LDAP_info_profesores[$i]["description"][0];

					//lanzar consulta
					$query = $con -> prepare("INSERT INTO profesor (nombre, apellidos, tbuscar, mail, Despacho, calendarID) VALUES (?,?,?,?,?,?);");
					mysqli_stmt_bind_param($query,"ssssss",$nombre,$apellidos,$tbuscar,$mail,$despacho,$calendarID);
					mysqli_stmt_execute($query);
				}
			}

			//alumnos
			for ($i = 0; $i < $get_LDAP_info_profesores["count"]; $i++) {
				//obtener datos
				$nombre = "";

				for ($j = 0; $get_LDAP_info_alumnos[$i]["cn"][0][$j] != " "; $j++) {
					$nombre = $nombre . $get_LDAP_info_alumnos[$i]["cn"][0][$j];
				}

				$apellidos = $get_LDAP_info_alumnos[$i]["sn"][0];
				$mail = $get_LDAP_info_alumnos[$i]["mail"][0];

				//lanzar consulta
				$query = $con -> prepare("INSERT INTO alumno (nombre_alumno, apellidos_alumno, mail_alumno) VALUES (?,?,?);");
				mysqli_stmt_bind_param($query,"sss",$nombre,$apellidos,$mail);
				mysqli_stmt_execute($query);
			}

			mysqli_stmt_close($query);

			echo "<h2>Volcado realizado</h2>";

			
		?>

		</div>

	</div>

	<div class="backandforthbuttons">

		<form action=<?php echo $Menu_admin ?> method="POST" class="backbutton">
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