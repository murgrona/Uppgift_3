<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$rappers = loadJson("../rap_name.json");

if ($requestMethod === "GET") {
    //Hämta rappare med egen nyckel (titles)
    if (isset($_GET["titles"])) {
        $rapTitle = explode(",",$_GET["titles"]);
        $titleArray = [];
        $found = true;
        foreach ($rappers as $rapT) {
            if (in_array($rapT["title"], $rapTitle)) {
                $titleArray[] = $rapT;
            }
        }
    }
    
    

    $found = false;
    //Hämta begränsat antal rappare
    if (isset($_GET["limit"])) {
        $found = true;
        $limit = $_GET["limit"];
        $slicedRapper = array_slice($rappers, 0, $limit);
        sendJson($slicedRapper);
    }
    // Hämta rappare beroende på id
    if (isset($_GET["ids"])) {
        $found = true;
        $ids = explode(",",$_GET["ids"]);
        $rappersId = [];
        foreach ($rappers as $rapper) {
            if (in_array($rapper["id"], $ids)) {
                $rappersId[] = $rapper;
            }
        }
        if ($found === false) {
            sendJson(
                [
                    "code" => 2,
                    "message" => "Does not exist"
                ],
                404
            );
        }
        sendJson($rappersId);
    }
    // Hämta alla rappare
    sendJson($rappers);
}

$contentType = $_SERVER["CONTENT_TYPE"];

// Checka contentType
if ($contentType !== "application/json") {
    sendJson(
        ["message" => "The API only accepts JSON"],
        400
    );
}
?>