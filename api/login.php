<?php
    require("../php/database.php");
    require("../php/authHelper.php");

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        die("Post request required for login");

    if(empty($_POST['username'])){
        die("Username parameter required");
    }

    if(empty($_POST['password'])){
        die("Password parameter required");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $database = new Database;

    $user = $database->assocQuery("SELECT idUser, username, password FROM Users WHERE Username = '{0}'", [$username]);

    if(count($user) == 0)
        die("User not found");

    //Bude vždy jediný, protože Username je unique
    $user = $user[0];

    if(!password_verify($password, $user["password"])){
        die("Invalid password");
    }

    $authHelper = new AuthHelper;

    $exp = new DateTime();
    $exp->modify('+30 minutes');
    $exp = $exp->getTimestamp();

    $token = $authHelper->generateToken([
        'idUser' => $user["idUser"],
        'roles' => 'tbd',
        'exp' => $exp
    ]);

    echo $token;
?>