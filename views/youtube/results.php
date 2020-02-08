<?php

$client = new Google_Client();
$client->setDeveloperKey($devKey);

$youtube = new Google_Service_YouTube($client);

$searchResponse = $youtube->search->listSearch('id,snippet', [
    'q' => $_GET['q'],
    'maxResults' => $_GET['maxResults'],
]);

$videos = '';
$channels = '';
$playlists = '';

foreach ($searchResponse['items'] as $searchResult) {
    switch ($searchResult['id']['kind']) {
        case 'youtube#video':
            $videos .= sprintf(
                '<li>%s (%s)</li>',
                $searchResult['snippet']['title'],
                $searchResult['id']['videoId']
            );
            break;
        case 'youtube#channel':
            $channels .= sprintf(
                '<li>%s (%s)</li>',
                $searchResult['snippet']['title'],
                $searchResult['id']['channelId']
            );
            break;
        case 'youtube#playlist':
            $playlists .= sprintf(
                '<li>%s (%s)</li>',
                $searchResult['snippet']['title'],
                $searchResult['id']['playlistId']
            );
            break;
    }
}

// make search into function 
// make item function... 

$q = "";

global $content;

$content = <<<HTML
    <main>
        <section>
            <p>Showing results for $q</p>
        </section>
    </main>
HTML;