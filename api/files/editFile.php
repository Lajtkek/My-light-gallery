<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/fileHelper.php");

    RequestHelper::getInstance()->checkMethod("PUT");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $file_id = RequestHelper::getInstance()->getParam("idFile", true);
    $filename = RequestHelper::getInstance()->getParam("filename", true);
    $description = RequestHelper::getInstance()->getParam("description", true);
    $tags = RequestHelper::getInstance()->getParam("tags", true);

    try {
        Database::getInstance()->beginTransaction();

        //TODO dont update extension redundency
        Database::getInstance()->normalQuery("UPDATE Files SET filename = '{0}', description = '{1}' WHERE idFile = {2}", [$filename, $description, $file_id]);

        Database::getInstance()->normalQuery("DELETE FROM FileTags WHERE idFile = {0}", [$file_id]);

        foreach ($tags as &$tag) {
            Database::getInstance()->insertQuery("INSERT INTO FileTags (idFile, idTag) VALUES ({0}, {1})", [$file_id, $tag->idTag]);
        }

        Database::getInstance()->commitTransaction();

        RequestHelper::getInstance()->resolve([
            "result" => true
        ]);
    } catch (Exception $e) {
        Database::getInstance()->rollbackTransaction();
        RequestHelper::getInstance()->reject([
            "error" => $e->getMessage()
        ]);
    }
 ?>