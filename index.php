<?php
require_once "abutube.php";
require_once "content.php";
require_once "views/header.php";
require_once "views/footer.php";
require_once "views/menu.php";

echo <<<HTML
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport"; content="width=device-width, initial-scale=1.0, user-scalable=no"; />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <title>$title</title>
        <link rel="stylesheet" type="text/css" href="/style.css">
        <link rel="apple-touch-icon" href="/logo.png">
        <script src="/index.js"></script>
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