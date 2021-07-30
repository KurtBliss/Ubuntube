<?
$client_path = $_SERVER['DOCUMENT_ROOT'] . "/client_secret.json";

// if (isset($session_id)) {
session_start();
// }

// echo $client_path;

$client = new Google_Client();
$client->setAuthConfig($client_path);
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);

// $httpClient = new GuzzleHttp\Client([
//     'proxy' => 'localhost:8888', // by default, Charles runs on localhost port 8888
//     'verify' => false, // otherwise HTTPS requests will fail.
// ]);
// $client->setHttpClient($httpClient);

// print_r($_SESSION['access_token']);
$client->setAccessToken($_SESSION['access_token']);
$youtube = new Google_Service_YouTube($client);