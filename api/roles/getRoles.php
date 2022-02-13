<?php
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $roles = Database::getInstance()->assocQuery("SELECT idRole, name FROM Roles");

    RequestHelper::getInstance()->resolve($roles);
?>