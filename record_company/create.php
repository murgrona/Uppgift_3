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

if($requestMethod === "POST") {
    
    //kontrollerar om något av dessa inte finns med och isåfall skicka felmeddelande
    if(!isset($requestData["record_company"]) || !isset($requestData["country"]) || !isset($requestData["email"]) || !isset($requestData["year"])) {
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
    
    //annars hämta JSON och skapa en ny användare med de värden vi lägger in 
    $rapNames = loadJson("../record_company.json");
    //kolla ifall record_company innehåller "

    $newRecord = [
        "record_company" => $requestData["record_company"],
        "country" => $requestData["country"],
        "email" => $requestData["email"],
        "year" => $requestData["year"]
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

    $id = $newRapper["record_company"]; // är rätt
    $found = false;

    foreach($recordCompanies as $index => $recordCompany) { //kontrollera så att det finns ett id-skivbolag som rapparen kan tillhöra
        $recordIds = $recordCompany["id"];
        if($recordIds === $id) {
            $found = true;
            var_dump($found);
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

}

?>