<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/database.php");
    require("../../php/requestHelper.php");
    require("../../php/authHelper.php");
    require("../../php/logHelper.php");
    
    RequestHelper::getInstance()->checkMethod("GET");
    $idFile = RequestHelper::getInstance()->getParam("idFile", true);

    //TODO: 
    $userData = AuthHelper::getInstance()->auth();
    $identifier = RequestHelper::getInstance()->getIP();
    $ratingQuery = "";

    LogHelper::getInstance()->log();

    if(!is_null($userData) && !is_string($userData)){
        //pokud má alespoň jednu roli (těď je jenom admin ale bude víc roli)
        $private_enabled = (int) (count(array_intersect($userData->roles, ["admin"])) > 0);
        $identifier = $userData->idUser;
        $ratingQuery = "LEFT JOIN Rating r ON (r.idFile = f.idFile AND r.idUser = {1})";
    }else{
        if(is_null($identifier))
            $ratingQuery = "LEFT JOIN Rating r ON (0=1)";
        else
            $ratingQuery = "LEFT JOIN Rating r ON (r.idFile = f.idFile AND r.ipAddress = '{1}')";
    }

    $file = Database::getInstance()->assocQuery("SELECT f.idFile, f.filename, concat(f.permalink,'.', f.extension) as permalink, f.mimeType, f.extension, f.description, IFNULL(r.rating, 0) as rating, f.rating as globalRating 
                                                    FROM Files f
                                                    ".$ratingQuery."
                                                    WHERE f.idFile = '{0}'", [$idFile, $identifier]);
    $tags = Database::getInstance()->assocQuery("SELECT t.idTag, t.name, t.code, concat('#',t.color) as color, t.isPublic FROM Tags t 
                                                    LEFT JOIN FileTags ft ON(ft.idTag = t.idTag)
                                                    WHERE ft.idFile = '{0}'", [$idFile]);

    foreach ($tags as &$tag) {
        if($tag["isPublic"] == 0){
            AuthHelper::getInstance()->auth(["admin"]);
        }
    }

    $file = $file[0];
    $file["tags"] = $tags;
    RequestHelper::getInstance()->resolve($file);

?>