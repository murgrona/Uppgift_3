<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$records = loadJson("../record_company.json");


if ($requestMethod === "GET") {
    //Hämta begränsat antal skivbolag
    if (isset($_GET["limit"])) {
        $limit = $_GET["limit"];
        $slicedRecord = array_slice($records, 0, $limit);
        sendJson($slicedRecord);
    }
    // Hämta skivbolag beroende på id
    if (isset($_GET["ids"])) {
        $ids = explode(",",$_GET["ids"]);
        $recordId = [];

        foreach ($records as $record) {
            if (in_array($record["id"], $ids)) {
                $recordId[] = $record;
            }
        }
        sendJson($recordId);
    }
    // Hämta alla skivbolag
    sendJson($records);
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