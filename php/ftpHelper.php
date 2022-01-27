<?php 
require("phpHelper.php");
class FTPHelper {
    private $ftp_server = "";
    private $ftp_port = 0;
    private $ftp_user = "";
    private $ftp_pass = "";
    private $ftp_root = "";
    private $ftp_root_fullpath = "";
    private $ftp;

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

    private function connect(){
        $this->ftp = ftp_connect($this->ftp_server, $this->ftp_port);

        if($this->ftp === FALSE)
            return FALSE;

        if (@ftp_login($this->ftp, $this->ftp_user, $this->ftp_pass)) {
            ftp_pasv($this->ftp, true);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function disconnect(){
        ftp_close($this->ftp);
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
            $path  = $_SERVER['DOCUMENT_ROOT']."/php/serverTempFiles/".$fileHash.".".$extention;
        }while(file_exists($path));


        $server_path = $this->ftp_root."/".$file_id.".".$extention;
       
        
        if (ftp_get($this->ftp, $path, $server_path, FTP_BINARY)) {
            $this->disconnect();
            return $path;
        } else {
            //todo: return like 404 image
            echo "There was a problem\n";
        }
        
        $this->disconnect();
    }

    //TODO: check for security
    public function deleteFromTemp($path){
        //check for only cache file folder
        unlink($path);
    }
}

?>