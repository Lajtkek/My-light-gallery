<?php 
class PHPHelper {
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

	public function randomHash($length = 32){
		return bin2hex(random_bytes($length));
	}
}

?>