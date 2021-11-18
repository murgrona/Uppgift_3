<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if($requestMethod === "POST") {
    if(!isset($requestData["record_company"]) || !isset($requestData["country"]) || !isset($requestData["email"]) || !isset($requestData["year"])) {
        header("Content-Type: application/json");
        http_response_code(400);

        $json = json_encode([
            "code" => 1,
            "Message" => "All fields need to be complete"]);
        echo $json;
        exit();
    }

    //$getUsers = file_get_contents("../rap_name.json");
    $rapNames = loadJson("../rap_name.json");
    $

    $newRapper = [
        "record_company" => $requestData["record_company"],
        "country" => $requestData["country"],
        "email" => $requestData["email"],
        "year" => $requestData["year"],
        "rapper_id" => $requestData["rapper_id"]
    ];

    $highestId = 0; 
        
    foreach ($rapNames as $rapper) { 
        if ($rapper["id"] > $highestId) {
                $highestId = $rapper["id"];
        }
    }

    $newRapper["id"] = $highestId + 1;
   
    array_push($rapNames, $newRapper);
   

    file_put_contents(
    "../rap_name.json",
    json_encode($rapNames, JSON_PRETTY_PRINT)
    );
   
    header("Content-Type: application/json");
    http_response_code(201);
    $json = json_encode($newRapper);
    echo $json;
    exit();
}

?>