<?php
error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if($requestMethod === "POST") {
    if(!isset($requestData["title"]) || !isset($requestData["rap_name"]) || !isset($requestData["spirit_animal"]) || !isset($requestData["gender"]) || !isset($requestData["city"])) {
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

    $newRapper = [
        "title" => $requestData["title"],
        "rap_name" => $requestData["rap_name"],
        "spirit_animal" => $requestData["spirit_animal"],
        "gender" => $requestData["gender"],
        "city" => $requestData["city"]
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