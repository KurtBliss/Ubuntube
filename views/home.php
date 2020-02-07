<?php

global $title, $content;

$title = "Home - Abutube";

$content = <<<HTML
    <main>
        <section>
            <p class="sectionTitle">Your feeds:</p>
            <p>Looks like you have no feeds saved</p> <!-- List saved feeds here -->

            <p class="sectionTitle">Make new feed:</p>
            <button>new feed</button>
        </section>
    </main>
HTML;