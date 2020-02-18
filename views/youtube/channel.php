<?php

global $content;
// http://localhost:8080/channel/UCr3cBLTYmIK9kY0F_OdFWFQ

$response = abutube::channel_data($id);

$title = $response->items[0]->snippet->title;
$desc = $response->items[0]->snippet->description;
$thumbnails =  $response->items[0]->snippet->thumbnails;

$thumbnail = $thumbnails->default->url ;
    // || $thumbnails->high->url 
    // || $thumbnails->medium->url;

$uploads = abutube::playlist_items(
    $response->items[0]->contentDetails->relatedPlaylists->uploads
)->items;

$uploadsHTML = "";
$uploadTitle;

foreach($uploads as $upload) {

    $uploadTitle = $upload->snippet->title;

    $uploadId = $upload->contentDetails->videoId;

    $uploadsHTML .= <<<HTML
        <div>
            <p><a href="/watch?v=$uploadId">$uploadTitle</a></p>
        </div>
    HTML;
}

$content = <<<HTML
    <main>
        <section>
            <!-- Channel Information -->
            <p class="sectionTitle">$title</p>
            <img src=$thumbnail>
            <p>$desc</p>
        </section>
        <section>
            <p class="sectionTitle">Uploads<p>
            <div>
                $uploadsHTML
            </div>
        </section>
    </main>
HTML;