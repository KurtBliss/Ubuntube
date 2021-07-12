<?php
global $content;

session_start();

if (isset($_SESSION["access_token"])) {
    $token = $_SESSION["access_token"];
    $a = subscriptions($token);
    $render = <<<HTML
    $a
HTML;
} else {
    $render = <<<HTML
    <p>no access token</p>
HTML;
}

$content = <<<HTML
    <main>
        <section>
        $render;
        </section>
    </main>
HTML;