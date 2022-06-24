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

        
        $tags_uses = Database::getInstance()->assocQuery("SELECT (ft.idTag) as idTag, COUNT(ft.idFile) as fileCount FROM FileTags ft
		LEFT JOIN Tags t ON(ft.idTag = t.idTag)
		GROUP BY ft.idTag");

		$file_tags = Database::getInstance()->assocQuery("SELECT idTag FROM FileTags WHERE idFile = {0}", [$file_id]);

        Database::getInstance()->normalQuery("DELETE FROM FileTags WHERE idFile = {0}", [$file_id]);
		
		foreach ($file_tags as &$tag) {
			foreach ($tags_uses as &$useTag) {
				if($tag["idTag"] == $useTag["idTag"] && $useTag["fileCount"] == 1){
					Database::getInstance()->normalQuery("DELETE FROM Tags WHERE idTag = {0} AND isTemporary = 1", [$useTag["idTag"]]);
				}
			}
		}

        Database::getInstance()->normalQuery("DELETE FROM Files WHERE idFile = {0} AND isTemporary = 1", [$file_id]);

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