<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";
$JSON = json_decode($_POST["data"]);

/*

    Render Feed Head

*/
$id = $JSON->id;
$feedHead = <<<HTML
    <h1>$JSON->name</h1>
    <p><a href="/edit/$id">edit</a></p>
HTML;

/*

    Render Sections

*/
$feedSections = "";

if (isset($JSON->section)) {
    foreach ($JSON->section as $section) {
        $item = abutubeRender::parse($section, ["type" => "feed"]);
        $feedSections .= abutubeRender::itemRender($item, "horizontal");
    }
} else {
    $feedSections = <<<HTML
        <p>Feed is empty</p>
    HTML;
}

/*

    Output

*/
echo <<<HTML
    $feedHead
    $feedSections
HTML;