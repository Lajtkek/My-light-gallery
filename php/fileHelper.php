<?php 
include_once("phpHelper.php");
class FileHelper {
    private $root_path;
    private static $instance;

	private function __construct()
	{
        // Only temporary (endora routes)
        $this->root_path = "..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
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
        $file_path = $this->root_path.$file_path;
        $myfile = @fopen($file_path, 'wb'); 

        fwrite($myfile, base64_decode($file_base64));
        fclose($myfile); 
    }

    public function appendToFile($file_path, $file_base64){
        $file_path = $this->root_path.$file_path;
        file_put_contents($file_path, base64_decode($file_base64), FILE_APPEND);
    }

    public function getFileSize($file_path){
        $file_path = $this->root_path.$file_path;
        $size = filesize($file_path);
        return $size;
    }

    public function getFileResource($file_path){
        $file_path = $this->root_path.$file_path;
        // $type = pathinfo($file_path, PATHINFO_EXTENSION);
        // $data = file_get_contents($file_path);

        //return 'data:image/' . $type . ';base64,' . base64_encode($data);
        return fopen($file_path, 'rb');
    }

    public function renameFile($oldname, $newname){
        $oldname = $this->root_path.$oldname;
        $newname = $this->root_path.$newname;
        rename($oldname, $newname);
    }

    //TODO: check for security
    public function deleteFile($file_path){
        $file_path = $this->root_path.$file_path;
        unlink($file_path);
    }
}

?>