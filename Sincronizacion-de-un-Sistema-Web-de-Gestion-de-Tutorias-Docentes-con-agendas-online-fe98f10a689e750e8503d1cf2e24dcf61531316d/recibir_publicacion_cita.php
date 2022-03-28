<!--	Author: Juan Borrero Carrón		-->

<head>
  <title>recibir_publicacion_cita</title>
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
        setcookie("mail", $_POST["mail"], time() + 3600);  //Crear cookie
        $time = time();
        $time_click = $time + 3600;
        setcookie("id_sesion", $_COOKIE["id_sesion"], $time_click);  //Crear cookie
        $Alumno_Menu = "Alumno_Menu.php";
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
        // $Alumno_Menu = "Login.php";
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
    // $Alumno_Menu = "Login.php";
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

    $sql = "UPDATE `slot` SET `id_alumno_fk` = '" . $_POST["id"] . "',  `disponible` = '0', `comentarios_alumno` = '" . $_POST["comentarios"] . "' WHERE `slot`.`id_slot_posicion` = '" . $_POST["id_slot_posicion"] . "'";
    // echo $sql;
    $result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
    if ($result) {

    ?>

      <h3 class="generalseparator green">La inserción de la cita se ha realizado correctamente.</h3>

    <?php
    }
    mysqli_close($con);
    ?>

    <form action=<?php echo $Alumno_Menu ?> method="POST" class="generalseparator">
      <input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
      <input type="hidden" name="id" value=<?php echo $_POST["id"] ?>>
      <input type="submit" name="Aceptar" value="Aceptar" class="functionality-button">
    </form>

  </div>

  <div class="backandforthbuttons">

    <form action=<?php echo $Alumno_Menu ?> method="POST" class="backbutton">
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

<?php

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
echo '<script>console.log("client") </script>';
$service = new Google_Service_Calendar($client);
echo '<script>console.log("service") </script>';
// Print the next 10 events on the user's calendar.
// $calendarId = 'primary';


// $optParams = array(
//   'maxResults' => 10,
//   'orderBy' => 'startTime',
//   'singleEvents' => true,
//   'timeMin' => "2021-01-01T19:30:49+01:00",//date('c'),
// );
// $results = $service->events->listEvents($calendarId, $optParams);
// echo '<script>console.log("results") </script>';

// $events = $results->getItems();
// echo '<script>console.log("$events") </script>';



// Refer to the PHP quickstart on how to setup the environment:
// https://developers.google.com/calendar/quickstart/php
// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
// credentials.

$fecha_cita = $_POST["dia"];
$fecha_cita = date("Y-m-d\TH:i:sP", strtotime('+' . $_POST["hora"] . ' hour +' . $_POST["minutos"] . ' minutes', strtotime($fecha_cita))); //formato deseado por Google Calendar API
echo '<script>console.log("la hora para fecha_cita es: ' . $fecha_cita . '") </script>';

$minutos_final = (int)$_POST["minutos"] + (int)$_POST["duracion_slots"];
$fecha_final = $_POST["dia"];
$fecha_final = date("Y-m-d\TH:i:sP", strtotime('+' . $_POST["hora"] . ' hour +' . $minutos_final . ' minutes', strtotime($fecha_final))); //formato deseado por Google Calendar API
echo '<script>console.log("la hora para fecha_final es: ' . $fecha_final . '") </script>';


$event = new Google_Service_Calendar_Event(array(
  'summary' => $_POST["asignatura"],
  'location' => $_POST["ubicacion"],
  'description' => "Motivo del evento: " . $_POST["tipo_citas"] . "\n" . "Comentario del alumno: " . $_POST["comentarios"],
  'start' => array(
    'dateTime' => $fecha_cita, //date('c'),//(new DateTime)->setDate(2020,3,28)->format('Y-m-d\TH:i:sP'),
    'timeZone' => 'Europe/Madrid',
  ),
  'end' => array(
    'dateTime' => $fecha_final, //(new DateTime)->setDate(2020,3,30)->format('Y-m-d\TH:i:sP'),
    'timeZone' => 'Europe/Madrid',
  ),
  'attendees' => array(
    array('email' => $_POST["mail"]),
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 10),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

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
$event = $service->events->insert($calendarId, $event);
echo "<script>console.log('Event created: " . $event->htmlLink . "' );</script>";
echo "<script>console.log('Calendar ID: " . $calendarId . "' );</script>";


$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
echo '<script>console.log("results") </script>';

$events = $results->getItems();
echo '<script>console.log("$events") </script>';



mysqli_close($con);

if (empty($events)) {
  echo '<script>console.log("No upcoming events found.\n"); </script>';
} else {
  echo '<script>console.log("All events: "); </script>';
  //print "All events: </br>\n";
  foreach ($events as $event) {
    $start = $event->start->dateTime;
    if (empty($start)) {
      $start = $event->start->date;
    }
    echo "<script>console.log('" . $event->getSummary() . "' );</script>";
    //printf("%s %s (%s)\n", $event->getSummary(), $event->getDescription(), $start);
    //print "</br>";
  }
}
//echo date('c');
?>

</html>