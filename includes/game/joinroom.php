<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../general.php';
    $idRoom = $_POST['idRoom'];
    $idUser = $_SESSION['user']['id'];
    if(isset($_SESSION['user'])){
        $query = mysqli_query($connect, "SELECT `id_room`, `count_players`, `max_players`, `players_id`, `players_faction`
        FROM `rooms` WHERE `id_room` = '$idRoom'");
        $room = mysqli_fetch_row($query);
        if($room[1]<$room[2]){
            $count = $room[1]+1;
            $array = json_decode($room[3],true);
            array_push($array,["id"=>$idUser, "name"=>$_SESSION['user']['login'],"faction"=>"Random"]);
            //$ids = $room[3].$idUser.'-';
            //$factions = $room[4]."random"."-";
            $ids = json_encode($array);
            $ts = time();
            mysqli_query($connect, "UPDATE `users` SET `active_room` = '$idRoom' WHERE `id_user` = '$idUser'");
            mysqli_query($connect, "UPDATE `rooms` 
            SET `count_players` = '$count', `players_id` = '$ids', `last_mod` = '$ts'
            WHERE `id_room` = '$idRoom'");
        }
    }


    include("../update.php");
?>