<?php

global $content;


// http://localhost:8080/channel/UCr3cBLTYmIK9kY0F_OdFWFQ

$key = 0;

function incKey()
{
    global $key;
    $key += 1;
    return $key;
}

$response = abutube::channel_data($id);

$title = $response->items[0]->snippet->title;
$desc = $response->items[0]->snippet->description;
$thumbnails =  $response->items[0]->snippet->thumbnails;

$thumbnail = $thumbnails->default->url;
// || $thumbnails->high->url 
// || $thumbnails->medium->url;

$uploadsPlaylist = $response->items[0]->contentDetails->relatedPlaylists->uploads;

$uploads = abutube::playlist_items(
    $uploadsPlaylist
)->items;

$uploadsHTML = "";
$uploadTitle;

foreach ($uploads as $upload) {

    $uploadTitle = $upload->snippet->title;

    $uploadId = $upload->contentDetails->videoId;

    $uploadsHTML .= <<<HTML
        <div>
            <p><a href="/watch?v=$uploadId">$uploadTitle</a></p>
        </div>
    HTML;
}

$script = <<<JS
    var feedsObj = feeds();
    var pickFeed = "";
    for (const feed in feedsObj) {
        pickFeed += "<option value='" + feed + "'>";
        pickFeed += feedsObj[feed]["name"];
        pickFeed += "</option>";
    }
    
    var elements = document.getElementsByClassName("addToFeed");

    for (var i = 0; i < elements.length; i++) {
        elements[i].innerHTML = pickFeed;
    }

    

JS;

$content = <<<HTML
    <main>
        <section>
            <!-- Channel Information -->
            <p class="sectionTitle">$title</p>
            <img src=$thumbnail>
            <p>$desc</p>
        </section>
        <section class="container">
            <p class="sectionTitle">
                Uploads 
                | <select id="select-$key" class="addToFeed" > 
                </select> 
                <button onclick="feed_add_single_playlist($key,'$uploadsPlaylist', '$title Uploads')">add to feed </button>
            <p>
            <input type="text" hidden value="$uploadsPlaylist">
            <div>
                $uploadsHTML
            </div>
        </section>
        <script>$script</script>
    </main>
HTML;