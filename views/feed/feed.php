<?php
$feedId = $id;
global $content;

$script = <<<JS
    renderFeed(feeds(), "$feedId");
JS;

$content = <<<HTML
    <main>
        <section id="feed-container">
        </section>
    </main>
    <script>$script</script>
HTML;