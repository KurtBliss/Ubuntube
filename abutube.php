<?php

// require_once 'vendor/autoload.php';
// require_once 'vendor/google/apiclient-services/src/YouTube.php';
// require_once "youtube.php";

$secret = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/secret.json"));

if (!isset($_ENV["YOUTUBE_DEV_KEY"])) {
    $_ENV["YOUTUBE_DEV_KEY"] = $secret->devKey;
}
if (!isset($_ENV["YOUTUBE_CLIENT_ID"])) {
    $_ENV["YOUTUBE_CLIENT_ID"] = $secret->clientId;
}
if (!isset($_ENV["YOUTUBE_CLIENT_SECRET"])) {
    $_ENV["YOUTUBE_CLIENT_SECRET"] = $secret->clientSecret;
}


const THUMB_MED = "medium";
const THUMB_DEF = "default";

function thumbnail($data, $quality)
{
    switch ($quality) { //$response->snippet->thumbnails   ->medium->url

        case "default":
            return $data->default->url;
            break;
        case "medium":
            return $data->medium->url;
            break;
        default:
            echo "missing thumbnail $quality";
            break;
    }
}


function search($q, $maxResults = 25)
{
    return youtube("search", [
        "q" => $q,
        "part" => "snippet",
        "maxResults" => $maxResults
    ]);
}

function video_data($id)
{
    return youtube("videos", [
        "id" => $id,
        "part" => "snippet,contentDetails,statistics"
    ]);
}

function playlist_data($id)
{
    return youtube("playlists", [
        "id" => $id,
        "part" => "snippet,contentDetails"
    ]);
}

function playlist_items($id, $max = 5)
{
    return youtube("playlistItems", [
        "playlistId" => $id,
        "part" => "snippet,contentDetails",
        "maxResults" => $max
    ]);
}

function channel_data($channelId)
{
    return youtube("channels", [
        "id" => $channelId,
        "part" => "snippet,contentDetails"
    ]);
}

function channel_get_uploads($channelId)
{
    return channel_parse_uploads(channel_data($channelId));
}

function channel_parse_uploads($response)
{
    return $response->items[0]->contentDetails->relatedPlaylists->uploads;
}


function feed_add_playlist_button($key, $uploadsPlaylist, $title)
{
    $ren = <<<HTML
        <select id="select-feed-$key" class="addToFeed" onchange='updateFeedSelect();updateSectionSelect(Object.keys(feeds())[0]);'> 
                </select> <select id="select-section-$key" class="addToSection"  > 
                </select> 
                <button id="select_feed_button_$key" onclick="feed_add_playlist($key,'$uploadsPlaylist', '$title Uploads')">add to feed </button>
    HTML;

    return $ren;
}

function subscriptions($token)
{
    echo "getting subscriptions!";
    return youtube_auth("subscription", [
        "part" => "snippet,contentDetails",
        "mine" => "true"
    ], $token);
}

function channel_sections($id)
{
    return youtube("channelSections", [
        "channelId" => $id,
        "part" => "snippet,contentDetails,targeting",
        "hl" => "en_US"
    ]);
}

function videos_details($videos)
{
    $return_videos = $videos;
    $video_ids = [""];
    $calls = 1;
    $pos = 0;
    foreach ($return_videos as $key => $vid) {
        $video_ids[$calls - 1] .= $vid["id"] . ",";
        $pos += 1;
        if ($pos == 49) {
            $pos = 0;
            $calls += 1;
        }
    }
    // $youtube_path = $_SERVER['DOCUMENT_ROOT'] . "/youtube.php";
    // require_once $youtube_path;
    // require_once __DIR__ . '/vendor/autoload.php';

    $i = 0;
    $details = [];
    while ($calls > 0) {
        $details[] = youtube_videos_details([
            "id" => $video_ids[$i]
        ])->items;
        $i += 1;
        $calls -= 1;
    }

    $out1 = json_encode($details[0]);
    $out2 = json_encode($return_videos[0]);

    foreach ($details as $keyGroup => $detailGroup) {
        // get key.... map to video_ids
        foreach ($detailGroup as $key => $detail) {
            foreach ($return_videos as $pos => $video) {
                $video_detail_id = $detail->id;
                $video_return_id = $video["id"]; //json_encode($video);

                // echo "<br>$video_detail_id - <textarea> $video_return_id </textarea>";

                if ($video_detail_id == $video_return_id) {
                    // echo $detail->contentDetails;
                    // $return_videos[$pos]["details"] = $detail;
                    $return_videos[$pos]["duration"] = parse_duration($detail->contentDetails->duration); //parse_duration($detail);
                    break;
                }
            }
        }
    }

    $out3 = json_encode($return_videos[0]);

    // echo <<<HTML
    //     <div>
    //         o1<textarea>$out1</textarea>
    //         o2<textarea>$out2</textarea>
    //         o3<textarea>$out3</textarea>
    //     </div>
    // HTML;


    // TODO: place video details in given array

    return $return_videos;
}

function youtube_videos_details($queryParams = [
    'id' => 'Ks-_Mh1QhMc,c0KYU2j0TM4,eIho2S0ZahI'
])
{
    if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
        throw new Exception(sprintf('Please run "composer require google/apiclient:~2.0" in "%s"', __DIR__));
    }
    require_once __DIR__ . '/vendor/autoload.php';
    $client = new Google_Client();
    $client->setApplicationName('API code samples');
    $client->setScopes([
        'https://www.googleapis.com/auth/youtube.readonly',
    ]);
    $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/client_secret.json');
    $client->setAccessType('offline');
    session_start();
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_YouTube($client);
    // $queryParams 
    $response = $service->videos->listVideos('snippet,contentDetails', $queryParams);
    return $response;
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

    // echo " ecncode: " . $encode;

    return json_decode(
        file_get_contents(
            $encode
        )
    );
}

function youtube_auth($resource, $params, $token)
{

    $parse = "";
    foreach ($params as $key => $param) {
        if (isset($param)) {
            $parse .= "&" . $key . "=" . $param;
        }
    }
    // --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
    // --header 'Accept: application/json' 

    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Authorization: Bearer $token \r\n" .
                "Accept: application/json\r\n"
        )
    );

    $context = stream_context_create($opts);

    $encode =
        "https://www.googleapis.com/youtube/v3/"
        . $resource
        . "?key=" .  $_ENV["YOUTUBE_DEV_KEY"]
        . str_replace(" ", "%20", $parse);

    // Open the file using the HTTP headers set above
    echo $encode . " \n";
    print($context);
    $file = file_get_contents($encode, false, $context);

    return json_decode($file);
}


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

function parse_duration($d = "PT34H17M3S")
{
    $pos = 0;
    $hr = "";
    $min = "00";
    $sec = "00";
    $d = str_replace("PT", "", $d);
    $has_h = substr_count($d, "H");
    $has_m = substr_count($d, "M");
    $has_s = substr_count($d, "S");
    if ($has_h > 0) {
        $len = strpos($d, "H");
        $hr = substr($d, $pos, $len - $pos);
        $hr .= ":";
        $pos = $len + 1;
    }
    if ($has_m > 0) {
        $len = strpos($d, "M");
        $min = substr($d, $pos, $len - $pos);
        if ($has_h > 0 && strlen($min) == 1) {
            $min = "0" . $min;
        }
        $pos = $len + 1;
    }
    if ($has_s > 0) {
        $len = strpos($d, "S");
        $sec = substr($d, $pos, $len - $pos);
        if ($has_m > 0 && strlen($sec) == 1) {
            $sec = "0" . $sec;
        }
    }
    return "$hr$min:$sec";
}

function itemDataParams($type, $id, $title, $thumbnail, $desc, $link, $channelTitle = "", $channelId = "", $channelLink = "", $duration = "")
{
    return [
        "type" => $type, // channel | video | playlist
        "id" => $id,
        "title" => $title,
        "thumbnail" => $thumbnail,
        "desc" => $desc,
        "link" => $link,
        "channelTitle" => $channelTitle,
        "channelId" => $channelId,
        "channelLink" => $channelLink,
        "duration" => $duration,
        "details" => []
    ];
}

function itemRender($data = [], $layout = "list", $feed_button = false, $quality = "default")
{
    $render = "";


    switch ($layout) {
        case "horizontal":
            foreach ($data as $item) {
                // Data from item
                $type = $item["type"];
                $id = $item["id"];
                $title = $item["title"];
                $thumbnail = thumbnail($item["thumbnail"], $quality);
                $desc = $item["desc"];
                $link = $item["link"];
                $channelTitle = $item["channelTitle"];
                // $channelId = $item["channelId"];
                $channelLink = $item["channelLink"];
                $duration = $item["duration"];

                // echo $type;

                if ($type == "youtube#channel") {
                    $imgClass = "img-round";
                } else {
                    $imgClass = "";
                }

                $render .= <<<HTML
                        <a class="list-item-container" href=$link>
                            <div class="list-item">
                                <img class="$imgClass" src=$thumbnail>
                                <div class="list-item-dur">$duration</div>
                                <p class="list-item-title" title='$title'>$title</p>
                                <a class="list-item-chan-container" href="$channelLink"><p class="list-item-chan">$channelTitle </p></a>
                            </div>
                        </a>
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

            // case "horizontal":
            //     foreach ($data as $item) {
            //         // Data from item
            //         $type = $item["type"];
            //         $id = $item["id"];
            //         $title = $item["title"];
            //         $thumbnail = $item["thumbnail"];
            //         $desc = $item["desc"];
            //         $link = $item["link"];

            //         // echo $type;

            //         if ($type == "youtube#channel") {
            //             $imgClass = "img-round";
            //         } else {
            //             $imgClass = "";
            //         }

            //         $render .= <<<HTML
            //                 <div class="list-item">
            //                     <img class="$imgClass" src=$thumbnail>
            //                     <p><a href=$link>$title</a></p>
            //                 </div>
            //             HTML;
            //     }
            //     $render = <<<HTML
            //         <div class="list-horizontal">
            //             <div class="list-horizontal-left" onclick="slideBack()"> < </div>
            //             $render
            //             <div class="list-horizontal-right" onClick="slide()"> > </div>
            //         </div>
            //     HTML;
            //     break;

        case "grid":
            $key = -1;
            foreach ($data as $item) {
                // Data from item
                $key += 1;
                $type = $item["type"];
                $id = $item["id"];
                $title = $item["title"];
                $thumbnail = $thumbnail = thumbnail($item["thumbnail"], $quality); //$item["thumbnail"];
                $desc = $item["desc"];
                $link = $item["link"];

                // echo $type;

                if ($type == "youtube#channel") {
                    $imgClass = "img-round";
                } else {
                    $imgClass = "";
                }

                $render_feed_button = "";

                if ($feed_button) {
                    $render_feed_button = <<<HTML
                        <button 
                            id="feed_button_$key" 
                            onclick='feed_add_channel("$id", "feed_sec_$key");
                                document.getElementById("feed_button_$key").style.display="none";'>
                                add to feed
                        </button>
                    HTML;
                }

                $render .= <<<HTML
                        <a href=$link>
                            <div class="list-grid-item">
                                <img class="$imgClass list-grid-item-image" src=$thumbnail>
                                <p class="list-grid-item-title"><a href=$link>$title</a></p>
                                <!-- <p class="list-grid-item-description">$desc</p> -->
                                $render_feed_button
                                <div id="feed_sec_$key"></div>
                            </div>
                        </a>
                    HTML;
            }
            $render = <<<HTML
                <div class="list-grid">
                    <!-- <div class="list-horizontal-left" onclick="slideBack()"> < </div> -->
                    $render
                    <!-- <div class="list-horizontal-right" onClick="slide()"> > </div> -->
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
                $thumbnail = $thumbnail = thumbnail($item["thumbnail"], $quality); //$item["thumbnail"];
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
                        <img class="$imgClass" src=$thumbnail class="gird-i">
                        <p class="grid-p"><a href=$link class="grid-a">$title</a></p>
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
                            parse(playlist_items($item->contentDetails->relatedPlaylists->uploads))
                        );
                    }
                    return $parse;
                } else {
                    // Get channel information
                    $parse = array_merge($parse, parse($response->items[0]));
                }
                break;
            case "youtube#channel":
                $parse = array_merge($parse, itemDataParams(
                    $response->kind,
                    $response->id,
                    $response->snippet->title,
                    $response->snippet->thumbnails,
                    $response->snippet->description,
                    "/channel/$response->id"
                ));
                break;
            case "youtube#subscription":
                $parse = array_merge($parse, itemDataParams(
                    $response->kind,
                    $response->snippet->resourceId->channelId,
                    $response->snippet->title,
                    $response->snippet->thumbnails,
                    $response->snippet->description,
                    "/channel/" . $response->snippet->resourceId->channelId
                ));
                break;
            case "youtube#channelSection":

                break;
            case "youtube#playlist":
                if ($set["getContent"]) {
                    foreach ($response->items as $item) {
                        $parse = array_merge($parse, parse(playlist_items($item->id)));
                    }
                } else {
                    $parse = array_merge($parse, itemDataParams(
                        $response->kind,
                        $response->id,
                        $response->snippet->title,
                        $response->snippet->thumbnails,
                        $response->snippet->description,
                        "/playlist?list=$response->id"
                    ));
                }
                break;
            case "youtube#playlistItemListResponse":
            case "youtube#searchListResponse":
            case "youtube#SubscriptionListResponse":
                foreach ($response->items as $item) {
                    $parse[] = parse($item);
                }
                break;
            case "youtube#playlistItem":
                $parse = array_merge($parse, itemDataParams(
                    $response->kind,
                    $response->snippet->resourceId->videoId, //video-id?
                    $response->snippet->title,
                    $response->snippet->thumbnails,
                    $response->snippet->description,
                    "/watch?v=" . $response->snippet->resourceId->videoId,
                    $response->snippet->channelTitle,
                    $response->snippet->channelId,
                    "/channel/" . $response->snippet->channelId
                ));
                break;
            case "youtube#searchResult":
                switch ($response->id->kind) {
                    case "youtube#video":
                        $link = "/watch?v=" . $response->id->videoId;
                        $parse = itemDataParams(
                            $response->id->kind,
                            $response->id->videoId,
                            $response->snippet->title,
                            $response->snippet->thumbnails,
                            $response->snippet->description,
                            $link
                        );
                        break;
                    case "youtube#channel":
                        $link = "/channel/" . $response->id->channelId;
                        $parse = itemDataParams(
                            $response->id->kind,
                            $response->id->channelId,
                            $response->snippet->title,
                            $response->snippet->thumbnails,
                            $response->snippet->description,
                            $link
                        );
                        break;
                }


                break;
            default:
                echo "Missing item kind (" . $response->kind . ") !";
                $dump = json_encode($response);
                echo $dump;
                echo <<<HTML
                    <script>
                        console.log(`$dump`);
                    </script>
                HTML;
        }
    } else {
        switch ($set["type"]) {
            case "feed":
                $parse = array_merge($parse, parse(playlist_items($response)));
            default:
        }
    }

    return $parse;
}

function google_auth_redirect()
{
    $id = $_ENV["YOUTUBE_CLIENT_ID"];
    // $sec = $_ENV["YOUTUBE_CLIENT_SECRET"];
    $url = "https://accounts.google.com/o/oauth2/v2/auth?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fyoutube.readonly&include_granted_scopes=true&state=state_parameter_passthrough_value&redirect_uri=http%3A%2F%2Flocalhost%2Foauth2callback&response_type=token&client_id=$id";
    return $url;
}

// function google_auth_js()


function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function processP20($process)
{
    return str_replace(" ", "%20", $process);
}