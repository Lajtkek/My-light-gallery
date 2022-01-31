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

    // //TODO check length and shit
    // $method = $_SERVER['REQUEST_METHOD'];
    // $idTag = null;
    // $action = "";
    // $name = "";
    // $code = "";
    // $color = "";
    // $is_public = 0;
    // switch ($method) {
    //     case "POST":
    //         $name = RequestHelper::getInstance()->getParam("name", true);
    //         $code = RequestHelper::getInstance()->getParam("code", true);
    //         $color = RequestHelper::getInstance()->getParam("color", true);
    //         $is_public = (int) RequestHelper::getInstance()->getParam("isPublic", true);
    //         $action = "CREATE";
    //         break;
    //     case "PATCH":
    //         RequestHelper::getInstance()->resolve();
    //         break;
    //     default:
    //         RequestHelper::getInstance()->reject("POST_OR_PATCH_REQUIRED");
    //         break;
    // }

    // $db_tags = Database::getInstance()->assocQuery("SELECT idTag FROM Tags WHERE code='{0}'", [$code]);
    // $color = str_replace("#", "", $color);

    // if($action === "CREATE"){
    //     if(count($db_tags) > 0){
    //         RequestHelper::getInstance()->reject("not_unique");
    //     }

    //     $tag_id = Database::getInstance()->insertQuery("INSERT INTO Tags (name, code, color, isPublic) VALUES ('{0}', '{1}', '{2}', {3})", [$name, $code, $color, $is_public]);
    //     if(is_numeric($tag_id))
    //         RequestHelper::getInstance()->resolve(["idTag" => $tag_id, "name" => $name, "code" => $code, "color" => "#".$color, "isPublic" => $is_public]);
    //     else
    //         RequestHelper::getInstance()->reject($tag_id);
    // }
?>