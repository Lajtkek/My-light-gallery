<?php 
include_once("phpHelper.php");
class FileHelper {
    private $root_file_path;
    private static $instance;

	private function __construct()
	{
        $this->root_file_path = $_SERVER['DOCUMENT_ROOT']."\\resources\\";
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

    public function uploadFile($path, $file_base64){
        $file_path = $this->root_file_path.$path;
        $myfile = fopen($file_path, 'wb'); 
        $data = explode(',', $file_base64);

        fwrite($myfile, base64_decode($data[1]));
        $file_metadata = stream_get_meta_data($myfile);
        fclose($myfile); 
    }

    //TODO: check for security
    public function deleteFile($path){
        //unlink($path);
    }
}

?>