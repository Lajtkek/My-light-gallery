<?php
    //header('Content-type:application/json;charset=utf-8');
    // require("php/database.php");
    
    // $database = new Database;
    // $a = $database->assocQuery("Select * FROM Users WHERE username = {0}", ["lajtkek"]);

    // foreach($a as $value){
    //     echo $value["username"];
    // }

    require("php/authHelper.php");
    $authHelper = new AuthHelper;
    
    //1702940400
    $token = $authHelper->generateToken([
        'user_id' => 1,
        'role' => 'admin_kek',
        'exp' => 1993828222
    ]);
    
    echo $token;
    echo "<br>";

    print_r($authHelper->validateToken($token));
?>