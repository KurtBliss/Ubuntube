<?
$client_path = $_SERVER['DOCUMENT_ROOT'] . "/client_secret.json";

session_start();

$client = new Google_Client();
$client->setAuthConfig($client_path);
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);

$client->setAccessToken($_SESSION['access_token']);
$youtube = new Google_Service_YouTube($client);