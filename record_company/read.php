<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$records = loadJson("../record_company.json");


if ($requestMethod === "GET") {
    if (isset($_GET["country"])) {
        $countryRec = explode(",",$_GET["country"]);
        $CountryArray = [];
        
        foreach ($records as $recC) {
            if (in_array($recC["country"], $countryRec)) {
                $CountryArray[] = $recC;
            }
        }
        sendJson($CountryArray);
    }
    //Hämta begränsat antal skivbolag
    if (isset($_GET["limit"])) {
        $limit = $_GET["limit"];
        $slicedRecord = array_slice($records, 0, $limit);
        sendJson($slicedRecord);
    }
    $found = false;
    // Hämta skivbolag beroende på id
    if (isset($_GET["ids"])) {
        $ids = explode(",",$_GET["ids"]);
        $recordId = [];

        foreach ($records as $record) {
            if (in_array($record["id"], $ids)) {
                $found = true;
                $recordId[] = $record; 
            }
        }
        if($found === false) {
            sendJson([
                "code" => 4,
                "message" => "Company does not exist"],
            404
            );
            exit();
        }
        sendJson($recordId);
        
    }
    // Hämta alla skivbolag
    sendJson($records);
}
?>