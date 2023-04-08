<?php
    session_start();
    require_once '../connect.php';
    if(isset($_SESSION['user'])){
        echo $_SESSION['user']['login'];
    };
?>