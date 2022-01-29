<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require_once("../../php/phpHelper.php");

    RequestHelper::getInstance()->checkMethod("GET");
    $userData = AuthHelper::getInstance()->auth();

    $private_enabled = 0;
    // if authToken is valid
    if(!is_null($userData)){
        //pokud má alespoň jednu roli (těď je jenom admin ale bude víc roli)
        $private_enabled = (int) (count(array_intersect($userData->roles, ["admin"])) > 0);
    }

    $files = Database::getInstance()->assocQuery("SELECT f.idFile as idFile, f.filename as filename, f.permalink as permalink, f.mimetype as mimeType, f.extension as extension 
                                                    FROM Files f
                                                    LEFT JOIN FileTags ft ON(ft.idFile = f.idFile)
                                                    LEFT JOIN Tags t ON(t.idTag = ft.idTag)
                                                    WHERE 
                                                        1={0}
                                                        OR
                                                        t.isPublic IS NULL 
                                                        OR
                                                        t.isPublic = 1
                                                    GROUP BY f.idFile", [$private_enabled]);

    RequestHelper::getInstance()->resolve($files);
?>