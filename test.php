<?php

echo <<<HTML

<script src="/index.js"></script>

<script>
    var feedsObj = {
        "Main": {
            name: "main",
            sections: [
                {name: "news", playlists: ["3frh84ew8u3"]},
                {name: "gaming", playlists: ["fjee4w8fja3", "fejqwa8932fea"]},
                {name: "Ashleys", feeds: "Ashleys"}
            ]
        },
        "Ashleys": {
            name: "Ashleys",
            sections: [
                {name: "news", playlists: ["jf38feuwjaq3", "fdje89f3j28efda", "fjh8498feowaj4"]},
                {name: "gaming", playlists: ["fhj348fjweoa", "fje480oqj3fi4ejaw093", "dje9w0fjiw34fea"]}
            ]
        }
    }

    feeds(feedsObj)
</script>

HTML;