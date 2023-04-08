<?php
    session_start();
    require_once '../connect.php';
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect, "SELECT `last_mod` FROM `rooms` WHERE `id_room` = '$idRoom'");
    $row = mysqli_fetch_row($query);
    if($_COOKIE['lm']<$row[0]){
        echo 'success';
    }else{
        echo 'nothing';
    }
?>