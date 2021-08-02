<?php
echo parse_duration();
function parse_duration($d = "PT34H17M3S")
{
    $pos = 0;
    $hr = "";
    $min = "00";
    $sec = "00";
    $d = str_replace("PT", "", $d);
    $has_h = substr_count($d, "H");
    $has_m = substr_count($d, "M");
    $has_s = substr_count($d, "S");
    if ($has_h > 0) {
        $len = strpos($d, "H");
        $hr = substr($d, $pos, $len - $pos);
        $hr .= ":";
        $pos = $len + 1;
    }
    if ($has_m > 0) {
        $len = strpos($d, "M");
        $min = substr($d, $pos, $len - $pos);
        if ($has_h > 0 && strlen($min) == 1) {
            $min = "0" . $min;
        }
        $pos = $len + 1;
    }
    if ($has_s > 0) {
        $len = strpos($d, "S");
        $sec = substr($d, $pos, $len - $pos);
        if ($has_m > 0 && strlen($sec) == 1) {
            $sec = "0" . $sec;
        }
    }
    return "$hr $min : $sec";
}





// if ($has_h > 0) {
    //     return $d;
    // } else {
    //     $d_pos = strpos($d, ":");
    //     $first = substr($d, 0, $d_pos + 1);
    //     $zeroit = substr($d, $d_pos + 1, strlen($d));
    //     if (substr_count($zeroit, ":") == 0) {
    //         if (strlen($zeroit) == 1) {
    //             $zeroit = "0" . $zeroit;
    //         }
    //     }
    //     return $first . $zeroit;
    // }