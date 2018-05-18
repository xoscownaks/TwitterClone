<?php
session_start();
require_once "mydb.php";

    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    
    $pdo = db_connect();
    $sql = "INSERT INTO posts(user_id, post) VALUES(:user_id, :post)";
    $stt = $pdo->prepare($sql);
    $stt->bindValue(':user_id', $user_id);
    $stt->bindValue(':post', $content);
    $stt->execute();
    
    print "<script>location.replace('../index.php')</script>";
?>