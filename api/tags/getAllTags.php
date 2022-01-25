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

    RequestHelper::getInstance()->checkMethod("GET");
    $userData = AuthHelper::getInstance()->auth();

    $tags = Database::getInstance()->assocQuery("SELECT idTag, name, code, concat('#',color) as color FROM Tags");

    echo json_encode($tags);
?>