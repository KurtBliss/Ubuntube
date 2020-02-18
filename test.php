<?php

echo "running test.php\n";

require_once "abutube.php";

$response = abutube::channel_data($_GET["id"]);

print_r(abutube::playlist_items(
    $response->items[0]->contentDetails->relatedPlaylists->uploads
));

/*

Channel Title, Thumbnail

*/