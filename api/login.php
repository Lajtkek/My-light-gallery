<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../php/requestHelper.php");
    require("../php/database.php");
    require("../php/authHelper.php");
    require_once("../php/phpHelper.php");

    RequestHelper::getInstance()->checkMethod("POST");

    $username = RequestHelper::getInstance()->getParam("username", true);
    $password = RequestHelper::getInstance()->getParam("password", true);

    $user = Database::getInstance()->assocQuery("SELECT idUser, username, password FROM Users WHERE Username = '{0}'", [$username]);

    if(count($user) == 0)
        die("USER_NOT_FOUND");

    //Bude vždy jediný, protože Username je unique
    $user = $user[0];
    $roles = Database::getInstance()->assocQuery("SELECT r.name FROM userroles ur LEFT JOIN roles r ON(r.idRole = ur.idRole) WHERE idUser = '{0}'", [$user["idUser"]]);

    if(!password_verify($password, $user["password"])){
        die("IVALID_PASSWORD");
    }

    $exp = new DateTime();
    $exp->modify('+9999 minutes');
    $exp = $exp->getTimestamp();

    $token = AuthHelper::getInstance()->generateToken([
        'idUser' => $user["idUser"],
        'username' => $user["username"],
        'roles' => assocArrayToArray($roles, "name"),
        'exp' => $exp
    ]);

    echo json_encode([
        'token' => $token
    ]);
?>