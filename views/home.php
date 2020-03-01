<?php
global $title, $content;
$title = "Home - Abutube";

$noFeeds = <<<HTML
     <p>Looks like you have no feeds saved</p> 
HTML;

$script = <<<JS
    var feeds = feeds();

    function appendContainer(append, id) {
        document.getElementById(id).innerHTML += append;
    }

    for (const feed in feeds) {

        appendContainer('<p><a href=feed/' + feed + '>' + feeds[feed]["name"] + '</a></p>', "feeds-list");

        console.log(feeds[feed]);
    }    
JS;

$content = <<<HTML
    <main>
        <section>
            <h1 class="sectionTitle">Your feeds:</h1>
            <div id="feeds-list"></div>

            <h1 class="sectionTitle">Make new feed:</h1>
            <input type="text">
        </section>
        <script>$script</script>
    </main>
HTML;