<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/database.php");
    require("../../php/requestHelper.php");
    require("../../php/authHelper.php");
    
    RequestHelper::getInstance()->checkMethod("GET");
    AuthHelper::getInstance()->auth(["admin"]);
    

    $total_filesize = Database::getInstance()->assocQuery("SELECT  ROUND(SUM(size)/1024/1024,1) as totalFileSize FROM Files")[0];
    $db_size =  Database::getInstance()->assocQuery("SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) 'totalDatabaseSize' 
                FROM information_schema.tables 
                WHERE table_schema = '{0}'
                GROUP BY table_schema; ", [Database::getInstance()->database])[0]; 

    //todo config
    RequestHelper::getInstance()->resolve([
        "totalFileSize" =>  (double) $total_filesize["totalFileSize"],
        //endora max disc space 6GB
        "maxFilesize" => 6 * 1024 * 1024,
        "totalDatabaseSize" => (double) $db_size["totalDatabaseSize"],
        //endora max db space 60 MB
        "maxDatabaseSize" => 60 * 1024,
    ]);

?>