<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../general.php';
if(isset($_COOKIE['token'])){
    $login = $_SESSION['user']['login'];
    $token = $_COOKIE['token'];
    $query = mysqli_query($connect,"DELETE FROM `session_tokens` WHERE `login` = '$login' AND `token` = '$token'");
}
    unset($_SESSION['user']);
    setcookie("token",'',time()-3600,'/');
?>