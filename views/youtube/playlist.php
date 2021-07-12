<?php

$playlistId = $_GET["list"];

$render = itemRender(
    parse(
        playlist_items(
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