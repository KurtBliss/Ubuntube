<?php
require_once "vendor/autoload.php";

$_ENV["YOUTUBE_DEV_KEY"] = json_decode(file_get_contents("secret.json"))->devKey;

class abutube
{
    function search($q, $maxResults = 25)
    {
        return abutube::youtube("search", [
            "q" => $q,
            "part" => "snippet",
            "maxResults" => $maxResults
        ]);
    }

    function video_data($id)
    {
        return abutube::youtube("videos", [
            "id" => $id,
            "part" => "snippet,contentDetails,statistics"
        ]);
    }

    function playlist_data($id)
    {
        return abutube::youtube("playlists", [
            "id" => $id,
            "part" => "snippet,contentDetails"
        ]);
    }

    function playlist_items($id, $max = 25)
    {
        return abutube::youtube("playlistItems", [
            "playlistId" => $id,
            "part" => "snippet,contentDetails",
            "maxResults" => $max
        ]);
    }

    function channel_data($id)
    {
        return abutube::youtube("channels", [
            "id" => $id,
            "part" => "snippet,contentDetails"
        ]);
    }

    function channel_sections($id)
    {
        return abutube::youtube("channelSections", [
            "channelId" => $id,
            "part" => "snippet,contentDetails,targeting",
            "hl" => "en_US"
        ]);
    }

    function youtube($resource, $params)
    {
        $parse = "";
        foreach ($params as $key => $param) {
            if (isset($param)) {
                $parse .= "&" . $key . "=" . $param;
            }
        }

        $encode =
            "https://www.googleapis.com/youtube/v3/"
            . $resource
            . "?key=" .  $_ENV["YOUTUBE_DEV_KEY"]
            . $parse;

        // htmlentities($encode);
        // urlencode($encode);

        return json_decode(
            file_get_contents(
                $encode
            )
        );
    }

    function escapefile_url($url)
    {
        $parts = parse_url($url);
        $path_parts = array_map('rawurldecode', explode('/', $parts['path']));

        return
            $parts['scheme'] . '://' .
            $parts['host'] .
            implode('/', array_map('rawurlencode', $path_parts));
    }
}