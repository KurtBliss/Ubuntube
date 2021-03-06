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

$uploadsPlaylist = $response->items[0]->contentDetails->relatedPlaylists->uploads;

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
    updateFeedSelect()
    updateSectionSelect(Object.keys(feeds())[0])

    function updateFeedSelect() {
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
    }
    
    function updateSectionSelect(feed) {
        var feedsObj = feeds();
        var sections = feedsObj[feed]["sections"];
        console.log("got", sections)
        var pickSection = '<option value="-1">As New Section</option>';
        for (const section in sections) {
            console.log(section)
            pickSection += "<option value='" + section + "'>";
            pickSection += sections[section]["name"];
            pickSection += "</option>";
        }

        // Updated elements
        var elements = document.getElementsByClassName("addToSection");
        for (var i = 0; i < elements.length; i++) {
            elements[i].innerHTML = pickSection;
        }
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
                | <select id="select-feed-$key" class="addToFeed" > 
                </select> <select id="select-section-$key" class="addToSection" > 
                </select> 
                <button onclick="feed_add_playlist($key,'$uploadsPlaylist', '$title Uploads')">add to feed </button>
            <p>
            <input type="text" hidden value="$uploadsPlaylist">
            <div>
                $uploadsHTML
            </div>
        </section>
        <script>$script</script>
    </main>
HTML;