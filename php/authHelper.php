<?php 
require_once("phpHelper.php");
require_once("configHelper.php");
require_once("authHelper.php");
class AuthHelper {
    //https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
    private $secret;

    private static $instance;

	private function __construct()
	{
        $this->secret = ConfigHelper::getInstance()->getConfigValue("sercret_key");
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

    function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    public function generateToken($payload){
        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        // Create the token payload
        $payload = json_encode($payload);

        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }
    
    public function auth($required_roles = []){
        $die_on_error = count($required_roles) > 0;
        $token = $this->getToken();

        if(!is_null($token))
            $token = $this->parseToken($token, $die_on_error);

        if(count($required_roles) > 0){
            if(is_null($token))
                die(json_encode([
                    "error" => "missing token:"
                ]));

            $user_roles = $token->roles;

            foreach ($required_roles as &$role) {
                if(!in_array($role, $user_roles)){
                    die(json_encode([
                        "error" => "missing role:". $role
                    ]));
                }
            }
        }

        return $token;
    }

    public function getToken(){
        $token = null;
        if(!array_key_exists("Authorization",apache_request_headers()))
            return null;
        else
            $token = str_replace("Bearer ", "", apache_request_headers()["Authorization"]);
        
        if($token == "undefined") $token = null;
        return $token;
    }

    public function parseToken($token, $die_on_error = true){
        if(is_null($token) || !is_string($token))
            return null;

        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = json_decode(base64_decode($tokenParts[1]));
        $signatureProvided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = new DateTime("@$payload->exp");
        $now = new DateTime();
        $tokenExpired = $expiration < $now;

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // verify it matches the signature provided in the token
        $signatureInvalid = !($base64UrlSignature === $signatureProvided);

        if ($tokenExpired) {
            if(!$die_on_error)
                return "token_expired";
            die(json_encode([
                "error" => "token_expired"
            ]));
        }

        if ($signatureInvalid) {
            if(!$die_on_error)
                return "invalid_signature";
            die(json_encode([
                "error" => "invalid_signature"
            ]));
        }

        return $payload;
    }

    function getUserIdentifier(){
        $userData = $this->auth();
        $identifier = RequestHelper::getInstance()->getIP() ?? -1;

        if(!is_null($userData) && !is_string($userData)){
            $identifier = [
                "value" => $userData->idUser,
                "type" => "idUser"
            ];
        }else{
            $identifier = [
                "value" => $identifier,
                "type" => "ip"
            ];
        }

        return $identifier;
    }
}

?>