<?php

$JSON = json_decode($_POST["data"]);

$feedTitle = <<<HTML
    <h1>$JSON->name</h1>
HTML;

$feedSections = "";

if (isset($JSON->section))
    foreach ($JSON->section as $section) {
        if ($section->type == "singlePlaylist") {

            // Playlist Data
            $response = abutube::playlist_data($section->playlistId);


            // Playlist Items
            $response = abutube::playlist_items($section->playlistId);

            // Render
            $feedSections .= <<<HTML
                <p></p>
            HTML;
        }
    }

echo <<<HTML
    $feedTitle
    $feedSections
HTML;