<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

$records = loadJson("../record_company.json");

$id = $requestData["id"];

$found = false;
$foundRecord = null;

$contentType = $_SERVER["CONTENT_TYPE"];

// Checka contentType
if ($contentType !== "application/json") {
    sendJson(
        ["message" => "The API only accepts JSON"],
        400
    );
}


if($requestMethod == "PATCH") {
        foreach($records as $index => $record){
            if($record["id"] === $id){
                $found = true;

                if(isset($requestData["record_company"])) {
                    $record["record_company"] = $requestData["record_company"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add record company"], 400);
                 }
                if(isset($requestData["country"])) {
                    $record["country"] = $requestData["country"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add country"], 400);
                 }
                if(isset($requestData["email"])) {
                    $record["email"] = $requestData["email"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add email"], 400);
                 }
                if(isset($requestData["year"])) {
                    $record["year"] = $requestData["year"];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add year"], 400);
                 }
    
                

                $records[$index] = $record;
                $foundRecord = $record;
                break;
            }

        }
    if ($found == false){
        sendJson(["message" => "ID not found."], 404);
    }

    $email = $requestData["email"];
    $findChar = strpos($email, '@');
   
    if($findChar == false) {
        sendJson([
            "code" => 2,
            "Message" => "Email needs to contain @"
        ], 400);
        exit();
    }
    
    //kontrollera så att år är siffror
    $year = $requestData["year"];
    if(!is_numeric($year)){
        sendJson([
            "code" => 2,
            "Message" => "Year needs to be in numbers"
        ], 400);
        exit();
    }
    
    saveJson("../record_company.json", $records);
    sendJson($foundRecord);
}
//Lägga till felmeddelanden

?>