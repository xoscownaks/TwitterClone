<?php
    function db_connect(){
      $user = 'root';
      $password = '';
      $db_type = "mysql";
      $db_host = "localhost";
      $db_name = "microposts";
    
      $dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";
      $pdo = new PDO($dsn,$user,$password);
     
      return $pdo;
    }
?>
