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
        window.location = "/subscriptions";
    JS;
} else {
    $render = <<<HTML
        <h2>
            Client passing google's hashed params to server 
        </h2>
    HTML;

    $js .= <<<JS
        var param = window.location.hash.replace("#", "");
        // alert("param " + param);
        // alert("Client passing google's hashed params to server " + window.location.hash);
        window.location = "/process_token?" + param
        // form_get({}, "/process_token?" + param);
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