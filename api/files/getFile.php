<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: image/jpeg');
    //=====================
    require("../../php/database.php");
    require("../../php/requestHelper.php");
    require("../../php/ftpHelper.php");
    //require("../../php/authHelper.php");

    //RequestHelper::getInstance()->checkMethod("GET");
    //$userData = AuthHelper::getInstance()->auth();

    try{
        if(!isset($_GET["permalink"])){
            header('Content-Type: application/json; charset=utf-8');
            die("permalink param required");
        }

        $permalink = $_GET["permalink"];

        $file = Database::getInstance()->assocQuery("SELECT idFile, filename, permalink, mimetype, extension FROM Files WHERE permalink = '{0}'", [$permalink]);

        if(count($file) == 0){
            header('Content-Type: application/json; charset=utf-8');
            die("imageNotFound");
        }

        $filePath = FTPHelper::getInstance()->downloadFile($file[0]["idFile"],$file[0]["extension"]);

        readfile($filePath);
        FTPHelper::getInstance()->deleteFromTemp($filePath);
        //echo json_encode($files);
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "error" => $e->getMessage()
        ]);
    }
?>