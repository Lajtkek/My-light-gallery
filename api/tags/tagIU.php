<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");

    //TODO check length and shit
    $method = $_SERVER['REQUEST_METHOD'];
    $idTag = RequestHelper::getInstance()->getParam("idTag", $method == "PUT");
    $tags = RequestHelper::getInstance()->getParam("tags", true);
    $name = RequestHelper::getInstance()->getParam("name", true);
    $code = RequestHelper::getInstance()->getParam("code", true);
    $color = RequestHelper::getInstance()->getParam("color", true);
    $is_public = (int) RequestHelper::getInstance()->getParam("isPublic", true);

    switch ($method) {
        case "POST":
            $action = "CREATE";
            break;
        case "PUT":
            $action = "UPDATE";
            break;
        default:
            RequestHelper::getInstance()->reject("POST_OR_PUT_REQUIRED");
            break;
    }

    $color = str_replace("#", "", $color);

    if($action === "CREATE"){
        $db_tags = Database::getInstance()->assocQuery("SELECT idTag FROM Tags WHERE code='{0}'", [$code]);
        if(count($db_tags) > 0){
            RequestHelper::getInstance()->reject("not_unique");
        }

        $tag_id = Database::getInstance()->insertQuery("INSERT INTO Tags (name, code, color, isPublic) VALUES ('{0}', '{1}', '{2}', {3})", [$name, $code, $color, $is_public]);
        if(is_numeric($tag_id))
            RequestHelper::getInstance()->resolve(["idTag" => $tag_id, "name" => $name, "code" => $code, "color" => "#".$color, "isPublic" => $is_public, "tags" => $tags]);
        else
            RequestHelper::getInstance()->reject($tag_id);
    }

    if($action == "UPDATE"){
        try{
            Database::getInstance()->beginTransaction();
            $db_tags = Database::getInstance()->assocQuery("SELECT idTag FROM Tags WHERE idTag = {0}", [$idTag]);
            if(count($db_tags) == 0){
                RequestHelper::getInstance()->reject("tag_doesnt_exist");
            }

            Database::getInstance()->normalQuery("UPDATE Tags SET name = '{0}', code = '{1}', color = '{2}', isPublic = '{3}' WHERE idTag = {4}", [$name, $code, $color, $is_public, $idTag]);
            Database::getInstance()->normalQuery("DELETE FROM TagTags WHERE idTag = {0}", [$idTag]);

            foreach ($tags as &$tag_to_add) {
                Database::getInstance()->insertQuery("INSERT INTO TagTags (idTag, idChildTag) VALUES ({0}, {1})", [$idTag, $tag_to_add]);
            }

            
            Database::getInstance()->commitTransaction();
            RequestHelper::getInstance()->resolve(["idTag" => $idTag, "name" => $name, "code" => $code, "color" => "#".$color, "isPublic" => $is_public, "tags" => $tags]);
        } catch (Exception $e) {
            Database::getInstance()->rollbackTransaction();
            RequestHelper::getInstance()->reject([
                "error" => $e->getMessage()
            ]);
        }
    }
?>