<?php
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$rappers = loadJson("../rap_name.json");

// Hämta Json data
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

//Radera baserat på id
if ($requestMethod === "DELETE") {
    if (!isset($requestData["id"])) {
        sendJson(
            [
            "code" => 1,
            "message" => "missing id"
            ],
            400
        );
    }
    $id = $requestData["id"];
    $recordCompanies = loadJson("../record_company.json");//hämta record_company JSON
    $found = false;
    // Går igenom idn och tar bort
    foreach($rappers as $index => $rapper){
    if($rapper["id"] === $id) {
        $found = true;
        array_splice($rappers, $index, 1);
        break;
        }
    }
    foreach($recordCompanies as $index => $recordCompany) { //kontrollera så att det finns ett id-skivbolag som rapparen kan tillhöra
        $recordIds = $recordCompany["id"];
        if($recordIds === $id) {
            $found = true;
            var_dump($found);
            array_splice($recordIds, $index, 1);
        }
    }if($found == false) {
        sendJson([
            "code" => 4,
            "Message" => "This company ID does not exist, please try again"], 400);
            exit();
    }
    if ($found === false) {
        sendJson(
            [
                "code" => 2,
                "message" => "The rapper by `id` does not exist"
            ],
            404
        );
    }
    // Uppdaterar filen
    saveJson("../record_company.json", $recordIds);
    saveJson("../rap_name.json", $rappers);
    sendJson(["id" => $id]);
};
?>