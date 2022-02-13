<?php
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/fileHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");

    $idFile = RequestHelper::getInstance()->getParam("idFile", true);
    $rating = RequestHelper::getInstance()->getParam("rating", true);
    $identifier = AuthHelper::getInstance()->getUserIdentifier();

    if(!is_numeric($rating))
        RequestHelper::getInstance()->reject("rating_must_be_number");

    $rating = $rating == 0 ? $rating : $rating/abs($rating); //convert to 1,-1,0

    try {
        Database::getInstance()->beginTransaction();
        $user_query = "";

        if($identifier["type"] == "idUser"){
            $user_query = "idUser";
        }else if($identifier["value"] != null){
            $user_query = "ipAddress";
        }else{
            RequestHelper::getInstance()->reject("ip_or_token_required");
        }

        $prev_rating = Database::getInstance()->assocQuery("SELECT rating FROM Rating WHERE idFile = {0} AND ".$user_query." = '{1}'", [$idFile ,$identifier["value"]]);
        $rating_balance = $rating;

        if(count($prev_rating) > 0){
            $rating_balance -= ((int) $prev_rating[0]["rating"]);
            Database::getInstance()->normalQuery("DELETE FROM Rating WHERE idFile = {0} AND ".$user_query." = '{1}'", [$idFile, $identifier["value"]]);
        }

        Database::getInstance()->insertQuery("INSERT INTO Rating (".$user_query.", idFile, rating) VALUES ('{0}', {1}, {2})", [$identifier["value"], $idFile, $rating]);
        Database::getInstance()->normalQuery("UPDATE Files SET rating = (rating + {0}) WHERE idFile = {1}", [$rating_balance, $idFile]);
        
        Database::getInstance()->commitTransaction();
        RequestHelper::getInstance()->resolve([
            "result" => true
        ]);
    } catch (Exception $e) {
        Database::getInstance()->rollbackTransaction();
        RequestHelper::getInstance()->reject([
            "error" => $e->getMessage()
        ]);
    }
 ?>