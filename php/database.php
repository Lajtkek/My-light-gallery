<?php 
require_once("configHelper.php");
class Database {
    private $servername;
    private $username;
    private $password;
    private $database;

    public function getDatabase(){
        return $this->database;
    }

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
        $this->servername = ConfigHelper::getInstance()->getConfigValue("servername");
        $this->username = ConfigHelper::getInstance()->getConfigValue("username");
        $this->password = ConfigHelper::getInstance()->getConfigValue("password");
        $this->database = ConfigHelper::getInstance()->getConfigValue("database");

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function renderSQL($sql, $parameters = []){
        foreach($parameters as $key=>$param){
            $escapedParam = mysqli_real_escape_string($this->conn, $param);
            $sql = str_replace("{".$key."}", $escapedParam, $sql);
        }
        return $sql;
    }

    function assocQuery($sql, $parameters = []){
        $sql = $this->renderSQL($sql, $parameters);

        $result = $this->conn->query($sql);

        $resultArray = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                array_push($resultArray, $row);
            }
        }
        return $resultArray;
    }

    function insertQuery($sql, $parameters = []){
        $sql = $this->renderSQL($sql, $parameters);

        if ($this->conn->query($sql) === TRUE) {
            return $this->conn->insert_id;
        } else {
            return FALSE;
        }
    }

    function normalQuery($sql, $parameters = []){
        $sql = $this->renderSQL($sql, $parameters);

        return $this->conn->query($sql);
    }

    function beginTransaction(){
        $this->conn->begin_transaction();
    }

    function commitTransaction(){
        $this->conn->commit();
    }

    function rollbackTransaction(){
        try{
            $this->conn->rollback();
        }catch (Exception $e){

        }
    }

    public function getUsers($user_ids = []){
        $filter_ids = count($user_ids) > 0 ? 1 : 0;
        $user_ids = $filter_ids == 1 ? "(".implode(",",$user_ids,).")" : "(0)";

        $users = $this->assocQuery("SELECT idUser, username, email, isApproved, createdAt FROM Users WHERE idUser IN {0} OR {1} = 0 ORDER BY idUser", [$user_ids, $filter_ids]);
        $roles = $this->assocQuery("SELECT ur.idUser, r.name, r.idRole FROM UserRoles ur LEFT JOIN Roles r ON(r.idRole = ur.idRole)  WHERE ur.idUser IN {0} OR {1} = 0 ORDER BY r.name asc", [$user_ids, $filter_ids]);
    
        foreach ($users as &$user) {
            $user["roles"] = array_filter($roles, function ($role) use (&$user){
                return $role["idUser"] == $user["idUser"];
            });

            $user["roles"] = array_map(function ($role){
                return [
                    "idRole" => $role["idRole"],
                    "name" => $role["name"]
                ];
            }, $user["roles"]);
        }

        return $users;
    }

    public function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function __destruct(){
        
    }
}

?>