<?php
global $content;
require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';
session_start();
$render = "";

try {
    $client = new Google_Client();
    $client->setAuthConfig('client_secret.json');
    $client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);
    $client->setAccessToken($_SESSION['access_token']);
    $youtube = new Google_Service_YouTube($client);
    $channel = $youtube->subscriptions->listSubscriptions('snippet', array('mine' => "true", 'maxResults' => 50));
    $render = itemRender(
        parse(
            $channel
        ),
        "grid"
    );
} catch (exception $e) {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth_process.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

$content = <<<HTML
    <main>
        <section>
        $render
        </section>
    </main>
HTML;