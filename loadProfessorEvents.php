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
  $client->setAuthConfig(__DIR__ .'/credentials.json');
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

// Refer to the PHP quickstart on how to setup the environment:
// https://developers.google.com/calendar/quickstart/php
// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
// credentials.

$con=mysqli_connect('localhost','root','','prueba2_tfg_tutorias');

$query = $con->prepare("SELECT `id_profesor` FROM `profesor` WHERE `mail` = ?;");
mysqli_stmt_bind_param($query, "s", $_COOKIE["mail"]);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
mysqli_stmt_close($query);

foreach ($result as $row) {
    $id_profesor=$row["id_profesor"];
}

$query = $con->prepare("SELECT calendarID FROM `profesor` WHERE (`id_profesor` = ?);");
mysqli_stmt_bind_param($query, "i", $id_profesor);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
mysqli_stmt_close($query);

$row = $result->fetch_assoc();
mysqli_close($con);


  $calendarId = $row["calendarID"];


 

  $optParams = array(
    'maxResults' => 100,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('c'),
  );
  $results = $service->events->listEvents($calendarId, $optParams);
  
  $events = $results->getItems();
  
  
if (empty($events)) {
    echo json_encode(array());

} else {

	$arrayevents= array();
    //print "All events: </br>\n";
    foreach ($events as $event) {

        
        $start = $event->start->dateTime;
		$end = $event->end->dateTime;
		$title= $event->getSummary();

        if (empty($start)) {
            $start = $event->start->date;
			
        }

		if (empty($end)) {
            $end = $event->end->date;
			
        }
        $arrayevents[] = array(
            'title'   => $title,
            'start'   => $start,
            'end'   => $end
        );
    }
    echo json_encode($arrayevents);

}


?>