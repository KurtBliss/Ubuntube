<?php
global $title, $content;
$title = "Home - Abutube";

$noFeeds = <<<HTML
     <p>Looks like you have no feeds saved</p> 
HTML;

$script = <<<JS
    var feedsObj = feeds();
    
    for (const feed in feedsObj) {

        appendContainer('<p><a href=feed/' + feed + '>' + feedsObj[feed]["name"] + '</a></p>', "feeds-list");

        console.log(feedsObj[feed]);
    }    
JS;

$content = <<<HTML
    <main>
        <section>
            <h1 class="sectionTitle">Your feeds:</h1>
            <div id="feeds-list"></div>

            <h1 class="sectionTitle">Make new feed:</h1>
            <input type="text" id="newFeedInput" onkeypress="onFeedNew(event)">
        </section>
        <script>$script</script>
    </main>
HTML;