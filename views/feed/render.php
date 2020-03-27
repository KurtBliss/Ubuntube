<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";

$JSON = json_decode($_POST["data"]);

$feedTitle = <<<HTML
    <h1>$JSON->name</h1>
HTML;

$feedSections = "";


foreach ($JSON->section as $section) {
    $item = abutubeRender::parse($section, ["type" => "feed"]);
    $feedSections .= abutubeRender::itemRender($item, "horizontal");
}

echo <<<HTML
    $feedTitle
    $feedSections
HTML;