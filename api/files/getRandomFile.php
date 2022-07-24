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

    $seed = RequestHelper::getInstance()->getParam("seed", false) ?? 0;
    $tag = RequestHelper::getInstance()->getParam("tag", false) ?? '';

    $private_enabled = (int) 0;

    $files = Database::getInstance()->assocQuery("SELECT f.idFile, f.filename, concat(f.permalink,'.', f.extension) as permalink, f.mimeType, f.extension, f.description, f.rating as globalRating  FROM FILES f
        LEFT JOIN filetags ft ON(ft.idFile = f.idFile)
        LEFT JOIN tags t ON(t.idTag = ft.idTag AND (t.code = '{0}' OR '{0}' = ''))
        WHERE t.idTag IS NOT NULL", [$tag]);

	if(count($files) == 0){
		RequestHelper::getInstance()->reject("Nenalezen");
	}

    $count = count($files);

    $index = $seed%$count;

    $file = $files[$index];

    $_filename = "resources".DIRECTORY_SEPARATOR.$file["permalink"];
    $filename = basename($_filename);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpeg"; break;
        case "svg": $ctype="image/svg+xml"; break;
        default:
    }

    header('Content-type: ' . $ctype);
    header("Content-Length: " . FileHelper::getInstance()->getFileSize($_filename));

    $res = FileHelper::getInstance()->getFileResource($_filename);
    fpassthru($res);
?>