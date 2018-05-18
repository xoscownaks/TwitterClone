<?php
require_once "mydb.php";

    $post_id = $_GET['post_id'];
    
    $pdo = db_connect();
    $sql = "DELETE FROM posts WHERE post_id=:post_id";
    $stt = $pdo->prepare($sql);
    $stt->bindValue(':post_id',$post_id);
    $stt->execute();
    
    echo "<script>history.go(-1)</script>";

?>