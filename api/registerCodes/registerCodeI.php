<?php
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/logHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");

    $code = RequestHelper::getInstance()->getParam("code", true);
    $user = AuthHelper::getInstance()->auth(["admin"]);
 
    Database::getInstance()->beginTransaction();
    try{
        $code_id = Database::getInstance()->insertQuery("INSERT INTO RegisterCodes (code, idCreator) VALUES ('{0}', '{1}')", [$code, $user->idUser]);

        Database::getInstance()->commitTransaction();

        RequestHelper::getInstance()->resolve($code_id);

    }catch(Exception $e){
        RequestHelper::getInstance()->reject($e);
        Database::getInstance()->rollbackTransaction();
    }
    
?>