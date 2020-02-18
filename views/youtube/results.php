<?php

global $title;
global $content;
global $abutube;

$q = $_GET["q"];

$response = abutube::search($q);

$results = "";
$snippet;
$resultTitle = "";

foreach($response->items as $item) {
    if (isset($item->snippet))
        $resultTitle = $item->snippet->title;
    else
        $resultTitle = "";

    $results .= <<<HTML
        <div>
            <p>$resultTitle</p>
        </div>
    HTML;
}

$content = <<<HTML
    <main>
        <section>
            <p>Showing results for $q</p>
            $results
        </section>
    </main>
HTML;