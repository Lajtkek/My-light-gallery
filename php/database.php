<?php 

class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "light_gallery";
    private $conn;
    
    private static $instance;

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        //echo "Connected successfully";
    }

    function assocQuery($sql, $parameters = []){
        foreach($parameters as $key=>$param){
            $escapedParam = mysqli_real_escape_string($this->conn, $param);
            $sql = str_replace("{".$key."}", $escapedParam, $sql);
        }

        $result = $this->conn->query($sql);

        $resultArray = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                array_push($resultArray, $row);
            }
        }
        return $resultArray;
    }

    public function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function __destruct(){
        
    }
}

?>