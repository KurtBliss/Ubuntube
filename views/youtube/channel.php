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

$response = channel_data($id);

$title = $response->items[0]->snippet->title;
$desc = $response->items[0]->snippet->description;
$thumbnails =  $response->items[0]->snippet->thumbnails;

$thumbnail = $thumbnails->default->url;
// || $thumbnails->high->url 
// || $thumbnails->medium->url;

$uploadsPlaylist = channel_parse_uploads($response);

$uploads = playlist_items(
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
    updateFeedSelect();
    updateSectionSelect(Object.keys(feeds())[0]);
JS;

$feedButton = feed_add_playlist_button($key, $uploadsPlaylist, $title);

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
                | $feedButton
            <p>
            <input type="text" hidden value="$uploadsPlaylist">
            <div>
                $uploadsHTML
            </div>
        </section>
        <script>$script</script>
    </main>
HTML;