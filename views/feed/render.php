<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/google/apiclient-services/src/YouTube.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";



$JSON = json_decode($_POST["data"]);

/*

    Render Feed Head

*/
$id = $JSON->id;
$feedHead = <<<HTML
    <h1 style="display:inline">$JSON->name</h1> | 
    <a class="feed-option" href="/edit/$id">edit</a>
HTML;

/*

    Render Sections

*/
$feedSections = "";

if (isset($JSON->sections)) {
    foreach ($JSON->sections as $section) {
        $unsortedItems = [];

        foreach ($section->playlists as $playlistId) {
            $unsortedItems = array_merge($unsortedItems, parse($playlistId, ["type" => "feed"]));
        }

        // print(json_encode($unsortedItems[0]));

        $unsortedItems = videos_details($unsortedItems);

        // echo json_encode($unsortedItems[0]);

        shuffle($unsortedItems);

        $itemRender = itemRender($unsortedItems, "horizontal", false, "medium");

        $feedSections .= <<<HTML
            <section class="feed-section">
                <h1 class="feed-section-title">$section->name</h1>
                $itemRender
            </section>
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