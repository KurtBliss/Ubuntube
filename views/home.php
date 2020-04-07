<?php
global $title, $content;
$title = "Home - Abutube";

$noFeeds = <<<HTML
     <p>Looks like you have no feeds saved</p> 
HTML;

$script = <<<JS
    homeLoadFeeds()
JS;

$content = <<<HTML
    <main>
        <div class="g-signin2" data-onsuccess="onSignIn"></div>

        <h1 class="feed-home-section">Your Feeds:</h1>
        <section>
            <div id="feeds-list"></div>
        </section>

        <h1 class="feed-home-section">Make New Feed:</h1>
        <section>
            <input type="text" id="newFeedInput" onkeypress="onFeedNew(event)"><button onclick="NewFeedButton()">Create</button>
        </section>
        <div class="home-welcome">
            <h1 class="sectionTitle">Welcome!</h1>
            <img class="img-round" style="width:150px; height:150px" src="/logo.png">
            <p>Check out our <a href="https://github.com/KurtBliss/Ubuntube">github page</a></p>
        </div>
        <script>$script</script>
    </main>
HTML;