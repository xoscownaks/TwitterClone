<?php
session_start();
require_once "mydb.php";

    $user_email = $_POST['email'];
    $password   = $_POST['password'];
    
    $pdo = db_connect();
    
    $sql = "SELECT * FROM users WHERE email = :email AND password=:password";
    $stt = $pdo->prepare($sql);
    $stt->bindValue(':email', $user_email);
    $stt->bindValue(':password', $password);
    $stt->execute();
    $result = $stt->rowCount();
    $row    = $stt->fetchAll();
    
    //email정보가 없을 때 
    if(!$result){
        print "<script>location.replace('../view/login.php')</script>";
    }else{
        
        
        if($password == $row[0]['password']){
            $_SESSION['user_id']     = $row[0]['id'];
            $_SESSION['user_name']   = $row[0]['name'];
            
            print "<script>location.replace('../index.php')</script>";
        }
    }
?>