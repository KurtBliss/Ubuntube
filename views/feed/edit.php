<?php
$feedId = urldecode($id);
global $content;

$script = <<<JS
    var feedsObj = feeds();

    if ("$feedId" in feedsObj) {
        document.getElementById("feedNameInput").value = feedsObj["$feedId"].name;
    } 

    renderFeed(feeds(), "$feedId", true);
JS;

$content = <<<HTML
    <main>
        <section>
            <h1>Name</h1>
            <input type="text" id="feedNameInput" onkeypress="onFeedName(event, '$feedId', true);" value="$feedId">
        </section>
        <section id="feed-container">
        </section>
    </main>
    <script>$script</script>
HTML;