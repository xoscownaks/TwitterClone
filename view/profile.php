<?php  
session_start();
require_once "../php/mydb.php";
$pdo = db_connect();

    $_SESSION['follow_target_id'] = $_GET['id'];
    
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
        
            <script>
                $(document).ready(function(){
                    $(".nav-tabs a").click(function(){
                        $(this).tab('show');
                    });
                });
            </script>
            <script type="text/javascript">
            </script>
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
                                    echo        "<li><a href='../php/logout.php'>Logout</a></li>";
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
            <div class="row">
                <aside class="col-xs-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <?php
                                    $sql = "SELECT * FROM users WHERE id=:follow_target_id";
                                    $stt = $pdo->prepare($sql);
                                    $stt->bindValue(':follow_target_id', $_SESSION['follow_target_id']);
                                    $stt->execute();
                                    $row = $stt->fetchAll();
                                    echo $row[0]['name'];
                                    
                                ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <img class="media-object img-rounded img-responsive" src="https://secure.gravatar.com/avatar/e79e636c493e13e803ace5afcddb87a5?s=500&amp;r=g&amp;d=identicon" alt="">
                        </div>
                            <?php 
                   
                                if($_SESSION['follow_target_id'] !== $_SESSION['user_id']){
                                    
                                    $sql = "SELECT * FROM follow WHERE user_id = :user_id AND follow_target_id = :follow_target_id";
                                    $stt = $pdo->prepare($sql);
                                    $stt->bindValue(':user_id',$_SESSION['user_id']);
                                    $stt->bindValue(':follow_target_id', $_SESSION['follow_target_id']);
                                    $stt->execute();
                                    $result = $stt->rowCount();
                                    // echo $profile_id;
                                    // echo $_SESSION['user_id'];
                                    // echo $result;
                                    
                                    if($result){
                                        echo '<form method="POST" action="../php/follow.php" accept-charset="UTF-8">';
                                            echo '<input type="hidden" name="profile_id" value="'. $_SESSION['follow_target_id'].'"/>';
                                            echo '<input type="hidden" name="follow_bool" value=1"/>';
                                            echo '<input class="btn btn-danger btn-block" type="submit" value="Unfollow" name="Unfollow">';
                                        echo '</form>';
                                        
                                    }else{
                                        echo '<form method="POST" action="../php/follow.php" accept-charset="UTF-8">';
                                            echo '<input type="hidden" name="profile_id" value="'. $_SESSION['follow_target_id'].'"/>';
                                            echo '<input type="hidden" name="follow_bool" value=0"/>';
                                            echo '<input class="btn btn-primary btn-block" type="submit" value="follow" name="follow">';
                                        echo '</form>';
                                    }
                                }
                            ?>
                      </div>
                </aside>
                <div class="col-xs-8">
                    <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#Microposts">Microposts<?php echo $resultMicroposts; ?></a></li>
                            <li><a href="#Followings">Followings</a></li>
                            <li><a href="#Followers">Followers</a></li>
                    </ul>
                    <div class="tab-content">
                        
                        <div id="Microposts" class="tab-pane fade in active">
                             <ul class="media-list">
                                <?php
                                    
                                    $sql = "SELECT * FROM posts WHERE user_id=:user_id";
                                    $stt = $pdo->prepare($sql);
                                    $stt->bindValue(':user_id',$_SESSION['follow_target_id']);
                                    $stt->execute();    
                                    $row = $stt->fetchAll();
                                    $resultMicroposts = $stt->rowCount();
                                    
                                    $sql = "SELECT name FROM posts WHERE id=:id";
                                    $stt = $pdo->prepare($sql);
                                    $stt->bindValue(':id',$_SESSION['follow_target_id']);
                                    $stt->execute();
                                    $name = $stt->fetch();

                                    foreach ($row as $value) {
                                        echo '<li class="media">';
                                            echo '<div class="media-left">';
                                                echo '<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/c5536218324153de5bf92e08c38bc5e5?s=50&amp;r=g&amp;d=identicon " alt="">';
                                            echo '</div>';
                                            echo '<div class="media-body">';
                                                echo '<div>';
                                                    echo '<a href="">'.$name.'</a> <span class="text-muted">posted at'.$value['created_at'].'</span>';
                                                echo '</div>';
                                                echo '<div>';
                                                    echo '<p>'.  $value['post'] .'</p>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</li>';
                                    }
                                ?>
                            </ul>
                        </div>
                        
                        <div id="Followings" class="tab-pane fade">
                            <ul class="media-list">
                                <?php
                                
                                    $pdo = db_connect();
                                    $sql = "SELECT follow_target_id FROM follow WHERE user_id=:user_id";
                                    $stt = $pdo->prepare($sql);
                                    $stt->bindValue(':user_id', $_SESSION['follow_target_id']);
                                    $stt->execute();
                                    $row = $stt->fetchAll();

                                    $followings = [];
                                    foreach ($row as $value) {
                                        array_push($followings,$value['follow_target_id']);
                                    }
                                    //var_dump($followings);
                                    
                                    foreach ($followings as $value) {
                                        $sql = "SELECT * FROM users WHERE id=:id";
                                        $stt = $pdo->prepare($sql);
                                        $stt->bindValue(':id', $value);
                                        $stt->execute();
                                        $row = $stt->fetch();
                                        
                                        echo '<li class="media">';
                                            echo '<div class="media-left">';
                                                echo '<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/e79e636c493e13e803ace5afcddb87a5?s=50&amp;r=g&amp;d=identicon" alt="">';
                                            echo '</div>';
                                            echo '<div class="media-body">';
                                            echo '<div>';
                                            echo $row['name'];
                                            echo '</div';
                                            echo '<div>';
                                                echo "<p><a href='profile.php?id=".$row['id']."'>View profile</a></p>";
                                            echo '</div>';
                                        
                                    }
                                ?>
                            </ul>
                        </div>
                        <div id="Followers" class="tab-pane fade">
                            <ul class="media-list">
                            <?php
                                $pdo = db_connect();
                                $sql = "SELECT user_id FROM follow WHERE follow_target_id=:follow_target_id";
                                $stt = $pdo->prepare($sql);
                                $stt->bindValue(':follow_target_id', $_SESSION['follow_target_id']);
                                $stt->execute();
                                $row = $stt->fetchAll();
                            
                                $followings = [];
                                foreach ($row as $value) {
                                    array_push($followings, $value['user_id']);
                                }

                                foreach ($followings as $value) {
                                        $sql = "SELECT * FROM users WHERE id=:id";
                                        $stt = $pdo->prepare($sql);
                                        $stt->bindValue(':id', $value);
                                        $stt->execute();
                                        $row = $stt->fetch();
                                        
                                        echo '<li class="media">';
                                            echo '<div class="media-left">';
                                                echo '<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/e79e636c493e13e803ace5afcddb87a5?s=50&amp;r=g&amp;d=identicon" alt="">';
                                            echo '</div>';
                                            echo '<div class="media-body">';
                                            echo '<div>';
                                            echo $row['name'];
                                            echo '</div';
                                            echo '<div>';
                                                echo "<p><a href='profile.php?id=".$row['id']."'>View profile</a></p>";
                                            echo '</div>';
                                        
                                    }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

