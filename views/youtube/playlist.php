<?php

$playlistId = $_GET["list"];

$render = abutubeRender::itemRender(
    abutubeRender::parse(
        abutube::playlist_items(
            $playlistId
        )
    )
);

global $content;
$content = <<<HTML
    <main>
        <section>
        $render;
        </section>
    </main>
HTML;