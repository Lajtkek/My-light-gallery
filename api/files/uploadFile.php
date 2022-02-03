<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/fileHelper.php");

    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth(["admin"]);

    //param structure { filename, description, file, tags }
    $filename = RequestHelper::getInstance()->getParam("filename");
    $extension = RequestHelper::getInstance()->getParam("extension");
    $mimeType = RequestHelper::getInstance()->getParam("mimeType");
    $description = RequestHelper::getInstance()->getParam("description");
    $base64 = RequestHelper::getInstance()->getParam("base64");
    $tags = RequestHelper::getInstance()->getParam("tags");

    try {
        $timestamp = (new DateTime())->getTimestamp();
        $permalink;
        $hash_size = (int) ConfigHelper::getInstance()->getConfigValue("hash_size");
        
        do {
            $permalink = randomHash($hash_size);
            $result = Database::getInstance()->assocQuery("SELECT permalink FROM Files WHERE permalink = '{0}'", [$permalink]);
        } while (count($result) !== 0);

        $filename = $filename.".".$extension;
        $file_path = $permalink.".".$extension;
        
        //TODO CHECK FOR LIKE .PHP FILES EVEN THO THEY WILL BE DELETED COULD BE VELKÝ ŠPATNÝ
        FileHelper::getInstance()->uploadFile($file_path, $base64);
        $size_in_kB = FileHelper::getInstance()->getFileSize($file_path); //kB;

        Database::getInstance()->beginTransaction();
        $idFile = Database::getInstance()->insertQuery("INSERT INTO Files (idUser, filename, permalink, mimeType, extension, size, description) VALUES ({0}, '{1}', '{2}', '{3}', '{4}', '{5}', '{6}')", [$userData->idUser, $filename, $permalink, $mimeType, $extension, $size_in_kB, $description]);



        foreach ($tags as &$tag) {
            Database::getInstance()->insertQuery("INSERT INTO FileTags (idFile, idTag) VALUES ({0}, {1})", [$idFile, $tag->idTag]);
        }

        Database::getInstance()->commitTransaction();

        echo json_encode([
            "result" => true
        ]);
    } catch (Exception $e) {
        Database::getInstance()->rollbackTransaction();
        echo json_encode([
            "error" => $e->getMessage()
        ]);
    } finally {
        try{
            //@unlink($file_uri);
        }catch (Exception $e){
            
        }
    }
 ?>