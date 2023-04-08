<?php 
    session_start();
    require_once '../connect.php';
    if(isset($_SESSION['user']['active_room'])){
        $ts = time();
        $playerString = "";
        $factionString = "";
        $iduser = $_SESSION['user']['id'];
        $idroom = $_SESSION['user']['active_room'];
        $query = mysqli_query($connect, "SELECT * FROM `rooms` WHERE `id_room` = '$idroom'");
        $activeRoom = mysqli_fetch_assoc($query);
        $playerCount = $activeRoom["count_players"];
        $playerList = explode('-',$activeRoom["players_id"]);
        $factionList = explode('-',$activeRoom["players_faction"]);
        if($activeRoom["game_state"] == 1){
            $num=0;
            $json= $activeRoom["game_state"];
            for($k=1;$k<count($json->gamePlayers);$k++){
                if($json->gamePlayers[$k]->name == $_SESSION['user']['login']){
                    $json->gamePlayers[$k] = false;
                    $num=$k;
                }
            }

            /*
            for($i=0;$i<8;$i++){
                for($j=0;$j<8;$j++){
                    if($json->gameField[$i][$j]->contains!=false){
                        //$json->gameField[$i][$j]->contains->owner == 0;
                        $json->gameField[$i][$j]->contains == false;
                    }
                }
            }*/

            
        }
        for ($i=0;$i<count($playerList);$i++){
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
        }
        mysqli_query($connect, "UPDATE `users` SET `active_room` = 0 WHERE `id_user` = '$iduser'");
        mysqli_query($connect, "UPDATE `rooms` SET `count_players` = '$playerCount', 
        `players_id` = '$playerString', `players_faction` = '$factionString', `last_mod` = '$ts' WHERE `id_room` = '$idroom'");
    }
    include("../updatedatauser.php");

?>