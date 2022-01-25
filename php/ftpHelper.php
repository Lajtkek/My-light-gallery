<?php 
class FTPHelper {
    private $ftp_server = "";
    private $ftp_port = 0;
    private $ftp_user = "";
    private $ftp_pass = "";
    private $ftp_root = "";
    private $ftp_root_fullpath = "";
    private $ftp;

    private function connect(){
        $this->ftp = ftp_connect($this->ftp_server, $this->ftp_port);

        if($this->ftp === FALSE)
            return FALSE;

        // try to login
        if (@ftp_login($this->ftp, $this->ftp_user, $this->ftp_pass)) {
            return TRUE;
        } else {
            return FALSE;
        }

        // set passive mode
        ftp_pasv($ftp, true);
        return TRUE;
    }

    private function disconnect(){
        ftp_close($this->ftp);
    }

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

}

?>