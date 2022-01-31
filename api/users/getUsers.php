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
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    $users = Database::getInstance()->assocQuery("SELECT idUser, username, email, isApproved, createdAt FROM Users ORDER BY idUser");
    $roles = Database::getInstance()->assocQuery("SELECT ur.idUser, r.name FROM UserRoles ur LEFT JOIN Roles r ON(r.idRole = ur.idRole) ORDER BY r.name asc");

    foreach ($users as &$user) {
        $user["roles"] = array_filter($roles, function ($role) use (&$user){
            return $role["idUser"] == $user["idUser"];
        });

        // $user["roles"] = array_map(function ($role){
        //     return $role["name"];
        // }, $user["roles"]);
    }

    RequestHelper::getInstance()->resolve($users);
?>