<?php
require 'vendor/autoload.php';

$router = new AltoRouter();
$website_name = "Blissfultube";

$content = "";

$router->map('GET', '/', function () {
    global $title, $website_name;
    $title = "$website_name";
    include "views/home.php";
});

$router->map("GET", "/watch", function () {
    global $title, $website_name;
    $title = "Watch - $website_name";
    include "views/youtube/watch.php";
});

$router->map('GET', '/results', function () {
    global $title, $website_name;
    $title = "Results - $website_name";
    include "views/youtube/results.php";
});

$router->map('GET', "/channel/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Channel - $website_name";
    include "views/youtube/channel.php";
});

$router->map('GET', "/edit/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Edit - $website_name";
    include "views/feed/edit.php";
});

$router->map('GET', "/feed/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Feed - $website_name";
    include "views/feed/feed.php";
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