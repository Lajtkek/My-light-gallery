<?php
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/logHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $users = Database::getInstance()->getUsers();

    LogHelper::getInstance()->log();
    RequestHelper::getInstance()->resolve($users);
?>