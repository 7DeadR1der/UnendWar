<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect, "SELECT `last_mod` FROM `rooms` WHERE `id_room` = '$idRoom'");
    $row = mysqli_fetch_row($query);
    if($_COOKIE['lm']<$row[0]){
        echo response(1);
    }else{
        echo response(2,'not change');
    }
?>