<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";

$JSON = json_decode($_POST["data"]);

console_log($JSON);

/*

    Render Feed Head

*/
$id = $JSON->id;
$feedHead = <<<HTML
    <h1>$JSON->name</h1>
    <p><a href="/feed/$id">view</a></p>
    <p><a href="#" onclick="return feedRemove('$id')">delete</a></p>
HTML;

/*

    Render Sections

*/
$feedSections = "";

if (isset($JSON->section)) {
    foreach ($JSON->section as $section) {
        $feedSections .= <<<HTML
            <p>section</p>
        HTML;
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