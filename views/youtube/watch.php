<?php

$videoId = $_GET["v"];

global $content;

$content = <<<HTML
    <main>
        <section>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/$videoId" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </section>
    </main>
HTML;