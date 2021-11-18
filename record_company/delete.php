<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];

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

    // Går igenom idn och tar bort
    foreach($recordsDecode as $index => $user){
    if($user["id"] === $id) {
        //$found = true;
        array_splice($recordsDecode, $index, 1);
        break;
        }
    }

    // Uppdaterar filen
    $json = json_encode($index, JSON_PRETTY_PRINT);
    file_put_contents("../record_company.json", $json);
    sendJson(["id" => $id]);
};
/*$getRecord = file_get_contents("../record_company.json");
$recordsDecode = json_decode($getRecord, true);


$found = false;

if($requestMethod == "DELETE") {
    
    
    saveJson("../record_company.json", $recordsDecode);
    sendJson(["id" => $id]);
}*/
?>