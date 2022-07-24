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

    RequestHelper::getInstance()->validateParam($extension, "extension", [
        [
            "name" => "inArray",
            "value" => explode(",",ConfigHelper::getInstance()->getConfigValue("allowed_filetypes"))
        ]
    ]);

    $chunk = RequestHelper::getInstance()->getParam("chunk", true);
    $totalChunks = RequestHelper::getInstance()->getParam("totalChunks", true);


    // RequestHelper::getInstance()->validateParam($base64, "base64", [
    //     [
    //         "name" => "byteSize",
    //         "value" => (int) ConfigHelper::getInstance()->getConfigValue("max_file_size")
    //     ]
    // ]);



    $tags = RequestHelper::getInstance()->getParam("tags", true);

    $log_data = RequestHelper::getInstance()->getRequestData();
    $log_data->base64 = "Big File :-)";
    LogHelper::getInstance()->log($log_data);

    
    $permalink = RequestHelper::getInstance()->getParam("permalink", false);
    if($chunk == 0){
        do {
            $permalink = randomHash(24);
            $result = Database::getInstance()->assocQuery("SELECT permalink FROM TempFiles WHERE permalink = '{0}'", [$permalink]);
        } while (count($result) !== 0);

        $file_path = "tempFiles".DIRECTORY_SEPARATOR.$permalink.".temp";
        Database::getInstance()->insertQuery("INSERT INTO TempFiles (idUser, permalink) VALUES ({0}, '{1}')", [$userData->idUser, $permalink]);
        FileHelper::getInstance()->uploadFile($file_path, "");
    }

    if(is_null($permalink))
    RequestHelper::getInstance()->reject("PERMALINK_REQUIRED");

    if($chunk < $totalChunks){
        $result = Database::getInstance()->assocQuery("SELECT permalink FROM TempFiles WHERE permalink = '{0}'", [$permalink]);

        if(is_null($result))
            RequestHelper::getInstance()->reject("file with this permalink doesnt exist");

        $temp_path = "tempFiles/".DIRECTORY_SEPARATOR.$permalink.".temp";
        FileHelper::getInstance()->appendToFile($temp_path, $base64);

        $size = FileHelper::getInstance()->getFileSize($temp_path);
        $max_size = (int) ConfigHelper::getInstance()->getConfigValue("max_file_size");
        if($max_size != -1 && $size > $max_size){
            FileHelper::getInstance()->deleteFile($file_path);
            Database::getInstance()->normalQuery("DELETE FROM TempFiles WHERE permalink = '{0}'", [$permalink]);
            RequestHelper::getInstance()->reject("FILE_SIZE_EXCEEDED");
        }
    }
    
    if($chunk == $totalChunks-1){
        try {
            Database::getInstance()->beginTransaction();
            Database::getInstance()->normalQuery("DELETE FROM TempFiles WHERE permalink = '{0}'", [$permalink]);
            $old_permalink = $permalink;

            $timestamp = (new DateTime())->getTimestamp();
            $permalink;
            $hash_size = (int) ConfigHelper::getInstance()->getConfigValue("hash_size");
            
            do {
                $permalink = randomHash($hash_size);
                $result = Database::getInstance()->assocQuery("SELECT permalink FROM Files WHERE permalink = '{0}'", [$permalink]);
            } while (count($result) !== 0);

            $filename = $filename.".".$extension;
            $file_path = $permalink.".".$extension;

            //move file
            FileHelper::getInstance()->renameFile("tempFiles".DIRECTORY_SEPARATOR.$old_permalink.".temp", "resources".DIRECTORY_SEPARATOR.$file_path);

            $size_in_kB = FileHelper::getInstance()->getFileSize("resources".DIRECTORY_SEPARATOR.$file_path); //kB;

            $is_temporary = in_array("admin",$userData->roles) == true ? 0 : 1;

            $idFile = Database::getInstance()->insertQuery("INSERT INTO Files (idUser, filename, permalink, mimeType, extension, size, description, isTemporary) VALUES ({0}, '{1}', '{2}', '{3}', '{4}', '{5}', '{6}', {7})", [$userData->idUser, $filename, $permalink, $mimeType, $extension, $size_in_kB, $description, $is_temporary]);

            foreach ($tags as &$tag) {
                Database::getInstance()->insertQuery("INSERT INTO FileTags (idFile, idTag) VALUES ({0}, {1})", [$idFile, $tag->idTag]);
            }

            Database::getInstance()->commitTransaction();

            RequestHelper::getInstance()->resolve([
                "idFile" => $idFile
            ]);
        } catch (Exception $e) {
            Database::getInstance()->rollbackTransaction();
            RequestHelper::getInstance()->reject([
                "error" => $e->getMessage()
            ]);
        }
    }

    RequestHelper::getInstance()->resolve([
        "permalink" => $permalink
    ]);
 ?>