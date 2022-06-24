<?php
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/fileHelper.php");
    require_once("../../php/logHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("PUT");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $file_id = RequestHelper::getInstance()->getParam("idFile", true);
    $filename = RequestHelper::getInstance()->getParam("filename", true);
    RequestHelper::getInstance()->validateParam($filename, "filename", [
        [
            "name" => "minLength",
            "value" => 3
        ]
    ]);
    $description = RequestHelper::getInstance()->getParam("description", true);
    $tags = RequestHelper::getInstance()->getParam("tags", true);
    
    LogHelper::getInstance()->log();

    try {
        Database::getInstance()->beginTransaction();

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