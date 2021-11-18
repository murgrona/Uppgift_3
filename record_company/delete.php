<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$records = loadJson("../record_company.json");

// Hämta Json data
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

//Radera baserat på id
if ($requestMethod === "DELETE") {
    if (!isset($requestData["id"])) {
        sendJson(
            [
            "code" => 1,
            "message" => "missing id"
            ],
            400
        );
    }
    $id = $requestData["id"];
    $found = false;
    // Går igenom idn och tar bort
    foreach($records as $index => $record){
    if($record["id"] === $id) {
        $found = true;
        array_splice($records, $index, 1);
        break;
        }
    }
    if ($found === false) {
        sendJson(
            [
                "code" => 2,
                "message" => "The recor company by `id` does not exist"
            ],
            404
        );
    }
    // Uppdaterar filen
    saveJson("../record_company.json", $records);
    sendJson(["id" => $id]);
};
?>