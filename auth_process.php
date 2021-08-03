<?php
require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/auth_process');
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);

if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}