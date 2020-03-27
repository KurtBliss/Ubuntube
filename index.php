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
        <title>$title</title>
        <link rel="stylesheet" type="text/css" href="/style.css">
        <script src="/index.js"></script>
    </head>
    <body>
        $header
        $menu
        $content
        $footer
    </body>
</html>
HTML;