<?php
$singleton_page = false;
require_once "content.php";
if ($singleton_page) {
} else {
    require_once "abutube.php";
    require_once "views/header.php";
    require_once "views/footer.php";
    require_once "views/menu.php";
    // require_once "vendor/google/auth/autoload.php";
    // require_once "vendor/google/apiclient-services/autoload.php";

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
            <script src="https://kit.fontawesome.com/a5c87a9067.js" crossorigin="anonymous"></script>
            <script src="/index.js"></script>
            <!-- <script src="https://apis.google.com/js/platform.js" async defer></script> -->
        </head>
        <body>
            $header
            $menu
            $content
            $footer
            <script>
                // this.href4ios(); 
            </script>
        </body>
    </html>
    HTML;
}