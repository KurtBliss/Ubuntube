<?php
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
            $unsortedItems = array_merge($unsortedItems, abutubeRender::parse($playlistId, ["type" => "feed"]));
        }

        shuffle($unsortedItems);

        $itemRender = abutubeRender::itemRender($unsortedItems, "horizontal");

        $feedSections .= <<<HTML
            <section class="feed-section">
                <h1>$section->name</h1>
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