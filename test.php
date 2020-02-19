<?php

echo "running test.php\n";

require_once "abutube.php";

// $response = abutube::search($_GET["q"]);
$response = abutube::search("pokemon%20ruby");

print_r(
    $response
);

/*

Channel Title, Thumbnail

*/