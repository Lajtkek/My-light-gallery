<?php
    //CHANGE FOR PRODUCTION
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json; charset=utf-8');
    //=====================
    require("../../php/phpHelper.php");
    require("../../php/requestHelper.php");
    require("../../php/ftpHelper.php");
    require("../../php/database.php");
    require("../../php/authHelper.php");

    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth();

    //todo check role

    //param structure { filename, description, file, tags }
    $filename = RequestHelper::getInstance()->getParam("filename");
    $extension = RequestHelper::getInstance()->getParam("extension");
    $filetype = RequestHelper::getInstance()->getParam("fileType");
    $description = RequestHelper::getInstance()->getParam("description");
    $base64 = RequestHelper::getInstance()->getParam("base64");
    $tags = RequestHelper::getInstance()->getParam("tags");

    $timestamp = (new DateTime())->getTimestamp();
    
    $permalink;

    do {
        $permalink = PHPHelper::getInstance()->randomHash();
        $result = Database::getInstance()->assocQuery("SELECT permalink FROM Files WHERE permalink = '{0}'", [$permalink]);
    } while (count($result) !== 0);

    $destinationFilename = $filename.".".$extension;
    //CREATE DB RECORD
    $idFile = Database::getInstance()->insertQuery("INSERT INTO Files (idUser, filename, permalink, mimeType) VALUES ({0}, '{1}', '{2}', '{3}')", [$userData->idUser, $destinationFilename, $permalink, $filetype]);

    //UPLOAD FILE
    //LOCALHOST ONLY?? 
    $userDirPath = str_replace("/", "\\",$_SERVER['DOCUMENT_ROOT'])."\\php\\tempFiles\\".$userData->username;
    @mkdir($userDirPath);

    $filename_ = $idFile.".".$extension;;
    $createdFile = $userDirPath."\\".$filename_;

    //TODO CHECK FOR LIKE .PHP FILES EVEN THO THEY WILL BE DELETED COULD BE VELKÝ ŠPATNÝ
    $myfile = fopen($createdFile, 'wb'); 
    $data = explode(',', $base64);

    fwrite($myfile, base64_decode($data[1]));

    $file_metadata = stream_get_meta_data($myfile);

    fclose($myfile); 

    $result = FTPHelper::getInstance()->uploadFile("../../php/tempFiles/".$userData->username."/".$filename_, $filename_);//.$destinationFilename);

    unlink($file_metadata["uri"]);

    echo json_encode([
        "result" => $result
    ]);
 ?>