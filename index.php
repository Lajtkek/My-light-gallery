<?php
    require("php/database.php");
    
    $database = new Database;

    $a = $database->assocQuery("Select * FROM Users WHERE username = {0}", ["lajtkek"]);
    echo "<br>";
    foreach($a as $value){
        echo $value["username"];
    }
?>