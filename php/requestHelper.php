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
            $this->reject($wantedMethod."_REQUEST_REQUIRED");
    }

    public function getParam($paramName, $required = false){
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body);

        if($_SERVER['REQUEST_METHOD'] === "GET"){
            $request_data = (object) $_GET;
        }

        if(!is_null($request_data) && property_exists($request_data,$paramName)){
            return $request_data->$paramName;
        }else{
            if($required)
                $this->reject("param '".$paramName."' is required");
            else    
                return null;
        }
    }

    public function reject($error = null){
        if(is_null($error)){   
            die(json_encode([
                "error" => true
            ]));
        }
        if(is_string($error)){   
            die(json_encode([
                "error" => $error
            ]));
        }
        die(json_encode([
            "error" => $error
        ]));
    }

    public function resolve($data = null){
        if(is_null($data)){
            die(json_encode([
                "success" =>  true 
            ]));
        }
        if(is_string($data) || is_numeric($data)){   
            die(json_encode([
                "success" => $data
            ]));
        }
        die(json_encode($data));
    }

    public function getIP(){
        return $_SERVER['HTTP_CLIENT_IP'] 
            ?? $_SERVER["HTTP_CF_CONNECTING_IP"] # when behind cloudflare
            ?? $_SERVER['HTTP_X_FORWARDED'] 
            ?? $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['HTTP_FORWARDED'] 
            ?? $_SERVER['HTTP_FORWARDED_FOR'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? NULL;
    }
}

?>