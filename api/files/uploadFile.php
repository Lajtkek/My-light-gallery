<?php
    require_once("../../php/phpHelper.php");
    require_once("../../php/configHelper.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/database.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/fileHelper.php");
    require_once("../../php/logHelper.php");

    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("POST");
    $userData = AuthHelper::getInstance()->auth();

    $filename = RequestHelper::getInstance()->getParam("filename", true);
    RequestHelper::getInstance()->validateParam($filename, "filename", [
        [
            "name" => "minLength",
            "value" => 3
        ]
    ]);
    $extension = RequestHelper::getInstance()->getParam("extension", true);
    
    $mimeType = RequestHelper::getInstance()->getParam("mimeType", true);
    $description = RequestHelper::getInstance()->getParam("description", true);
    $base64 = RequestHelper::getInstance()->getParam("base64", true);
    RequestHelper::getInstance()->validateParam($base64, "base64", [
        [
            "name" => "byteSize",
            "value" => (int) ConfigHelper::getInstance()->getConfigValue("max_file_size")
        ]
    ]);
    RequestHelper::getInstance()->validateParam($extension, "extension", [
        [
            "name" => "inArray",
            "value" => explode(",",ConfigHelper::getInstance()->getConfigValue("allowed_filetypes"))
        ]
    ]);
    $tags = RequestHelper::getInstance()->getParam("tags", true);

    $log_data = RequestHelper::getInstance()->getRequestData();
    $log_data->base64 = "Big File :-)";
    LogHelper::getInstance()->log($log_data);

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
        

        $filename_chunks = explode(".", $file_path);

        if(strtolower($filename_chunks[count($filename_chunks)-1]) == "php"){
            RequestHelper::getInstance()->reject("forbiden_file_type");
        }
        
        FileHelper::getInstance()->uploadFile($file_path, $base64);
        $size_in_kB = FileHelper::getInstance()->getFileSize($file_path); //kB;

        Database::getInstance()->beginTransaction();
		$is_temporary = in_array("admin",$userData->roles) == true ? 0 : 1;

        $idFile = Database::getInstance()->insertQuery("INSERT INTO Files (idUser, filename, permalink, mimeType, extension, size, description, isTemporary) VALUES ({0}, '{1}', '{2}', '{3}', '{4}', '{5}', '{6}', {7})", [$userData->idUser, $filename, $permalink, $mimeType, $extension, $size_in_kB, $description, $is_temporary]);

        foreach ($tags as &$tag) {
            Database::getInstance()->insertQuery("INSERT INTO FileTags (idFile, idTag) VALUES ({0}, {1})", [$idFile, $tag->idTag]);
        }

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