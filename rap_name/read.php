<?php
//ids
//titles
//includes
error_reporting(-1);

require_once "../functions.php";

// Hämta metoden
$requestMethod = $_SERVER["REQUEST_METHOD"];
$rappers = loadJson("../rap_name.json");

if ($requestMethod === "GET") {
    //Hämta rappare med egen nyckel (titles)
    if (isset($_GET["titles"])) {
        $rapTitle = explode(",",$_GET["titles"]);
        $titleArray = [];
        $found = true;
        foreach ($rappers as $rapT) {
            if (in_array($rapT["title"], $rapTitle)) {
                $titleArray[] = $rapT;
            }
        }//ifall GET URL innehåller både limit och titles
        if(isset($_GET["limit"]) && isset($_GET["titles"])) {
            $limit = $_GET["limit"];
            $slicedRapperTitle = array_slice($titleArray, 0, $limit);
            sendJson($slicedRapperTitle);
        }
        sendJson($titleArray);
    }
    
    

    $found = false;
    //Hämta begränsat antal rappare
    if (isset($_GET["limit"])) {
        $found = true;
        $limit = $_GET["limit"];
        $slicedRapper = array_slice($rappers, 0, $limit);
        sendJson($slicedRapper);
    }
    //Får fram rapparens företag beroende på vilket id som anges i includes URL
    if (isset($_GET["includes"])) {
        $includes = $_GET["includes"];
        foreach ($rappers as $rapper) {
            if($includes == $rapper["record_company"]){
                $rapperRecordID = $rapper["record_company"];
                $recordCompanies = loadJson("../record_company.json");
                $found = false;
                //loopar igenom skivbolag och jämför angett id för att få fram rätt skivbolag
                foreach($recordCompanies as $recordCompany) {
                    if($recordCompany["id"] === $rapperRecordID) {  
                        $found = true;
                        $rapperWithRecordCompany = str_replace($rapperRecordID, $recordCompany["record_company"], $rapper);
                        
                        sendJson([
                            $rapperWithRecordCompany
                        ],200);
                    }
                 
                }
            

            }
            //om användaren anger ett ID som inte finns så får de upp följande felmeddelande
        }if($includes !== $rapper["record_company"]) {
            sendJson([
                "code" => 4,
                "Message" => "ID does not exist"], 400);
            exit();
        }
    }
 
    // Hämta rappare beroende på id
    if (isset($_GET["ids"])) {
        $ids = explode(",",$_GET["ids"]);
        $rappersId = [];
        foreach ($rappers as $rapper) {
            if (in_array($rapper["id"], $ids)) {
                $found = true;
                $rappersId[] = $rapper;
            }
        }
        if ($found === false) {
            sendJson(
                [
                    "code" => 4,
                    "message" => "Does not exist"
                ],
                404
            );
        }
        sendJson($rappersId);
    }
   
    sendJson($rappers);
}


?>