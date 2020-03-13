<?php

global $title, $content, $abutube;

$q = $_GET["q"];

$response = abutube::search(urlencode($q));

$results = "";
foreach ($response->items as $item) {
    if (isset($item->snippet)) {
        $resultTitle = $item->snippet->title;

        if ($item->id->kind == "youtube#video") {
            $resultLink = "/watch?v=" . $item->id->videoId;
            $resultType = "video";
        } else if ($item->id->kind == "youtube#channel") {
            $resultLink = "/channel/" . $item->id->channelId;
            $resultType = "channel";
        }
    } else {
        $resultTitle = "";
        $resultType = "Unknown";
    }

    $results .= <<<HTML
        <div class="result-item result-item-$resultType">
            <a href="$resultLink">$resultTitle</a> | <span>$resultType</span>
        </div>
    HTML;
}

$content = <<<HTML
    <main>
        <section>
            <p>Showing results for "$q"</p>
            $results
        </section>
    </main>
HTML;