<?php
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/logHelper.php");
    
    RequestHelper::getInstance()->setHeader();

    AuthHelper::getInstance()->auth(["admin"]);
    //TODO check length and stuff
    $method = $_SERVER['REQUEST_METHOD'];
    $idUser = null;
    $user_id = null;
    $username = "";
    $password = "";
    $email = "";
    $is_approved = "";
    $roles = null;
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
            $user_id = RequestHelper::getInstance()->getParam("idUser", true);
            $username = RequestHelper::getInstance()->getParam("username");
            $email = RequestHelper::getInstance()->getParam("email");
            $password = RequestHelper::getInstance()->getParam("password") ?? null;
            $password = $password != null ? password_hash($password, PASSWORD_DEFAULT) : null;
            $is_approved = (int) RequestHelper::getInstance()->getParam("isApproved");
            $roles = RequestHelper::getInstance()->getParam("roles");
            $action = "PATCH";
            break;
        default:
            RequestHelper::getInstance()->reject("POST_OR_PATCH_REQUIRED");
            break;
    }

    LogHelper::getInstance()->log();

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
    }else{
        Database::getInstance()->beginTransaction();

        try{
            $db_users = Database::getInstance()->assocQuery("SELECT username, email FROM Users WHERE (email = '{0}' OR username = '{1}') AND idUser != {2}", [$email, $username, $user_id]);

            if(count($db_users) > 0){
                if($db_users[0]["email"] == $email)
                    RequestHelper::getInstance()->reject("EMAIL_NOT_UNIQUE");
                else
                    RequestHelper::getInstance()->reject("USERNAME_NOT_UNIQUE");
            }

            Database::getInstance()->normalQuery("UPDATE Users SET 
                                                    username = IFNULL('{0}', username),
                                                    password = IFNULL('{1}', password),
                                                    email = IFNULL('{2}', email),
                                                    isApproved = IFNULL({3}, isApproved)
                                                    WHERE idUser = {4}", [$username, $password, $email, $is_approved, $user_id]);

            if(!is_null($roles) && count($roles) > 0){
                Database::getInstance()->normalQuery("DELETE FROM UserRoles WHERE idUser = {0}", [$user_id]);

                $roleInsert = "";
                foreach ($roles as &$role) {
                    $roleInsert .= "(".$user_id.",".$role."),";
                }
                $roleInsert = rtrim($roleInsert, ',');

                Database::getInstance()->insertQuery("INSERT INTO UserRoles (idUser, idRole) VALUES {0}", [$roleInsert]);
            }

            Database::getInstance()->commitTransaction();

            $created_user = Database::getInstance()->getUsers([$user_id])[0];
            RequestHelper::getInstance()->resolve($created_user);
        } catch (Exception $e) {
            Database::getInstance()->rollbackTransaction();
            RequestHelper::getInstance()->reject([
                "error" => $e->getMessage()
            ]);
        }
    }
?>