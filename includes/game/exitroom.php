<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    if(isset($_SESSION['user']['active_room'])){
        $ts = time();
        $exitFromGame = false;
        $playerString = "";
        $factionString = "";
        $iduser = $_SESSION['user']['id'];
        $idroom = $_SESSION['user']['active_room'];
        $query = mysqli_query($connect, "SELECT `count_players`, `players_id`, `game_state`, `game_json` FROM `rooms` WHERE `id_room` = '$idroom'");
        $activeRoom = mysqli_fetch_assoc($query);
        $playerCount = $activeRoom["count_players"];
        $array = json_decode($activeRoom["players_id"],true);
        //$playerList = explode('-',$activeRoom["players_id"]);
        //$factionList = explode('-',$activeRoom["players_faction"]);
        if($activeRoom["game_state"] == 1){
            $num=0;
            $json = json_decode($activeRoom['game_json']);;
            for($k=1;$k<count($json->gamePlayers);$k++){
                if($json->gamePlayers[$k]->name == $_SESSION['user']['login']){
                    $json->gamePlayers[$k]->live = false;
                    $num=$k;
                    $exitFromGame = true;
                }
            }
            
        }
        foreach($array as $key => $value){
            if($_SESSION['user']['id'] == $value["id"]){
                array_splice($array,$key,1);
                $playerCount-=1;
            }
        }
        $ids = json_encode($array);
        mysqli_query($connect, "UPDATE `users` SET `active_room` = 0 WHERE `id_user` = '$iduser'");
        if($exitFromGame==true){
            $jsonData = json_encode($json);
            mysqli_query($connect, "UPDATE `rooms` SET `count_players` = '$playerCount', 
            `players_id` = '$ids', `game_json` = '$jsonData', `last_mod` = '$ts' WHERE `id_room` = '$idroom'");
        }else{
            mysqli_query($connect, "UPDATE `rooms` SET `count_players` = '$playerCount', 
            `players_id` = '$ids', `last_mod` = '$ts' WHERE `id_room` = '$idroom'");
        }
    }
    include("../update.php");


    /*for ($i=0;$i<count($playerList);$i++){
        if($_SESSION['user']['id'] == $playerList[$i]){
            array_splice($playerList, $i, 1);
            array_splice($factionList, $i, 1);
            $playerCount-=1;
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