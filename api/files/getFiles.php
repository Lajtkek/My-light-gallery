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
    //if change change vue config
    $limit = 15;
    $offset = RequestHelper::getInstance()->getParam("offset") ?? 0;
    $tags = RequestHelper::getInstance()->getParam("tags") ?? "";
    $file_name = RequestHelper::getInstance()->getParam("filename") ?? "";
    $tags = $tags == "" ? [] : explode(",", $tags);
    
    $tag_array = count($tags) > 0 ? implode(",",$tags) : -1;

    // if authToken is valid (not null or not string)
    if(!is_null($userData) && !is_string($userData)){
        //pokud má alespoň jednu roli (těď je jenom admin ale bude víc roli)
        $private_enabled = (int) (count(array_intersect($userData->roles, ["admin"])) > 0);
        // to je asi buřt
        //$limit = 20;
    }

    $files = Database::getInstance()->assocQuery("SELECT f.idFile as idFile, f.filename as filename, concat(f.permalink,'.', f.extension) as permalink, f.mimetype as mimeType, f.extension as extension 
                                                    FROM Files f
                                                    LEFT JOIN FileTags ft ON(ft.idFile = f.idFile)
                                                    LEFT JOIN Tags t ON(t.idTag = ft.idTag AND (t.isPublic = 0))
                                                    WHERE 
                                                        f.filename LIKE '%{0}%'
                                                        AND 
                                                        (SELECT COUNT(rt.idTag) FROM FileTags rt WHERE rt.idFile = f.idFile AND rt.idTag IN ({1})) = {2}
                                                    GROUP BY f.idFile 
                                                    HAVING 
                                                        COUNT(t.idTag) = 0 
                                                        OR
                                                        1 = {3}    
                                                    LIMIT {4} OFFSET {5}", [ $file_name, $tag_array, count($tags), $private_enabled, $limit, $offset]);

    RequestHelper::getInstance()->resolve($files);
?>