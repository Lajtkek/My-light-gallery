<?php
    require_once("../../php/database.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/logHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");
    AuthHelper::getInstance()->auth(["admin"]);
    
    $total_filesize = Database::getInstance()->assocQuery("SELECT  ROUND(SUM(size)/1024/1024,1) as totalFileSize FROM Files")[0];
    $db_size =  Database::getInstance()->assocQuery("SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) 'totalDatabaseSize' 
                FROM information_schema.tables 
                WHERE table_schema = '{0}'
                GROUP BY table_schema; ", [Database::getInstance()->getDatabase()])[0]; 

    RequestHelper::getInstance()->resolve([
        "totalFileSize" =>  (double) $total_filesize["totalFileSize"],
        "maxFilesize" => ConfigHelper::getInstance()->getConfigValue("max_file_size") / 1024,
        "totalDatabaseSize" => (double) $db_size["totalDatabaseSize"],
        "maxDatabaseSize" => ConfigHelper::getInstance()->getConfigValue("max_database_size") / 1024,
    ]);

?>