<?php

if (!isset($_ENV["YOUTUBE_DEV_KEY"])) {
    $_ENV["YOUTUBE_DEV_KEY"] = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/secret.json"))->devKey;
}

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

    function playlist_items($id, $max = 50)
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
            . str_replace(" ", "%20", $parse);

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

        switch ($layout) {
            case "horizontal":
                foreach ($data as $item) {
                    // Data from item
                    $type = $item["type"];
                    $id = $item["id"];
                    $title = $item["title"];
                    $thumbnail = $item["thumbnail"];
                    $desc = $item["desc"];
                    $link = $item["link"];

                    // echo $type;

                    if ($type == "youtube#channel") {
                        $imgClass = "img-round";
                    } else {
                        $imgClass = "";
                    }

                    $render .= <<<HTML
                            <div class="list-item">
                                <img class="$imgClass" src=$thumbnail>
                                <p><a href=$link>$title</a></p>
                            </div>
                        HTML;
                }
                $render = <<<HTML
                    <div class="list-horizontal">
                        <div class="list-horizontal-left" onclick="slideBack()"> < </div>
                        $render
                        <div class="list-horizontal-right" onClick="slide()"> > </div>
                    </div>
                HTML;
                break;

            case "list":
            default:
                foreach ($data as $item) {
                    // Data from item
                    $type = $item["type"];
                    $id = $item["id"];
                    $title = $item["title"];
                    $thumbnail = $item["thumbnail"];
                    $desc = $item["desc"];
                    $link = $item["link"];

                    // echo $type;

                    if ($type == "youtube#channel") {
                        $imgClass = "img-round";
                    } else {
                        $imgClass = "";
                    }

                    $render .= <<<HTML
                        <div>
                            <img class="$imgClass" src=$thumbnail>
                            <p><a href=$link>$title</a></p>
                        </div>
                    HTML;
                }
                break;
        }


        return $render;
    }

    function parse($response, $settings = ["type" => "auto", "getContent" => "true"])
    // $type = "auto", $getItemContent = "true")
    {
        $set = array_merge(["type" => "auto", "getContent" => "true"], $settings);

        $parse = [];
        if ($set["type"] === "auto") {
            switch ($response->kind) {
                case "youtube#channelListResponse":
                    if ($set["getContent"] === "true") {
                        foreach ($response->items as $item) {
                            // Get channel content
                            $parse = array_merge(
                                $parse,
                                abutubeRender::parse(abutube::playlist_items($item->contentDetails->relatedPlaylists->uploads))
                            );


                            // $parse[] = abutubeRender::parse(abutube::playlist_items($item->contentDetails->relatedPlaylists->uploads));
                        }
                        return $parse;
                    } else {
                        // Get channel information
                        $parse = array_merge($parse, abutubeRender::parse($response->items[0]));
                    }
                    break;
                case "youtube#channel":
                    $parse = array_merge($parse, abutubeRender::itemDataParams(
                        $response->kind,
                        $response->id,
                        $response->snippet->title,
                        $response->snippet->thumbnails->default->url,
                        $response->snippet->description,
                        "/channel/$response->id"
                    ));
                    break;
                case "youtube#channelSection":

                    break;
                case "youtube#playlist":
                    if ($set["getContent"]) {
                        foreach ($response->items as $item) {
                            $parse = array_merge($parse, abutubeRender::parse(abutube::playlist_items($item->id)));
                        }
                    } else {
                        $parse = array_merge($parse, abutubeRender::itemDataParams(
                            $item->kind,
                            $item->id,
                            $item->snippet->title,
                            $item->snippet->thumbnails->default->url,
                            $item->snippet->description,
                            "/playlist?list=$item->id"
                        ));
                    }
                    break;
                case "youtube#playlistItemListResponse":
                case "youtube#searchListResponse":
                    foreach ($response->items as $item) {
                        $parse[] = abutubeRender::parse($item);
                    }
                    break;
                case "youtube#playlistItem":
                    $parse = array_merge($parse, abutubeRender::itemDataParams(
                        $response->kind,
                        $response->snippet->resourceId->videoId, //video-id?
                        $response->snippet->title,
                        $response->snippet->thumbnails->default->url,
                        $response->snippet->description,
                        "/watch?v=" . $response->snippet->resourceId->videoId
                    ));
                    break;
                case "youtube#searchResult":
                    switch ($response->id->kind) {
                        case "youtube#video":
                            $link = "/watch?v=" . $response->id->videoId;
                            $parse = abutubeRender::itemDataParams(
                                $response->id->kind,
                                $response->id->videoId,
                                $response->snippet->title,
                                $response->snippet->thumbnails->default->url,
                                $response->snippet->description,
                                $link
                            );
                            break;
                        case "youtube#channel":
                            $link = "/channel/" . $response->id->channelId;
                            $parse = abutubeRender::itemDataParams(
                                $response->id->kind,
                                $response->id->channelId,
                                $response->snippet->title,
                                $response->snippet->thumbnails->default->url,
                                $response->snippet->description,
                                $link
                            );
                            break;
                    }


                    break;
                default:
                    echo "Missing item kind " . $response->kind . " ";
            }
        } else {
            switch ($set["type"]) {
                case "feed":
                    $parse = [];
                    // foreach ($response->section as $section) {
                    switch ($response->type) {
                        case "singlePlaylist":
                            $parse = array_merge($parse, abutubeRender::parse(abutube::playlist_items($response->playlistId)));
                            break;
                        default:
                            print_r(["Missing feed section type $response->type"]);
                    }
                    // abutubeRender::parse(abutube::)
                    // }
                    break;
                default:
            }
        }

        return $parse;
    }
}

function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}