<?php
$_ENV["YOUTUBE_DEV_KEY"] = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/secret.json"))->devKey;

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

        return json_decode(
            file_get_contents(
                $encode
            )
        );
    }
}

class abutubeRender
{
    function itemData($data) { return array_merge([
            "type" => "channel", // channel | video | playlist
            "id" => "",
            "title" => "",
            "thumbnail" => "",
            "desc" => "",
            "link" => ""
        ],
        $data
    );}

    function itemRender($data) {
        $render = "";
        for ($data as $item) {
            
            // Data from item
            $type = $item["type"];
            $id = $item["id"];
            $title = $item["title"];
            $thumbnail = $item["thumbnail"];
            $desc = $item["desc"];
            $link = $item["link"];

            // Render item
            $render .= <<<HTML
                <div>
                    
                </div>
            HTML;
        }
    }
}

