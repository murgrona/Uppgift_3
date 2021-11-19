<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

$rappers = loadJson("../record_company.json");

$id = $requestData["id"];

$found = false;
$foundRecord = null;


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
                if(isset($requestData["owner_of_rapper"])) {
                   $record["owner_of_rapper"] = [$requestData["owner_of_rapper"]];
                }else {
                    sendJson([
                         "code" => 1,
                         "Message" => "All fields need to be complete, add owner of rapper"], 400);
                 }
                

                $records[$index] = $record;
                $foundRecord = $record;
                break;
            }

        }
    
    saveJson("../record_company.json", $records);
    sendJson($foundRecord);
}
//Lägga till felmeddelanden

?>