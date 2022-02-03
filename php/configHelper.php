<?php 
class ConfigHelper {
    private $config;

    public function getConfig()
    {
        return $this->config;
    }

    private static $instance;

	private function __construct()
	{
        $this->config = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/appConfig.ini");

        if($this->config == false){
            throw new Exception("config couldnt be loaded");
        }
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

    public function getConfigValue($value_name)
    {
        return $this->config[$value_name];
    }
}

?>