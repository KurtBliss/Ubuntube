<?php
$feedId = $id;

global $content;

$script = <<<JS
    console.log(loadObject(document.getElementById("feedNameInput").value))

    if (loadObject(document.getElementById("feedNameInput").value) === null) {
        var feed = {
            name: "$feedId",
            id: "$feedId"
        };
    } else {
        var feed = loadObject(document.getElementById("feedNameInput").value);
        getElementById("feedNameInput").value = feed.name;
    }

    function onFeedName(event) {
        if (event.key === "Enter") {
            new_feed_name = document.getElementById("feedNameInput").value;
            console.log("new_feed_name", new_feed_name);
            feed.name = document.getElementById("feedNameInput").value;
            saveObject(feed.name, feed);
        } else {
            return false;
        }
    }

    function saveObject(name, object) {
        localStorage.setItem("obj_" + name, JSON.stringify(object));
    }

    function loadObject(name) {
        if (localStorage.getItem("obj_" + name) === null) 
            return null;
        return JSON.parse(localStorage.getItem("obj_" + name));
    }
JS;

$content = <<<HTML
    <script>$script</script>
    <main>
        <section>
            <label>feed name</label>
            <input type="text" id="feedNameInput" onkeypress="onFeedName(event)" value="$feedId">
        </section>
    </main>
HTML;