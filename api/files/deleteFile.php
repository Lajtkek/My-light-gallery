<?php
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/fileHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");

    $idFile = RequestHelper::getInstance()->getParam("idFile", true);

	AuthHelper::getInstance()->auth(["admin"]);

    try {
        Database::getInstance()->beginTransaction();
        
        Database::getInstance()->normalQuery("DELETE FROM filetags WHERE idFile = {0}", [$idFile]);
        Database::getInstance()->normalQuery("DELETE FROM rating WHERE idFile = {0}", [$idFile]);
        Database::getInstance()->normalQuery("DELETE FROM Files WHERE idFile = {0}", [$idFile]);
        
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