<?php

include "abutube.php";

print_r(
    abutubeRender::parse(
        abutube::channel_data(
            $_GET["d"]
        ),
        ["getContent" => "true"]
    )
);