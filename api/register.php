<?php
    require_once("../php/configHelper.php");
    require_once("../php/requestHelper.php");
    require_once("../php/database.php");
    require_once("../php/authHelper.php");
    require_once("../php/phpHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");

    $username = RequestHelper::getInstance()->getParam("username", true);
    $password = RequestHelper::getInstance()->getParam("password", true);
    $code = RequestHelper::getInstance()->getParam("code", true);

    $codeUsed = Database::getInstance()->assocQuery("SELECT used FROM RegisterCodes WHERE code = '{0}'", [$code]);

    if(count($codeUsed) == 0)
        RequestHelper::getInstance()->reject("CODE_DOESNT_EXIST");

	if($codeUsed[0]["used"] == 1)
		RequestHelper::getInstance()->reject("CODE_ALREADY_USED");

	$user = Database::getInstance()->assocQuery("SELECT idUser FROM Users WHERE username = '{0}'", [$username]);
    if(count($user) > 0){
        RequestHelper::getInstance()->reject("USERNAME_ERROR");
    }

	$password = password_hash($password, PASSWORD_DEFAULT);

    $user_id = Database::getInstance()->insertQuery("INSERT INTO Users (username, password, isApproved) VALUES ('{0}', '{1}', {2})", [$username, $password, 1]);
	Database::getInstance()->normalQuery("UPDATE RegisterCodes SET used = 1 WHERE code = '{0}'", [$code]);

    RequestHelper::getInstance()->resolve($user_id);
?>