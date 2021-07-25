<?php

require_once "abutube.php";
$channelId = $_POST["channelId"];
$response = channel_data($channelId);
$title = $response->items[0]->snippet->title;
$uploadsPlaylist = channel_parse_uploads($response);
echo feed_add_playlist_button(microtime(true), $uploadsPlaylist, $title);