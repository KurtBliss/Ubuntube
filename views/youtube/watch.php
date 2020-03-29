<?php

$videoId = $_GET["v"];

$response = abutube::video_data($videoId);

$videoTitle = $response->items[0]->snippet->title;
$videoDesc = $response->items[0]->snippet->description;
$channelTitle = $response->items[0]->snippet->channelTitle;
$channelId = $response->items[0]->snippet->channelId;

$response = abutube::channel_data($channelId);
$channelThumbnails = $response->items[0]->snippet->thumbnails;
$channelThumbnail = $channelThumbnails->default->url;

global $content;

$content = <<<HTML
    <main>
        <section>
            <p class="section-title">$videoTitle</p>

            <div class="youtube-video-container">
                <iframe class="youtube-video-player" src="https://www.youtube.com/embed/$videoId" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div>
                
                <p class="watch-channel-title">
                    <a href="/channel/$channelId">$channelTitle</a>
                </p>
                
                <img class="watch-channel-thumbnail" src="$channelThumbnail">
                
                <p>$videoDesc</p>
            </div>
        </section>
    </main>
HTML;