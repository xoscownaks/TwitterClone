<?php
session_start();
require_once "mydb.php";

    $user_id = $_SESSION['user_id'];
    $follow_target_id = $_POST['profile_id'];   
    $pdo = db_connect();
    $follow_bool = $_POST['follow_bool']; 

    if($follow_bool == 0){
        
        $sql = "INSERT INTO follow (user_id, follow_target_id) VALUES(:user_id, :follow_target_id)";
        $stt = $pdo->prepare($sql);
        $stt->bindValue(':user_id', $user_id);
        $stt->bindValue(':follow_target_id', $follow_target_id);
        $stt->execute();
        
        echo "<script>history.go(-1)</script>";
    }
    else if($follow_bool == 1){
        
        $sql = "DELETE FROM follow WHERE user_id=:user_id AND follow_target_id=:follow_target_id";
        $stt = $pdo->prepare($sql);
        $stt->bindValue(':user_id', $user_id);
        $stt->bindValue(':follow_target_id', $follow_target_id);
        $stt->execute();
        
        echo "<script>history.go(-1)</script>";
    }

?>