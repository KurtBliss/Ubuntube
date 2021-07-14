<?
require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);
// $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->setRedirectUri('http://127.0.0.1:8080/auth_process.php');
// offline access will give you both an access and refresh token so that
// your app can refresh the access token without user interaction.
$client->setAccessType('offline');
// Using "consent" ensures that your application always receives a refresh token.
// If you are not using offline access, you can omit this.
$client->setApprovalPrompt("consent");
$client->setIncludeGrantedScopes(true);   // incremental auth

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
