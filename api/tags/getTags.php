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

    RequestHelper::getInstance()->checkMethod("GET");
    $detail = RequestHelper::getInstance()->getParam("detail") ?? 0;
    $userData = AuthHelper::getInstance()->auth();

    if($detail == 0){
        $tags = Database::getInstance()->assocQuery("SELECT idTag, name, code, concat('#',color) as color, isPublic FROM Tags");
        RequestHelper::getInstance()->resolve($tags);
    }

    $tags = Database::getInstance()->assocQuery("SELECT 
                                                    t.idTag as idTag,
                                                    t.name as name, 
                                                    t.code as code, 
                                                    concat('#',t.color) as color,
                                                    t.isPublic as isPublic,
                                                    GROUP_CONCAT(tt.idChildTag) as tags
                                                FROM Tags t
                                                LEFT JOIN TagTags tt ON (tt.idTag = t.idTag)
                                                GROUP BY t.idTag");

    foreach ($tags as &$tag) {
        if($tag["tags"] == null)
            $tag["tags"] = [];
        else
            $tag["tags"] = explode(",",$tag["tags"]);
    }

    RequestHelper::getInstance()->resolve($tags);
?>