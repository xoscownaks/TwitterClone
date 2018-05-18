<?php
session_start();
require_once "mydb.php";

    if(isset($_POST['submit'])){
        
        $user_name      = $_POST['name'];
        $user_email     = $_POST['email'];
        $user_password  = $_POST['password'];
        $user_confirm   = $_POST['password_confirmation'];
        
        $pdo = db_connect();
        $sql = "SELECT email FROM users WHERE user_email = :email";
        $stt = $pdo->prepare($sql);
        $stt->bindValue(':email',$user_email);
        $stt->execute();
        $result = $stt->rowCount();
        
        if($user_password !== $user_confirm){
            echo "<script>alert('Miss match password and confrim')</script>";
            print "<script>history.go(-1)</script>";
        }
        if($user_password == $user_confirm && !$result){
            $sql = "INSERT INTO users (name, email, password) VALUES(:name, :email, :password)";
            $stt = $pdo->prepare($sql);
            $stt->bindValue(':name', $user_name);
            $stt->bindValue(':email', $user_email);
            $stt->bindValue(':password', $user_password);
            $stt->execute();
            
            //등록한 이용자의 id번호를 가져온다 
            $sql = "SELECT id FROM users WHERE email=:email";
            $stt = $pdo->prepare($sql);
            $stt->bindValue(':email',$user_email);
            $stt->execute();
            $id = $stt->fetch();
            
            $_SESSION['user_name']  = $user_name;
            $_SESSION['user_id']    = $id;
            
            print "<script>location.replace('../index.php')</script>";
        }else{
            echo "<script>alert('The email has already been taken.')</script>";
            print "<script>history.go(-1)</script>";
        }
    }

?>