<?php
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/fileHelper.php");
    require_once("../../php/logHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $file_id = RequestHelper::getInstance()->getParam("idFile", true);
    
    LogHelper::getInstance()->log();

    try {
        Database::getInstance()->beginTransaction();
		
		$file_tags = Database::getInstance()->assocQuery("SELECT idTag FROM FileTags WHERE idFile = {0}", [$file_id]);
		foreach ($file_tags as &$tag) {
			Database::getInstance()->normalQuery("UPDATE Tags SET isTemporary = 0 WHERE idTag = {0}", [$tag["idTag"]]);
			
			
		}

        Database::getInstance()->normalQuery("UPDATE Files SET isTemporary = 0 WHERE idFile = {0}", [$file_id]);

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