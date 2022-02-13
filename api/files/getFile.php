<?php
    require_once("../../php/database.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/logHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");

    $idFile = RequestHelper::getInstance()->getParam("idFile", true);

    $user_data = AuthHelper::getInstance()->auth();
    $identifier = RequestHelper::getInstance()->getIP();
    $ratingQuery = "";

    LogHelper::getInstance()->log();

    if(!is_null($user_data) && !is_string($user_data)){
        //pokud má alespoň jednu roli (těď je jenom admin ale bude víc roli)
        $private_enabled = (int) (count(array_intersect($user_data->roles, ["admin"])) > 0);
        $identifier = $user_data->idUser;
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