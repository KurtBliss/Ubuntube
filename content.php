<?php
require 'vendor/autoload.php';
global $singleton_page;

$router = new AltoRouter();
$website_name = "Blissfultube";

$content = "";

$router->map('GET', '/', function () {
    global $title, $website_name;
    $title = "$website_name";
    require_once "abutube.php";
    include "views/home.php";
});

// $router->map('GET', '/auth', function () {
//     $singleton_page = true;
//     include "auth.php";
// });
// $router->map('GET', '/auth_process', function () {
//     $singleton_page = true;
//     include "auth_process.php";
// });

$router->map("GET", "/watch", function () {
    global $title, $website_name;
    $title = "Watch - $website_name";
    require_once "abutube.php";
    include "views/youtube/watch.php";
});

$router->map("GET", "/playlist", function () {
    global $title, $website_name;
    $title = "Playlist - $website_name";
    require_once "abutube.php";
    include "views/youtube/playlist.php";
});

$router->map('GET', '/results', function () {
    global $title, $website_name;
    $title = "Results - $website_name";
    require_once "abutube.php";
    include "views/youtube/results.php";
});

$router->map('GET', '/subscriptions', function () {
    global $title, $website_name;
    $title = "Subscriptions - $website_name";
    require_once "abutube.php";
    include "views/subscriptions.php";
});

$router->map('GET', '/process_token', function () {
    global $title, $website_name;
    $title = "process_token - $website_name";
    require_once "abutube.php";

    include "views/process_token.php";
});

$router->map('GET', "/channel/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Channel - $website_name";
    require_once "abutube.php";

    include "views/youtube/channel.php";
});

$router->map('GET', "/edit/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Edit - $website_name";
    require_once "abutube.php";

    include "views/feed/edit.php";
});

$router->map('GET', "/feed/[*:id]", function ($id) {
    global $title, $website_name;
    $title = "Feed - $website_name";
    require_once "abutube.php";
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