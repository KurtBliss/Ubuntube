<?php
global $content;
require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';
session_start();
$render = "";

try {
    include "youtube.php";

    $subscriptions = $youtube->subscriptions->listSubscriptions('snippet,contentDetails', array('mine' => "true", 'maxResults' => 50));

    $render = itemRender(
        parse(
            $subscriptions
        ),
        "grid",
        true
    );
    // $render .= json_encode($subscriptions);
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