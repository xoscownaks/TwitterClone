<?php
    session_start();
    session_destroy();
    
    print "<script>location.replace('../index.php')</script>";
?>