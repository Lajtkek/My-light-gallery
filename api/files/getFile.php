<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/database.php");
    require("../../php/requestHelper.php");
    //require("../../php/ftpHelper.php");

    require("../../php/authHelper.php");

    RequestHelper::getInstance()->checkMethod("GET");
    $idFile = RequestHelper::getInstance()->getParam("idFile", true);

    $file = Database::getInstance()->assocQuery("SELECT idFile, filename, concat(permalink,'.', extension) as permalink, mimetype, extension FROM Files WHERE idFile = '{0}'", [$idFile]);
    $tags = Database::getInstance()->assocQuery("SELECT t.idTag, t.name, t.code, t.color, t.isPublic FROM Tags t 
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