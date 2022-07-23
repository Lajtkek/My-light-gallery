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

    public function setHeader(){
        header("Access-Control-Allow-Origin: http://localhost:8080");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH');
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header('Content-Type: application/json; charset=utf-8');
    }

    public function checkMethod($wantedMethod){
        if($_SERVER['REQUEST_METHOD'] != $wantedMethod)
            $this->reject($wantedMethod."_REQUEST_REQUIRED");
    }

    public function getRequestData(){
        $request_body = file_get_contents('php://input');
        $request_data = json_decode($request_body);

        if($_SERVER['REQUEST_METHOD'] === "GET"){
            $request_data = (object) $_GET;
        }
        return $request_data;
    }
    
    public function getParam($paramName, $required = false){
        $request_data = $this->getRequestData();

        if(!is_null($request_data) && property_exists($request_data,$paramName)){
            return $request_data->$paramName;
        }else{
            if($required)
                $this->reject("param '".$paramName."' is required");
            else    
                return null;
        }
    }

    public function validateParam($var_value, $param_name, $rules){
        foreach ($rules as &$rule) {
            $name = $rule["name"];
            $value = $rule["value"];

            switch ($name) {
                case 'minLength':
                    if(strlen($var_value) < $value)
                        $this->reject("Param named ". $param_name ." must be at least ".$value." long ");
                    break;
                case 'maxLength':
                    if(strlen($var_value) > $value)
                        $this->reject("Param named ". $param_name ." must be at most ".$value." long");
                    break;
                case 'inArray':
                    if(!in_array($var_value, $value))
                        $this->reject("UNSUPPORTED_EXTENSION");
                    break;
                case 'byteSize':
                    $byte_size = strlen($var_value);
                    if($value != -1 && $byte_size > $value)
                        $this->reject("TOO_LARGE");
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

    public function reject($error = null){
        if(is_null($error)){   
            die($this->encodeJson([
                "error" => true
            ]));
        }
        if(is_string($error)){   
            die($this->encodeJson([
                "error" => $error
            ]));
        }
        if(is_object($error) && get_class($error) == "Exception"){
            die($this->encodeJson([
                "error" => $error->getMessage()
            ]));
        }
        die($this->encodeJson([
            "error" => $error
        ]));
    }

    public function resolve($data = null){
        if(is_null($data)){
            die($this->encodeJson([
                "success" =>  true 
            ]));
        }
        if(is_string($data) || is_numeric($data)){   
            die($this->encodeJson([
                "success" => $data
            ]));
        }
        die($this->encodeJson($data));
    }

    public function encodeJson($data){
        return json_encode($data, JSON_NUMERIC_CHECK);
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