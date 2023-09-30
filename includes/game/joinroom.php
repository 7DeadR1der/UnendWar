<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../general.php';
    $idRoom = $_POST['idRoom'];
    $idUser = $_SESSION['user']['id'];
    if(isset($_SESSION['user'])){
        $query = mysqli_query($connect, "SELECT `id_room`, `count_players`, `max_players`, `players_id`
        FROM `rooms` WHERE `id_room` = '$idRoom'");
        $room = mysqli_fetch_assoc($query);
        if($room["count_players"]<$room["max_players"]){
            $count = $room["count_players"]+1;
            $array = json_decode($room["players_id"],true);
            $colorArr = [];
            $colorNum = 0;
            foreach ($array as $key => $value) {
                array_push($colorArr,$value["color"]);
            }
            for($i=1;$i<7;$i++){
                if(!in_array($i,$colorArr)){
                    $colorNum = $i;
                    break;
                }
            }
            //if color not find -> code ...


            array_push($array,["id"=>$idUser, "name"=>$_SESSION['user']['login'],"faction"=>"Random","color"=>$colorNum]);
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