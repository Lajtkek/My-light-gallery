<?php 
class AuthHelper {
    //https://developer.okta.com/blog/2019/02/04/create-and-verify-jwts-in-php
    private $secret = "7c32d31dbdd39f2111da0b1dea59e94f3ed715fd8cdf0ca3ecf354ca1a2e3e30";

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
        if(!array_key_exists("Authorization",apache_request_headers()))
            die("token_is_null");

        $token = str_replace("Bearer ", "", apache_request_headers()["Authorization"]);

        return $this->validateToken($token, $required_roles);
    }

    public function validateToken($token, $required_roles = []){
        if($token == null)
            die("token_is_null");
        // split the token
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

        if(count($required_roles) > 0){
            $user_roles = $payload->roles;

            foreach ($required_roles as &$role) {
                if(!in_array($role, $user_roles)){
                    die(json_encode([
                        "error" => "missing role:". $role
                    ]));
                }
            }
        }

        if ($tokenExpired) {
            die(json_encode([
                "error" => "token_expired"
            ]));
        }

        if ($signatureInvalid) {
            die(json_encode([
                "error" => "invalid_signature"
            ]));
        }

        return $payload;
    }
}

?>