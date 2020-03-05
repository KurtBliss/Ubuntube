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
    function itemData($data = [])
    {
        return array_merge(
            [
                "type" => "channel", // channel | video | playlist
                "id" => "",
                "title" => "",
                "thumbnail" => "",
                "desc" => "",
                "link" => ""
            ],
            $data
        );
    }

    function itemDataParams($type, $id, $title, $thumbnail, $desc, $link)
    {
        return [
            "type" => $type, // channel | video | playlist
            "id" => $id,
            "title" => $title,
            "thumbnail" => $thumbnail,
            "desc" => $desc,
            "link" => $link
        ];
    }

    function itemRender($data = [], $layout = "list")
    {
        $render = "";
        foreach ($data as $item) {

            // Data from item
            $type = $item["type"];
            $id = $item["id"];
            $title = $item["title"];
            $thumbnail = $item["thumbnail"];
            $desc = $item["desc"];
            $link = $item["link"];

            // Render item
            switch ($layout) {
                case "list":
                default:
                    $render .= <<<HTML
                        <div>
                            <img src=$thumbnail>
                            <p><a href=$link>$title</a></p>
                            <small>$type</small>
                            <p>$desc</p>
                        </div>
                    HTML;
                    break;
            }
        }
    }

    function parse($response, $settings = ["type" => "auto", "getContent" => "true", "isGroup" => "true"])
    // $type = "auto", $getItemContent = "true")
    {
        $parse = [];
        if ($settings["type"] === "auto") {
            switch ($response->kind) {
                case "youtube#channelListResponse":
                    if ($settings["getContent"]) {
                        foreach ($response->items as $item) {
                            // Get channel content
                            $parse[] = abutubeRender::parse(abutube::playlist_items($item->contentDetails->relatedPlaylists->uploads));
                        }
                        return $parse;
                    } else {
                        // Get channel information
                        $parse[] = abutubeRender::parse($item);
                    }
                    break;
                case "youtube#channel":
                    $parse[] = abutubeRender::itemDataParams(
                        $item->kind,
                        $item->id,
                        $item->snippet->title,
                        $item->snippet->thumbnails->default->url,
                        $item->snippet->description,
                        "/channel/$item->id"
                    );
                    break;
                case "youtube#channelSection":

                    break;
                case "youtube#playlist":
                    if ($settings["getContent"]) {
                    } else {
                        $parse[] = abutubeRender::itemDataParams(
                            $item->kind,
                            $item->id,
                            $item->snippet->title,
                            $item->snippet->thumbnails->default->url,
                            $item->snippet->description,
                            "/playlist?list=$item->id"
                        );
                    }
                    break;
                case "youtube#playlistItem":
                    $parse[] = [];
                    break;
                default:
                    echo "Missing item kind " . $response->kind;
            }
        }

        switch ($settings->type) {
            default:
        }
    }
}