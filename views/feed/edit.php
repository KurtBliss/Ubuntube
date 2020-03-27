<?php
$feedId = $id;
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
            <label>feed name</label>
            <input type="text" id="feedNameInput" onkeypress="onFeedName(event, '$feedId')" value="$feedId">
        </section>
        <section id="feed-container">
        </section>
    </main>
    <script>$script</script>
HTML;