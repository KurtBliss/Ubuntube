<?php
global $title, $content;

$render = "";

$js = <<<JS

JS;

if (isset($_GET["access_token"])) {
    session_start();
    $token = $_GET["access_token"];
    $_SESSION["access_token"] = $token;
    $render = <<<HTML
        <h2>
            Received token
        </h2>
    HTML;
    $js = <<<JS
        alert("Received token ");
        console.log("THEE TOKEN!!", $token);
    JS;
} else {
    $render = <<<HTML
        <h2>
            Client passing google's hashed params to server 
        </h2>
    HTML;

    $js .= <<<JS
        alert("Client passing google's hashed params to server " + window.location.hash);
        form_get({}, "/process_token?" + window.location.hash);
    JS;
}

$content = <<<HTML
    <main>
        <section>
            $render
        </section>
        <script>
            $js
        </script>
    </main>
HTML;