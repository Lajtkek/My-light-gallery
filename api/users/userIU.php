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

    AuthHelper::getInstance()->auth(["admin"]);
    //TODO check length and shit
    $method = $_SERVER['REQUEST_METHOD'];
    $idUser = null;
    $username = "";
    $password = "";
    $email = "";
    $is_approved = "";
    $roles = "";
    switch ($method) {
        case "POST":
            $username = RequestHelper::getInstance()->getParam("username", true);
            $password = password_hash(RequestHelper::getInstance()->getParam("password", true), PASSWORD_DEFAULT);
            $email = RequestHelper::getInstance()->getParam("email", true);
            $is_approved = (int) RequestHelper::getInstance()->getParam("isApproved", true);
            $roles = RequestHelper::getInstance()->getParam("roles", true);
            $action = "CREATE";
            break;
        case "PATCH":
            RequestHelper::getInstance()->reject("not_implemented");
            break;
        default:
            RequestHelper::getInstance()->reject("POST_OR_PATCH_REQUIRED");
            break;
    }


    if($action === "CREATE"){
        $db_users = Database::getInstance()->assocQuery("SELECT username, email FROM Users WHERE email = '{0}' OR username = '{1}'", [$email, $username]);

        if(count($db_users) > 0){
            if($db_users[0]["email"] == $email)
                RequestHelper::getInstance()->reject("EMAIL_NOT_UNIQUE");
            else
                RequestHelper::getInstance()->reject("USERNAME_NOT_UNIQUE");
        }

        Database::getInstance()->beginTransaction();
        try{
            $user_id = Database::getInstance()->insertQuery("INSERT INTO Users (username, password, email, isApproved) VALUES ('{0}', '{1}', '{2}', {3})", [$username, $password, $email, $is_approved]);
            
            if(count($roles) > 0){
                $roleInsert = "";
                foreach ($roles as &$role) {
                    $roleInsert .= "(".$user_id.",".$role."),";
                }
                $roleInsert = rtrim($roleInsert, ',');

                Database::getInstance()->insertQuery("INSERT INTO UserRoles (idUser, idRole) VALUES {0}", [$roleInsert]);
            }
            Database::getInstance()->commitTransaction();
        }catch(Exception $e){
            RequestHelper::getInstance()->reject($e);
            Database::getInstance()->rollbackTransaction();
        }
        
        $created_user = Database::getInstance()->getUsers([$user_id])[0];
        RequestHelper::getInstance()->resolve($created_user);
    }
?>