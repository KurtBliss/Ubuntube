<?php

global $title, $content, $abutube;

$q = $_GET["q"];

$render = abutubeRender::itemRender(
    abutubeRender::parse(
        abutube::search(
            $q,
            50
        )
    )
);

$content = <<<HTML
    <main>
        <section>
            <p>Showing results for "$q"</p>
            $render
        </section>
    </main>
HTML;