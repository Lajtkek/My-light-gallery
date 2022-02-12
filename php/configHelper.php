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
        // Todo refactor
        $temp = getcwd();
        while(!file_exists("appConfig.ini")){
            chdir("../");
        }

        $this->config = parse_ini_file("appConfig.ini");

        chdir($temp);

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

    public function getConfigValue($value_name, $fallback = null)
    {
        if(!array_key_exists($value_name,$this->config))
            return $fallback;
            
        return $this->config[$value_name];
    }
}

?>