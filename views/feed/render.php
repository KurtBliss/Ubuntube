<?php

$JSON = json_decode($_POST["data"]);

print(<<<HTML
        <h1>$JSON->name</h1>
        
    HTML);