<?php

error_reporting(-1);
require_once "../functions.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

//Om metoden är PUT, följande felmeddelande
if($requestMethod === "PUT") {
    sendJson([
        "code" => 4,
        "Message" => "Method not allowed"], 405);
}

$contentType = $_SERVER["CONTENT_TYPE"];

// Checka contentType
if ($contentType !== "application/json") {
    sendJson(
        ["message" => "The API only accepts JSON"],
        400
    );
}

if($requestMethod === "POST" && isset($_POST)) {
 
    //kontrollerar om något av dessa inte finns med och isåfall skicka felmeddelande
    if(!isset($requestData["record_company"]) || !isset($requestData["country"]) || !isset($requestData["email"]) || !isset($requestData["year"])) {
        var_dump(isset($requestData["record_company"]));
        sendJson([
            "code" => 1,
            "Message" => "All fields need to be complete"], 400);
            exit();
    }
    if (strlen($requestData["record_company"]) < 2 || strlen($requestData["country"]) < 2|| strlen($requestData["email"]) < 2 || strlen($requestData["year"]) < 2) {
        sendJson([
            "code" => 2,
            "Message" => "Field needs to contain at least 2 characters",
        ], 400);
        exit();
    }
 
    //hämta JSON och skapa en nya skivbolag med de värden vi lägger in 
    $recordCompanies = loadJson("../record_company.json");

    $newRecord = [
        "record_company" => $requestData["record_company"],
        "country" => $requestData["country"],
        "email" => $requestData["email"],
        "year" => $requestData["year"]
    ];
    //få ut nytt ID
    $highestId = 0; 
        
    foreach ($recordCompanies as $recordCompany) {
        if ($recordCompany["id"] > $highestId) {
                $highestId = $recordCompany["id"];
        }
    }

    //kontrollera så att email har @ 
    $email = $newRecord["email"];
    $find = strpos($email, '@');
   
    if($find == false) {
        sendJson([
            "code" => 2,
            "Message" => "Email needs to contain @"
        ], 400);
        exit();
    }
    
    //kontrollera så att år är siffror
    $year = $newRecord["year"];
    if(!is_numeric($year)){
        sendJson([
            "code" => 2,
            "Message" => "Year needs to be in numbers"
        ], 400);
        exit();
    }

    $newRecord["id"] = $highestId + 1;
    array_push($recordCompanies, $newRecord);

    saveJson("../record_company.json", $recordCompanies);
    sendJson($newRecord, 200);  

}

?>