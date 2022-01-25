<?php 
class RequestHelper {
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

    public function checkMethod($wantedMethod){
        if($_SERVER['REQUEST_METHOD'] != $wantedMethod)
            die($wantedMethod."_REQUEST_REQUIRED");
    }

    public function getParam($paramName, $required = false){
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body);
        if(property_exists($request_data,$paramName)){
            return $request_data->$paramName;
        }else{
            if($required)
                die("param '".$paramName."' is required");
            else    
                return null;
        }
    }
}

?>