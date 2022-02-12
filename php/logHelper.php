<?php 
require_once("database.php");
require_once("authHelper.php");
require_once("requestHelper.php");
require_once("configHelper.php");

class LogHelper {
    private static $instance;

	private function __construct()
	{}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    public function __destruct(){
    }

    public function log($requst_data = null){
        try {
            if(!(ConfigHelper::getInstance()->getConfigValue("logEnabled", false))){
                return;
            }
            $user_data = AuthHelper::getInstance()->auth() ?? (object) [ "idUser" => "null" ];
            $ip = RequestHelper::getInstance()->getIP();
            $requst_data = json_encode($requst_data ?? RequestHelper::getInstance()->getRequestData());
            $ednpoint = explode("?",$_SERVER['REQUEST_URI'])[0];
            Database::getInstance()->insertQuery("INSERT INTO Logs (idUser, ipAddress, detail, endpoint) VALUES ({0}, '{1}', '{2}', '{3}')", [$user_data->idUser, $ip, $requst_data, $ednpoint]);
        } catch (Throwable $th) {
            //
        }
        
    }

}

?>