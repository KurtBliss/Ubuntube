<?php

require_once "abutube.php";

if (array_key_exists('channelId', $_POST)) {
    $channelId = $_POST["channelId"];
    $response = channel_data($channelId);
    $title = $response->items[0]->snippet->title;

    $uploadsPlaylist = channel_parse_uploads($response);
    echo feed_add_playlist_button(microtime(true), $uploadsPlaylist, $title);
} elseif (array_key_exists('channelIds', $_POST)) {
    $channelId = $_POST["channelIds"];
    $response = channel_data($channelId);

    $playlist = [];
    foreach ($response->items as $res) {

        $playlist[] = $res->contentDetails->relatedPlaylists->uploads;
    }

    echo json_encode($playlist);
}