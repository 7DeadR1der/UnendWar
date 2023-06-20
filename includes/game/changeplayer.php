<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../connect.php';
    $idRoom = $_SESSION['user']['active_room'];
    $idUser = $_SESSION['user']['id'];
    //$playerString = "";
    //$factionString = "";
    $query = mysqli_query($connect, "SELECT `count_players`, `players_id` FROM `rooms` WHERE `id_room` = '$idRoom'");
    $row = mysqli_fetch_row($query);
    $count = $row[0];
    $array = json_decode($row[1],true);
    //$playerList = explode('-',$row[1]);
    //$factionList = explode('-',$row[2]);
    foreach($array as $key => $value){
        if($value["id"] == $idUser){
            $array[$key]["faction"]=$_POST['faction'];
        }
    }
    $ids = json_encode($array);
    $ts = time();
    mysqli_query($connect, "UPDATE `rooms` SET `players_id` = '$ids', 
     `last_mod` = '$ts' WHERE `id_room` = '$idRoom'");
    unset($playerList);
    unset($factionList);
    unset($playerString);
    unset($factionString);
    /*for($i=0;$i<$count;$i++){
        if($playerList[$i]==$idUser){
            $factionList[$i] = $_POST['faction'];
        }
    }
    foreach($playerList as $str){
        if($str != false){
            $playerString .= $str.'-';
        }
    };
    foreach ($factionList as $str){
        if($str != false){
            $factionString .= $str.'-';
        }
    }*/
?>