<?php 
require("phpHelper.php");
class FTPHelper {
    private static $instance;

	private function __construct()
	{
    }

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    public function __destruct(){

    }

    public function uploadFile($localPath, $destination){
        $destination = $this->ftp_root."/".$destination;

        if($this->connect()){
            $result = ftp_put(
                $this->ftp,
                $destination,
                $localPath
            );
            $this->disconnect();
            return $result;
        }
        return false;
    }

    public function downloadFile($file_id, $extention){
        $this->connect();

        $path = "";
        $fileHash = PHPHelper::getInstance()->randomHash();

        do{
            $path  = $_SERVER['DOCUMENT_ROOT']."/resources/".$fileHash.".".$extention;
        }while(file_exists($path));


        $server_path = $this->ftp_root."/".$file_id.".".$extention;
       
        $ret = ftp_nb_get($this->ftp, $path, $server_path, FTP_BINARY);
        while ($ret == FTP_MOREDATA) {
            $ret = ftp_nb_continue($this->ftp);
        }
        if ($ret != FTP_FINISHED) {
            return false;
        }
        $this->disconnect();
        return $path;

    }

    //TODO: check for security
    public function deleteFromTemp($path){
        //check for only cache file folder
        unlink($path);
    }
}

?>