<?php
global $title, $content;

$render = "";

if (isset($_GET["token"])) {
    session_start();
    $token = $_GET["token"];
    // $_SESSION
    $render = <<<HTML
        <p>
            Received token!
        </p>
    HTML;
} else {
    $render = <<<HTML
        <p>
            Log-In
        </p>
    HTML;
}

$content = <<<HTML
    <main>
        $render
    </main>
HTML;