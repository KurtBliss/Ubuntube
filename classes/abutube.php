<?php
require_once "vendor/autoload.php";

class abutube {
    private $secretJson, $secretKey;
    function __construct() {
        $this->secretJson =  json_decode(file_get_contents("secret.json"));
        $this->secretKey = $this->secretJson->devKey;
    } 

    function search($q, $maxResults = 25) {
        return json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?key=" . $this->secretKey . "&q=$q&part=snippet&maxResults=$maxResults"));
    }

    function get($url, $params) {
        $varUrl = $url . "?";
        foreach($params as $param) {
            $varUrl .= $param . "&";
        }
    }
}

