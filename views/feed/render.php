<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/abutube.php";

$JSON = json_decode($_POST["data"]);

$feedTitle = <<<HTML
    <h1>$JSON->name</h1>
HTML;

$feedSections = "";

if (isset($JSON->section))
    foreach ($JSON->section as $section) {
        if ($section->type == "singlePlaylist") {

            // Playlist Data
            $title = abutube::playlist_data($section->playlistId)->items[0]->snippet->title;

            // Playlist Items
            $uploads = abutube::playlist_items($section->playlistId)->items;

            $uploadsHTML = "";

            foreach ($uploads as $upload) {
                $uploadTitle = $upload->snippet->title;

                $uploadId = $upload->contentDetails->videoId;

                $uploadsHTML .= <<<HTML
                    <div>
                        <p><a href="/watch?v=$uploadId">$uploadTitle</a></p>
                    </div>
                HTML;
            }

            // Render
            $feedSections .= <<<HTML
                <h2>$title</h2>
                <hr>
                <div>
                $uploadsHTML
                </div>
            HTML;
        }
    }

echo <<<HTML
    $feedTitle
    $feedSections
HTML;