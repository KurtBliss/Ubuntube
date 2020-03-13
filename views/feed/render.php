<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";

$JSON = json_decode($_POST["data"]);

$feedTitle = <<<HTML
    <h1>$JSON->name</h1>
HTML;

$feedSections = "";


foreach ($JSON->section as $section) {
    $feedSections .= abutubeRender::itemRender(abutubeRender::parse($section, ["type" => "feed"]), "horizontal");
}

// $data = abutubeRender::parse($JSON->section, ["type" => "feed"]);

// foreach ($data as $section) {
// $feedSections .= abutubeRender::itemRender($section, "horizontal");
// }
// print_r(abutubeRender::parse($JSON, ["type" => "feed"]));

echo <<<HTML
    $feedTitle
    $feedSections
HTML;