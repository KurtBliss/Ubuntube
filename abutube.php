<?php
require_once "vendor/autoload.php";

$_ENV["YOUTUBE_DEV_KEY"] = json_decode(file_get_contents("secret.json"))->devKey;

class abutube {
    private $secretJson, $secretKey;

    function search($q, $maxResults = 25) {
        return abutube::youtube("search", [
            "q" => $q, 
            "part" => "snippet", 
            "maxResults" => $maxResults
        ]);
    }

    function video_data($id) {
        return abutube::youtube("videos", [
            "id" => $id,
            "part" => "snippet,contentDetails,statistics"
        ]);
    }

    function playlist_data($id) {
        return abutube::youtube("playlists", [
            "id" => $id,
            "part" => "snippet,contentDetails"
        ]);
    }

    function playlist_items($id, $max = 25) {
        return abutube::youtube("playlistItems", [
            "playlistId" => $id,
            "part" => "snippet,contentDetails",
            "maxResults" => $max
        ]);
    }

    function channel_data($id) {
        return abutube::youtube("channels", [
            "id" => $id,
            "part" => "snippet,contentDetails"
        ]);
    }

    function channel_sections($id) {
        return abutube::youtube("channelSections", [
            "channelId" => $id,
            "part" => "snippet,contentDetails,targeting",
            "hl" => "en_US"
        ]);
    }

    function youtube($resource, $params) {
        $parse = "";
        foreach($params as $key => $param) {
            if (isset($param)) {
                $parse .= "&" . $key . "=" . $param;
            }
        }
        return json_decode(
            file_get_contents("https://www.googleapis.com/youtube/v3/" . $resource . "?key=" .  $_ENV["YOUTUBE_DEV_KEY"] . $parse
        ));
    }
}