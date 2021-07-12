<?php
require_once "abutube.php";
require_once "content.php";
require_once "views/header.php";
require_once "views/footer.php";
require_once "views/menu.php";

session_start();

if (isset($_GET["access_token"])) {
    echo "has access_token!";
    $_SESSION["access_token"] = $_GET["access_token"];
} else {
    echo "no token :( " . session_id();
}

echo <<<HTML
<html lang="en">
    <head>
        <title>$title</title>
        <meta charset="utf-8">
        <meta name="viewport"; content="width=device-width, initial-scale=1.0, user-scalable=no"; />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <!-- <meta name="google-signin-client_id" content="915181654584-evnvq4te40679g3hsboacrf9dofn7juu.apps.googleusercontent.com"> -->
        <link rel="stylesheet" type="text/css" href="/style.css">
        <link rel="apple-touch-icon" href="/logo.png">
        <link rel="icon" href="/logo.png">
        <script src="/index.js"></script>
        <!-- <script src="https://apis.google.com/js/platform.js" async defer></script> -->
    </head>
    <body>
        $header
        $menu
        $content
        $footer
        <script>this.href4ios();</script>
    </body>
</html>
HTML;