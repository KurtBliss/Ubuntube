<?php
global $content;
require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';
session_start();
$render = "";

try {
    include "youtube.php";

    $subscriptions = $youtube->subscriptions->listSubscriptions('snippet,contentDetails', array('mine' => "true", 'maxResults' => 50));

    $render = itemRender(
        parse(
            $subscriptions
        ),
        "grid",
        true
    );
    // $render .= json_encode($subscriptions);
} catch (exception $e) {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth_process.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

$script = <<<JS
    var que = [];
    var que_name = "";
JS;

$script_end = <<<JS
    updateFeedSelect();
    updateSectionSelect(Object.keys(feeds())[0]);
JS;

$feed_button = feed_add_playlist_button(0, "que", "que_name", true);

$render_add_to_feed = <<<HTML

$feed_button

HTML;

$content = <<<HTML
    <main>
        <script>$script</script>
        <section>
        $render
        $render_add_to_feed
        </section>
        <script>$script_end</script>
    </main>
HTML;