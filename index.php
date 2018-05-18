<?php  
session_start();
require_once "./php/mydb.php";
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
                        <a class="navbar-brand" href="./index.php">Microposts</a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php 
                                if(isset($_SESSION['user_id'])){
                                    echo "<li><a href='./view/users.php'>"."Users"."</a></li>";
                                    echo "<li class='dropdown'>";
                                    echo    "<a href='./view/users.php' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>".$_SESSION['user_name']."<span class='caret'></span></a>";
                                    echo    "<ul class='dropdown-menu'>";
                                    echo        "<li><a href='./view/profile.php?id=".$_SESSION['user_id']."'>My profile</a></li>";
                                    echo        "<li role='separator' class='divider'></li>";
                                    echo        "<li><a href='./php/logout.php'>Logout</a></li>";
                                    echo    "</ul>";
                                    echo "</li>";
                                }else{
                                    echo "<li><a href='./view/join.php'>"."Signup"."</a></li>";
                                    echo "<li><a href='./php/login.php'>Login</a></li>";
                                }
                                
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header> 
        <div class='container'>
        <?php
            if(!isset($_SESSION['user_name'])){
                echo    "<div class='center jumbotron'>";
                echo        "<div class='text-center'>";
                echo            "<h1>Welcome to the Microposts</h1>";
                echo            "<a href='./view/join.php' class='btn btn-lg btn-primary'>Sign up now!</a>";
                echo        "</div>";
                echo    "</div>";
            }else{
                
                
                echo '<form method="POST" action="./php/inputNewPost.php" accept-charset="UTF-8">';
                    echo '<div class="form-group">';
                        echo '<textarea class="form-control" rows="5" name="content" cols="50"></textarea>';
                    echo '</div>';
                    echo '<input class="btn btn-primary btn-block" type="submit" value="Post">';
                echo '</form>';
                
            }
        ?>
        
        <div class="col-xs-8">
            <ul class="media-list">
                <?php
                    //자신이 투고한 포스트를 확인 가능
                    //자신이 팔로우한 상대의 포스트를 확인 가능
                    //방법
                    //1.자신이 팔로우한 상대의 id를 전부 가져온다
                    //2.가져온 id전부와 자신의 id를 배열에 저장
                    //3.저장된 모든 id의 정보를 users테이블에서 가져옴
                    //4.테이블 조인을 통해 id의 정보를 바탕으로 posts테이블에서 가져옴
                    //5.가져온 모든 정보를 출력 
                    
                    $user_id = $_SESSION['user_id'];
                    $pdo = db_connect();
                    $sql = "SELECT follow_target_id FROM follow WHERE user_id=:user_id";
                    $stt = $pdo->prepare($sql);
                    $stt->bindValue(':user_id', $user_id);
                    $stt->execute();
                    $row = $stt->fetchAll();
                    $result_ids = [];
                    foreach($row as $key){
                        array_push($result_ids, $key[0]);
                    }
                    array_push($result_ids, $user_id);

                    foreach($result_ids as $key){
                        
                        $sql = "SELECT * FROM users WHERE id = :user_id";
                        $stt = $pdo->prepare($sql);
                        $stt->bindValue(':user_id', $key);
                        $stt->execute();
                        $name_row = $stt->fetch();
                        //var_dump($name_row);
                        
                        $sql = "SELECT * FROM posts WHERE user_id = :user_id";
                        $stt = $pdo->prepare($sql);
                        $stt->bindValue(':user_id', $key);
                        $stt->execute();
                        $post_row = $stt->fetchAll();
                        
                        //var_dump($post_row);
                        
                        foreach($post_row as $key){
                            echo '<li class="media">';
                            echo '<div class="media-left">';
                                echo '<img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/c5536218324153de5bf92e08c38bc5e5?s=50&amp;r=g&amp;d=identicon" alt="">';
                            echo '</div>';
                            echo '<div class="media-body">';
                                echo '<div>';
                                    echo '<a href="">'.$name_row['name'].'</a> <span class="text-muted">posted at '.$key['created_at'].'</span>';
                                echo '</div>';
                                echo '<div>';
                                    echo '<p>'.$key['post'] .'</p>';
                                    echo '<p>';
                                    //자신이 작성한 글인지 확인 
                                    if($user_id == $key['user_id']){
                                        echo '<div>';
                                            echo '<form method="POST" action="php/delete.php?post_id='.$key["post_id"].'" accept-charset="UTF-8">';
                                                echo '<input name="_method" type="hidden" value="DELETE">';
                                                echo '<input class="btn btn-danger btn-xs" type="submit" value="Delete">';
                                            echo '</form>';
                                        echo '</div>';
                                    }
                                    echo '</p>';
                                echo '</div>';
                            echo '</div>';
                        echo '</li>';
                        }
                        
                    }

                ?>
            </ul>
        </div>

                <!--<li class="media">
                    <div class="media-left">
                        <img class="media-object img-rounded" src="https://secure.gravatar.com/avatar/c5536218324153de5bf92e08c38bc5e5?s=50&amp;r=g&amp;d=identicon" alt="">
                    </div>
                    <div class="media-body">
                        <div>
                            <a href="http://laravel-microposts.herokuapp.com/users/3">k-s</a> <span class="text-muted">posted at 2017-01-10 18:50:03</span>
                        </div>
                        <div>
                            <p>テスト投稿</p>
                        </div>
                    </div>
                </li>-->
    </body>
</html>
