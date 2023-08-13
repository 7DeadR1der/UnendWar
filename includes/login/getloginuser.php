<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    if(isset($_SESSION['user'])){
        echo $_SESSION['user']['login'];
    };
?>