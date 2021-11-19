<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if($requestMethod === "POST") {
    if(!isset($requestData["title"]) || !isset($requestData["rap_name"]) || !isset($requestData["spirit_animal"]) || !isset($requestData["gender"]) || !isset($requestData["record_id"])) {
        header("Content-Type: application/json");
        http_response_code(400);

        $json = json_encode([
            "code" => 1,
            "Message" => "All fields need to be complete"]);
        echo $json;
        exit();
    }

    $rapNames = loadJson("../rap_name.json");

    $newRapper = [
        "title" => $requestData["title"],
        "rap_name" => $requestData["rap_name"],
        "spirit_animal" => $requestData["spirit_animal"],
        "gender" => $requestData["gender"],
        "record_id" => [$requestData["record_id"]]
    ];

    $highestId = 0; 
        
    foreach ($rapNames as $rapper) { 
        if ($rapper["id"] > $highestId) {
                $highestId = $rapper["id"];
        }
    }

    $newRapper["id"] = $highestId + 1;
   
    array_push($rapNames, $newRapper);


    $rapperRecordID = $requestData["record_id"];

    $recordJson = loadJson("../record_company.json");

    $foundUser = null;
    $ownerOfRapper = null;

    foreach($recordJson as $record) {
        $ownerOfRapper = $record["owner_of_rapper"];
            if($rapperRecordID == $record["id"]) { 
                $foundUser = $newRapper["id"];
            }
            //array_push($ownerOfRapper, $foundUser); 
            //var_dump($ownerOfRapper); 
        }
       
    }
   
   
   

    saveJson("../record_company.json", $foundUser);

        
    saveJson("../rap_name.json", $rapNames);
    sendJson($newRapper, 200);

    //sendJSON här istället


?>