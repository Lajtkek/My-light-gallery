<?php
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/logHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");

    $user = AuthHelper::getInstance()->auth(["admin"]);
 
    Database::getInstance()->beginTransaction();
    try{
        $codes = Database::getInstance()->assocQuery("SELECT idRegisterCode, code, idCreator, used FROM RegisterCodes");

        RequestHelper::getInstance()->resolve($codes);
        
    }catch(Exception $e){
        RequestHelper::getInstance()->reject($e);
        Database::getInstance()->rollbackTransaction();
    }
    
?>