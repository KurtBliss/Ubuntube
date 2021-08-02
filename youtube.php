<?
require_once __DIR__ . '/vendor/autoload.php';


session_start();

$client = new Google_Client();
$client_path = $_SERVER['DOCUMENT_ROOT'] . "/client_secret.json";
$client->setApplicationName('Ubuntube');
$client->setAuthConfig($client_path);
$client->setAccessToken($_SESSION['access_token']);
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE);
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_READONLY);
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);
// $client->addScope("https://www.googleapis.com/auth/youtube.readonly");
// $client->setScopes([
//     'https://www.googleapis.com/auth/youtube.readonly',
//     GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL
// ]);
// echo GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL;
// print(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);
// print(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_READONLY);
$youtube = new Google_Service_YouTube($client);