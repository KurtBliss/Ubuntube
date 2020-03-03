?php

class abutubeRender
{
    $video = {
        id: "",
        title: "",
        thumbnail: "",
        desc: "",
        link: "",
        channel: {
            id: ""
            title: "",
            thumbnail: "",
            desc: "",
            link: ""
        }
    }

    $channel = {
        id: ""
        title: "",
        thumbnail: "",
        desc: "",
        link: "",
        uploads: "",
        sections: {
            key: 0,
            0: {
                id: "",
                title: "",
                link: "",
                type: "singlePlaylist|multiPlaylist|channels", // < program without need of type??
                layout: "horizontal|vertial"
            }
        }
    }

    $item = {
        id: "",
        title: "",
        thumbnail: "",
        desc: "",
        link: "/feed/main | /watch?v= | /channel/id | /users/rtu |",
        layout: "video|channel|section|feed"
    }

    parse([
        type: "playlist",

    ])

    function parse(data) {
        data = [
            "" => "",
            ...data
        ]
    }

    function pr_playlist() {
        return [
            part: 0, 
            maxResults: 25;
        ]
    }
}
