<?php

global $menu;

$script = <<<JS
    homeLoadFeeds("feeds-list-2");
JS;

$menu = <<<HTML

  <aside id="asideMenu">
    <ul>
      <li><a href="/auth">login</a></li>
      <li><a href="/subscriptions">subscriptions</a></li>
    </ul>

    <h1 class="feed-home-section">Your Feeds:</h1>
    <section>
        <div id="feeds-list-2"></div>
    </section>

    <h1 class="feed-home-section">Make New Feed:</h1>
    <section>
        <input type="text" id="newFeedInput" onkeypress="onFeedNew(event)"><button onclick="NewFeedButton()">Create</button>
    </section>
    
    <script>$script</script>

  </aside>
HTML;