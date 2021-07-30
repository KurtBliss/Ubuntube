<?php
$feedId = urldecode($id);
global $content;

$script = <<<JS
    renderFeed(feeds(), "$feedId");
JS;

$content = <<<HTML
    <main>
        <div id="feed-container">
        </div>
    </main>
    <script>$script</script>
HTML;