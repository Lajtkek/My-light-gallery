<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/phpHelper.php");
    require("../../php/requestHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");
    require("../../php/fileHelper.php");

    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth();

    //param structure { filename, description, file, tags }
    $filename = RequestHelper::getInstance()->getParam("filename");
    $extension = RequestHelper::getInstance()->getParam("extension");
    $filetype = RequestHelper::getInstance()->getParam("fileType");
    $description = RequestHelper::getInstance()->getParam("description");
    $base64 = RequestHelper::getInstance()->getParam("base64");
    $tags = RequestHelper::getInstance()->getParam("tags");

    $tmp_file_path = "";
    $file_metadata;
    $file_uri;
    try {
        $timestamp = (new DateTime())->getTimestamp();
        $permalink;

        do {
            $permalink = PHPHelper::getInstance()->randomHash();
            $result = Database::getInstance()->assocQuery("SELECT permalink FROM Files WHERE permalink = '{0}'", [$permalink]);
        } while (count($result) !== 0);

        $filename = $filename.".".$extension;

        Database::getInstance()->beginTransaction();
        $idFile = Database::getInstance()->insertQuery("INSERT INTO Files (idUser, filename, permalink, mimeType, extension) VALUES ({0}, '{1}', '{2}', '{3}', '{4}')", [$userData->idUser, $filename, $permalink, $filetype, $extension]);

        $file_path = $permalink.".".$extension;

        //TODO CHECK FOR LIKE .PHP FILES EVEN THO THEY WILL BE DELETED COULD BE VELKÝ ŠPATNÝ
        FileHelper::getInstance()->uploadFile($file_path,$base64);


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