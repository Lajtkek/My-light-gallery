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

    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth();

    //TODO: check role :RtzW:
    //$tags = Database::getInstance()->assocQuery("SELECT idTag, name, code, concat('#',color) as color FROM Tags");

    //TODO: upload
    //echo json_encode($tags);
 ?>