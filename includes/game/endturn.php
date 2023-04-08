<?php
    session_start();
    require_once '../connect.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $newRound = false;
        $id_player = 0;
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $jsonData = json_decode($game['game_json']);
        //print_r($jsonData);
        for ($i=1;$i<count($jsonData->gamePlayers);$i++){
            if($login == $jsonData->gamePlayers[$i]->name){
                $id_player = $i;
            }
        }
        if($jsonData->gameTurn == $id_player){
            $jsonData->gameTurn++;
            while(!isset($jsonData->gamePlayers[$jsonData->gameTurn]) || $jsonData->gamePlayers[$jsonData->gameTurn]==false){
                if($jsonData->gameTurn>=count($jsonData->gamePlayers)){
                    $jsonData->gameTurn=0;
                    //code for bot and etc
                    $newRound=true;
                }
                $jsonData->gameTurn++;
            }
            $num=0;
            for($i=1;$i<count($jsonData->gamePlayers);$i++){
                if(isset($jsonData->gamePlayers[$i]) && $jsonData->gamePlayers[$i]!=false){
                    $jsonData->gamePlayers[$i]->counts = 0;
                    $num+=1;
                    //check скилла казначейства на +1 голду
                    
                    if($newRound==true && in_array('Estates II',$jsonData->gamePlayers[$i]->skills)){
                        $jsonData->gamePlayers[$i]->gold += 1;
                    }
                }
            }
            for($i=0;$i<count($jsonData->gameField);$i++){
                for($j=0;$j<count($jsonData->gameField[$i]);$j++){
                    $cell = $jsonData->gameField[$i][$j];
                    print_r($cell);
                    if($cell->contains != false){
                        $jsonData->gamePlayers[$cell->contains->owner]->counts++;
                        $cell->contains->canMove=true;
                        $cell->contains->canAction=true;
                        if($newRound==true && $cell->resCount && in_array('worker',$cell->contains->ability)){
                            $cell->resCount--;
                            $jsonData->gamePlayers[$cell->contains->owner]->gold+=1;
                        }
                    }
                }
            }
            for($i=1;$i<count($jsonData->gamePlayers);$i++){
                if(isset($jsonData->gamePlayers[$i]) && $jsonData->gamePlayers[$i]->counts==0){
                    //player lose
                    $jsonData->gamePlayers[$i] = false;
                    //unset($jsonData->gamePlayers[$i]);
                }
            }
            $ts = time();
            //check Victory
            if($num<=1 && count($json->gamePlayers)>2){
                //WIN
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 2, `last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
                //$updateUser = mysqli_query($connect, "UPDATE `users` SET `count_wins` +=1");

            }
            $gameTurn = $jsonData->gameTurn;
            $jsonData = json_encode($jsonData);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            echo "success";
        }
    }
?>