<?php
        session_start();
        require_once '../connect.php';
    $idRoom = $_POST['idRoom'];
    $idUser = $_SESSION['user']['id'];
    if(isset($_SESSION['user'])){
        $query = mysqli_query($connect, "SELECT `id_room`, `count_players`, `max_players`, `players_id`, `players_faction`
        FROM `rooms` WHERE `id_room` = '$idRoom'");
        $room = mysqli_fetch_row($query);
        if($room[1]<$room[2]){
            $count = $room[1]+1;
            $ids = $room[3].$idUser.'-';
            $factions = $room[4]."random"."-";
            $ts = time();
            mysqli_query($connect, "UPDATE `users` SET `active_room` = '$idRoom' WHERE `id_user` = '$idUser'");
            mysqli_query($connect, "UPDATE `rooms` 
            SET `count_players` = '$count', `players_id` = '$ids', `players_faction` = '$factions', `last_mod` = '$ts'
            WHERE `id_room` = '$idRoom'");
        }
    }


    include("../updatedatauser.php");
?>