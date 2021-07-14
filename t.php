<?

require_once 'vendor/autoload.php';
require_once 'vendor/google/apiclient-services/src/YouTube.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('client_secret.json');
$client->addScope(GOOGLE_SERVICE_YOUTUBE::YOUTUBE_FORCE_SSL);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $youtube = new Google_Service_YouTube($client);
    $channel = $youtube->subscriptions->listSubscriptions('snippet', array('mine' => "true"));
    // $channel = $youtube->channels->listChannels('snippet', array('mine' => $mine));
    echo json_encode($channel);
} else {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth_process.php';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}