<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if($requestMethod === "POST") {
    if(!isset($requestData["title"]) || !isset($requestData["rap_name"]) || !isset($requestData["spirit_animal"]) || !isset($requestData["gender"]) || !isset($requestData["record_id"])) {
        sendJson([
            "code" => 1,
            "Message" => "All fields need to be complete"]);
    }

    $rapNames = loadJson("../rap_name.json");

    $newRapper = [
        "title" => $requestData["title"],
        "rap_name" => $requestData["rap_name"],
        "spirit_animal" => $requestData["spirit_animal"],
        "gender" => $requestData["gender"],
        "record_id" => $requestData["record_id"]
    ];

    $highestId = 0; 
        
    foreach ($rapNames as $rapper) { 
        if ($rapper["id"] > $highestId) {
                $highestId = $rapper["id"];
        }
    }

    $newRapper["id"] = $highestId + 1;
   
    array_push($rapNames, $newRapper);

}    
    saveJson("../rap_name.json", $rapNames);
    sendJson($newRapper, 200);
?>