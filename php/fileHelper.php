<?php 
include_once("phpHelper.php");
class FileHelper {
    private $root_file_path;
    private static $instance;

	private function __construct()
	{
        // Only temporary (endora routes)
        $this->root_file_path = "../../resources/";
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

    public function uploadFile($file_path, $file_base64){
        $temp = getcwd();
        chdir($this->root_file_path);
        $myfile = fopen($file_path, 'wb'); 
        $data = explode(',', $file_base64);

        fwrite($myfile, base64_decode($data[1]));
        fclose($myfile); 
        chdir($temp);
    }

    //TODO: check for security
    public function deleteFile($path){
        //unlink($path);
    }
}

?>