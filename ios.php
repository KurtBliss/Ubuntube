<?php

/*

    Keep ios app in fullscreen

*/

echo <<<HTML
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport"; content="width=device-width, initial-scale=1.0, user-scalable=no"; />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="/logo.png">
        <!-- <meta name="apple-mobile-web-app-status-bar-style" content="363f63"> -->
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <link rel="icon" href="/logo.png">
        <title>BlissfulTube</title>
    </head>
    <body>
        <iframe src="/" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
            Your browser doesn't support iframes
        </iframe>
    </body>
</html>
HTML;