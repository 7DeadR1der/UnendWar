<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    $ifEnd=false;
    require_once '../general.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT `game_json`, `last_mod`, `game_state`, `game_field_json` FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $id_player = 0;
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
        $fields_json = json_decode($game['game_field_json']);
        include_once("classes.php");
        //print_r($json);
        for ($i=1;$i<count($json->gamePlayers);$i++){
            if($json->gamePlayers[$i]->live!=false && ($login == $json->gamePlayers[$i]->name || $json->local == 1)){
                $id_player = $i;
            }
        }
        if(($json->gameTurn == $id_player && $game['game_state']==1) || $json->local == 1){
            if(!empty($_GET['sur']) && $_GET['sur']==1){
                //surrender
                $json->gamePlayers[$json->gameTurn]->live = false;
            }
            $result = endTurn();
            $ts = time();
            //check Victory
            //win from domination
            if($result["win"]["type"] != 0){
                $owner = $result["win"]["players"][0];
                $login = $json->gamePlayers[$owner]->name;
                $json->gameVictoryCond->winner = $login;
                $json->gamePlayers[$owner]->statistic->winner = 1;

                if($json->local == 0){
                    //because now exist only one type for win

                    //$login = $json->gamePlayers[$owner]->name;
                    //echo $login;
                    $userQuery = mysqli_query($connect,"SELECT `login`, `count_wins`, `win_table` FROM `users` WHERE `login` = '$login'");
                    $user = mysqli_fetch_assoc($userQuery);
                    $table = json_decode($user['win_table'],true);
                    $factionName = $json->gamePlayers[$owner]->faction->name;
                    if(!array_key_exists($factionName,$table)){
                        $table[$factionName] = ["games" => 0, "wins" => 0];
                    }
                    $table[$factionName]["wins"] += 1;
                    $table = json_encode($table);
                    /*if($user['win_table'] === null){
                        $user['win_table'] = [
                            "Kingdom" => ["games" => 0, "wins" => 0],
                            "SeaMercs" => ["games" => 0, "wins" => 0],
                            "Undead" => ["games" => 0, "wins" => 0],
                            "Orcs" => ["games" => 0, "wins" => 0],
                            "Elves" => ["games" => 0, "wins" => 0],
                        ];
                    }*/

                    //$json->gameStatistic[$owner]["winner"] = 1;
                    $count_wins = $user['count_wins']+1;
                    //echo $count_wins;
                    //$updateUsers = mysqli_query($connect, "UPDATE `users` SET `active_room` = 0 WHERE `active_room` = '$idRoom'");
                    $updateUser = mysqli_query($connect, "UPDATE `users` SET `count_wins` = '$count_wins', `win_table` = '$table' WHERE `login` = '$login'");
                    
                }
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 2, `last_mod` = '$ts',`date_end_game`='$ts'  WHERE `id_room` = '$idRoom'");
                $ifEnd=true;



                
            }
            
            $gameTurn = $json->gameTurn;
            $fieldsData = json_encode($fields_json);
            $jsonData = json_encode($json);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData', `last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            $updateReplay = mysqli_query($connect, "UPDATE `rooms` SET `game_field_json` = '$fieldsData'  WHERE `id_room` = '$idRoom'");
            if($ifEnd==true){
                $copyReplay = mysqli_query($connect, "INSERT INTO `replays` SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
            }
            echo response(1);
        }
    }

?>