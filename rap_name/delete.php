<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$rappers = loadJson("../rap_name.json");

// Hämta Json data
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);


$contentType = $_SERVER["CONTENT_TYPE"];

// Checka contentType
if ($contentType !== "application/json") {
    sendJson([ 
        "code" => 2,
        "Message" => "The API only accepts JSON"],
        400
    );
    exit();
}

//Radera baserat på id
if ($requestMethod === "DELETE") {
    if (!isset($requestData["id"])) {
        sendJson([
            "code" => 3,
            "Message" => "missing id"],
            404
        );
        exit();
    }
    
    $id = $requestData["id"];
    
    $found = false;
    // Går igenom idn och tar bort
    foreach($rappers as $index => $rapper){
    if($rapper["id"] === $id) {
        $found = true;
        array_splice($rappers, $index, 1);
        break;
        }
    }
    if ($found === false) {
        sendJson(
            [
                "code" => 4,
                "Message" => "The rapper by this id = {$id}, does not exist"
            ],
            404
        );
        exit();
    }
    // Uppdaterar filen
    saveJson("../rap_name.json", $rappers);
    sendJson([
        "Message" => "sucessfully deleted rapper with id = {$id}"], 200);
};
?>