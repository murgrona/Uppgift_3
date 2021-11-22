<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

$rappers = loadJson("../rap_name.json");

$id = $requestData["id"];

$found = false;
$foundRapper = null;


if($requestMethod == "PATCH") {
        foreach($rappers as $index => $rapper){
            if($rapper["id"] === $id){
                $found = true;

                if(isset($requestData["title"])) {
                    $rapper["title"] = $requestData["title"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add title"], 400);
                 }
                if(isset($requestData["rap_name"])) {
                    $rapper["rap_name"] = $requestData["rap_name"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add rap name"], 400);
                 }
                if(isset($requestData["spirit_animal"])) {
                    $rapper["spirit_animal"] = $requestData["spirit_animal"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add spirit animal"], 400);
                 }
                if(isset($requestData["gender"])) {
                    $rapper["gender"] = $requestData["gender"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add gender"], 400);
                 }
                
                if(isset($requestData["record_company"])) {
                   $rapper["record_company"] = $requestData["record_company"];
                } else {
                   sendJson([
                        "code" => 1,
                        "Message" => "All fields need to be complete, add record company ID"], 400);
                }

                $rappers[$index] = $rapper;
                $foundRapper = $rapper;
                break;
            }

        }
    
    if ($found == false){
        sendJson(["message" => "ID not found."], 404);
    }
    
    saveJson("../rap_name.json", $rappers);
    sendJson($foundRapper);
}

//Lägga till felmeddelanden
?>