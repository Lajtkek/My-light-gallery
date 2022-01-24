<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================

    require("../php/database.php");
    require("../php/authHelper.php");

    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body);

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        die("POST_REQUEST_REQUIRED");

    if(empty($request_data->username)){
        die("USERNAME_PARAM_REQUIRED");
    }

    if(empty($request_data->password)){
        die("PASSWORD_PARAM_REQUIRED");
    }

    $username = $request_data->username;
    $password = $request_data->password;

    $database = new Database;

    $user = $database->assocQuery("SELECT idUser, username, password FROM Users WHERE Username = '{0}'", [$username]);

    if(count($user) == 0)
        die("USER_NOT_FOUND");

    //Bude vždy jediný, protože Username je unique
    $user = $user[0];

    if(!password_verify($password, $user["password"])){
        die("IVALID_PASSWORD");
    }

    $authHelper = new AuthHelper;

    $exp = new DateTime();
    $exp->modify('+30 minutes');
    $exp = $exp->getTimestamp();

    $token = $authHelper->generateToken([
        'idUser' => $user["idUser"],
        'username' => $user["username"],
        'roles' => 'tbd',
        'exp' => $exp
    ]);

    echo json_encode([
        'token' => $token
    ]);
?>