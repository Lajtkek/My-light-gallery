<?php
    require_once("../../php/database.php");
    require_once("../../php/requestHelper.php");
    require_once("../../php/authHelper.php");
    require_once("../../php/logHelper.php");
    require_once("../../php/fileHelper.php");
    
    RequestHelper::getInstance()->setHeader();
    RequestHelper::getInstance()->checkMethod("GET");

    $user_data = AuthHelper::getInstance()->auth();
    $ratingQuery = "";

    LogHelper::getInstance()->log();

    $seed = RequestHelper::getInstance()->getParam("seed", false) ?? -1;
    $tag = RequestHelper::getInstance()->getParam("tag", false) ?? '';

    $private_enabled = (int) 0;

    $files = Database::getInstance()->assocQuery("SELECT f.idFile, f.filename, concat(f.permalink,'.', f.extension) as permalink, f.mimeType, f.extension, f.description, f.rating as globalRating  FROM Files f
        LEFT JOIN FileTags ft ON(ft.idFile = f.idFile)
        LEFT JOIN Tags t ON(t.idTag = ft.idTag AND (t.code = '{0}' OR '{0}' = ''))
        LEFT JOIN Tags pt ON(t.idTag = pt.idTag AND pt.isPublic = 0)
        WHERE t.idTag IS NOT NULL AND f.idFile IS NOT NULL AND f.mimeType LIKE '%IMAGE%'
        GROUP BY f.idFile
        HAVING  COUNT(pt.idTag) = 0", [$tag]);


    $_filename = "public/not_found.png";
	if(count($files) > 0){
        $count = count($files);

        $index = $seed != -1 ? $seed%$count : rand(0, $count-1);

        $file = $files[$index];

        $_filename = "resources".DIRECTORY_SEPARATOR.$file["permalink"];
    }

    $filename = basename($_filename);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));
    $ctype = FileHelper::getInstance()->getCType($_filename);

    header('Content-type: ' . $ctype);
    header("Content-Length: " . FileHelper::getInstance()->getFileSize($_filename));

    $res = FileHelper::getInstance()->getFileResource($_filename);
    fpassthru($res);
?>