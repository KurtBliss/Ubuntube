<?php
global $content;

require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';

session_start();

$render = "";

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $youtube = new Google_Service_YouTube($client);
    $channel = $youtube->subscriptions->listSubscriptions('snippet', array('mine' => "true", 'maxResults' => 50));
    // $channel = $youtube->channels->listChannels('snippet', array('mine' => $mine));
    $render = itemRender(
        parse(
            $channel
        ),
        "grid"
    );
    // echo json_encode($channel->items);
} else {
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