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


    $createdFile = $_SERVER['DOCUMENT_ROOT']."\\php\\tempFiles\\".str_replace('/', '_', $filename.".".$extension);

    $myfile = fopen( $createdFile, 'wb' ); 
    $data = explode( ',', $base64 );

    fwrite( $myfile, base64_decode( $data[ 1 ] ) );

    $file_metadata = stream_get_meta_data($myfile);

    fclose( $myfile ); 



    echo json_encode([
        "result" => $file_metadata["uri"]
    ]);

    //$tags = Database::getInstance()->assocQuery("SELECT idTag, name, code, concat('#',color) as color FROM Tags");

    //TODO: upload
    //echo json_encode($tags);
 ?>