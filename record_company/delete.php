<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$records = loadJson("../record_company.json");

// Våran Json data från vilken fil
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
    $rapNames = loadJson("../rap_name.json");
    // Går igenom record idn och tar bort
    foreach($records as $index => $record){
    if($record["id"] === $id) {
        $found = true;
        array_splice($records, $index, 1);
        break;
        }
    }

    $length = count($rapNames);

    for ($x = 0; $x <= $length; $x++) {
        foreach ($rapNames as $index => $rapName) {
            $rapperID = $rapName["record_company"];
           if($rapperID == $id) {
               //Raderar bort dem
                array_splice($rapNames, $index, 1);
                break;
            } 
        }
    }
   
    if ($found === false) {
        sendJson([
            "code" => 4,
            "Message" => "The record company id does not exist"],
            404 
        );
        exit();
    }

    // Uppdaterar filen
    saveJson("../record_company.json", $records);
    saveJson("../rap_name.json", $rapNames);
    sendJson([
        "Message" => "Sucessfully deleted both record company with id = {$id} and rappers with same id" ], 200);
};
?>