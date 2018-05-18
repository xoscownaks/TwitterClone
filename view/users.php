<?php  
session_start();
require_once "../php/mydb.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Microposts</title>
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="../index.php">Microposts</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php 
                                if(isset($_SESSION['user_name'])){
                                    echo "<li><a href='users.php'>"."Users"."</a></li>";
                                    echo "<li class='dropdown'>";
                                    echo    "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>".$_SESSION['user_name']."<span class='caret'></span></a>";
                                    echo    "<ul class='dropdown-menu'>";
                                    echo        "<li><a href='profile.php?id=".$_SESSION['user_id']."'>My profile</a></li>";
                                    echo        "<li role='separator' class='divider'></li>";
                                    echo        "<li><a href='logout.php'>Logout</a></li>";
                                    echo    "</ul>";
                                    echo "</li>";
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="container">
            <ul class="media-list">
                <?php
                
                    $pdo = db_connect();
                    $sql = "SELECT * FROM users";
                    $stt = $pdo->prepare($sql);
                    $stt->execute();
                    $row = $stt->fetchAll();
                    
                    foreach ($row as $key=>$value) {
                        echo '<li class="media">';
                            echo '<div class="media-left">';
                                echo '<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/e79e636c493e13e803ace5afcddb87a5?s=50&amp;r=g&amp;d=identicon" alt="">';
                            echo '</div>';
                            echo '<div class="media-body">';
                            echo '<div>';
                            echo $row[$key]['name'];
                            echo '</div';
                            echo '<div>';
                                echo "<p><a href='profile.php?id=".$row[$key]['id']."'>View profile</a></p>";
                            echo '</div>';
                    }
                    
                ?>
            </ul>
    </body>
</html>