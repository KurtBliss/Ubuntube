<?php
global $content;

session_start();

if (isset($_SESSION["access_token"])) {
    $token = $_SESSION["access_token"];
    $render = subscriptions($token);
} else {
    $render = <<<HTML
    <p>no access token</p>
HTML;
}

$content = <<<HTML
    <main>
        <section>
        $render
        </section>
    </main>
HTML;