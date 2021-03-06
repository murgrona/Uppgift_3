<?php

function sendJson($data, $statusCode=200) {
    header("Content-Type: application/json");
    http_response_code($statusCode);

    $json = json_encode($data);
    echo $json;
    exit();
}

function loadJson($filename) {
    if(file_exists($filename)) {
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        return $data;
        exit();
    }else {
        return false;
    }
}

function saveJson ($filename, $data) {
    if(file_exists($filename)) {
        file_put_contents($filename,
        json_encode($data, JSON_PRETTY_PRINT));
    }
}

function arrayInsert($array, $position, $insertArray){
    $ret = [];

    if ($position == count($array)) {
        $ret = $array + $insertArray;
    }
    else {
        $i = 0;
        foreach ($array as $key => $value) {
            if ($position == $i++) {
                $ret += $insertArray;
            }

            $ret[$key] = $value;
        }
    }

    return $ret;
}


?>
