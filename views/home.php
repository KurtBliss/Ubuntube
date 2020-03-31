<?php
global $title, $content;
$title = "Home - Abutube";

$noFeeds = <<<HTML
     <p>Looks like you have no feeds saved</p> 
HTML;

$script = <<<JS
    var feedsObj = feeds();
    
    for (const feed in feedsObj) {

        console.log("feed", feed)

        appendContainer('<a class="feed-home" target=_self href="feed/' + feed + '">' + feedsObj[feed]["name"] + '</a>', "feeds-list");

        console.log(feedsObj[feed]);
    }    
JS;

$content = <<<HTML
    <main>
        <h1 class="feed-home-section">Your Feeds:</h1>
        <section>
            <div id="feeds-list"></div>
        </section>

        <h1 class="feed-home-section">Make New Feed:</h1>
        <section>
            <input type="text" id="newFeedInput" onkeypress="onFeedNew(event)">
        </section>
        <div class="home-welcome">
            <h1 class="sectionTitle">Welcome!</h1>
            <img class="img-round" style="width:150px; height:150px" src="/logo.png">
            <p>Check out our <a href="https://github.com/KurtBliss/Ubuntube">github page</a></p>
        </div>
        <script>$script</script>
    </main>
HTML;