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
    if(!isset($requestData["title"]) || !isset($requestData["rap_name"]) || !isset($requestData["spirit_animal"]) || !isset($requestData["gender"]) || !isset($requestData["record_company"])) {
        sendJson([
            "code" => 1,
            "Message" => "All fields need to be complete"], 400);
            exit();
    }
    if (strlen($requestData["title"]) < 2 || strlen($requestData["rap_name"]) < 2|| strlen($requestData["spirit_animal"]) < 2 || strlen($requestData["gender"]) < 2) {
        sendJson([
            "code" => 2,
            "Message" => "Field needs to contain at least 2 characters",
        ], 400);
        exit();
    }
    
    //annars hämta JSON och skapa en ny användare med de värden vi lägger in 
    $rapNames = loadJson("../rap_name.json");
    //kolla ifall record_company innehåller "

    $newRapper = [
        "title" => $requestData["title"],
        "rap_name" => $requestData["rap_name"],
        "spirit_animal" => $requestData["spirit_animal"],
        "gender" => $requestData["gender"],
        "record_company" => $requestData["record_company"]
    ];
    //få ut nytt ID
    $highestId = 0; 
        
    foreach ($rapNames as $rapper) {
        if ($rapper["id"] > $highestId) {
                $highestId = $rapper["id"];
        }
    }

    $newRapper["id"] = $highestId + 1;

    $recordCompanies = loadJson("../record_company.json");//hämta record_company JSON

    $id = $newRapper["record_company"]; 
    $found = false;

    foreach($recordCompanies as $index => $recordCompany) { //kontrollera så att det finns ett id-skivbolag som rapparen kan tillhöra
        $recordIds = $recordCompany["id"];

        if($recordIds === $id) {
            $found = true;
            array_push($rapNames, $newRapper);
        }
        }if($found == false) {
            sendJson([
                "code" => 4,
                "Message" => "This company ID does not exist, please try again"], 400);
                exit(); 
        }
    saveJson("../rap_name.json", $rapNames);
    sendJson($newRapper, 200);  

   $contentType = $_SERVER["CONTENT_TYPE"];

    // Checka contentType
    if ($contentType !== "application/json") {
    sendJson(
        ["message" => "The API only accepts JSON"],
        400
    );
    }
}
?>