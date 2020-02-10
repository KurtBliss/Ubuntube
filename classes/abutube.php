<?php

require_once "vendor/autoload.php";

use GuzzleHttp\Client;


class abutube {
    private $secretJson, $secretKey;

    public $client;

    function __construct() {
        // Secrets from github
        $this->secretJson =  json_decode(file_get_contents("secret.json"));
        $this->secretKey = $this->secretJson->devKey;

        // Init for making http request
        $this->client = new Client([
            "base_uri" => "https://www.googleapis.com/youtube/v3",
            "timeout"  => 2.0
        ]);
    } 

    function search() {
        
        echo $this->client->request("GET", "search", [
                "form_params" => [
                "key" => $this->secretKey,
                "q" => "pokemon",
                "part" => "snippet",
                "maxResults" => 25,]
        ]);
    }
}

