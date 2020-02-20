<?php
require 'vendor/autoload.php';

$router = new AltoRouter();

$content;

$router->map('GET', '/', function () {
    global $title;
    $title = "Abutube";
    include "views/home.php";
});

$router->map("GET", "/watch", function () {
    global $title;
    $title = "Watch - Abutube";
    include "views/youtube/watch.php";
});

$router->map('GET', "/channel/[*:id]", function ($id) {
    global $title;
    $title = "Channel - Abutube";
    include "views/youtube/channel.php";
});

$router->map('GET', '/results', function () {
    global $title;
    $title = "Results - Abutube";
    include "views/youtube/results.php";
});

// match current request url
$trim = "/" . trim($_SERVER['REQUEST_URI'], "/");
$match = $router->match(isset($_SERVER['REQUEST_URI']) ? $trim : '/');

// call closure or throw 404 status
if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    $PageNotFound = $_SERVER["REQUEST_URI"];
    $content = <<<HTML
        <p>The following page was not found:</p>
        <p>$PageNotFound</p>
    HTML;
}