<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT `game_json`, `last_mod`, `game_state`, `game_field_json` FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $newRound = false;
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
            $json->gameTurn++;
            while(!isset($json->gamePlayers[$json->gameTurn]) || $json->gamePlayers[$json->gameTurn]->live==false){
                if($json->gameTurn>=count($json->gamePlayers)){
                    $json->gameTurn=0;
                    //code for bot and etc
                    $newRound=true;
                }
                $json->gameTurn++;
            }
            $num=0;
            for($i=1;$i<count($json->gamePlayers);$i++){
                if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
                    $json->gamePlayers[$i]->counts = 0;
                    //check скилла казначейства на +1 голду
                    
                    if($newRound==true && in_array('Estates II',$json->gamePlayers[$i]->skills)){
                        setGold($i,'+',1);
                        //$json->gamePlayers[$i]->gold += 1;
                    }
                }
            }
            updateField();
            for($i=1;$i<count($json->gamePlayers);$i++){
                if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
                    if($json->gamePlayers[$i]->counts==0){
                        //player lose
                        $json->gamePlayers[$i]->live = false;
                        //unset($json->gamePlayers[$i]);
                    }else{
                        $num+=1;
                    }
                }
            }
            while($json->gamePlayers[$json->gameTurn]->live==false){
                if($json->gameTurn>=count($json->gamePlayers)){
                    $json->gameTurn=0;
                    //code for bot and etc
                    $newRound=true;
                }
                $json->gameTurn++;
                updateField();
            }
            $ts = time();
            //check Victory
            //win from domination
            if($json->gameVictoryCond->type==false || $json->gameVictoryCond->classicWin==true){
                if($num==1){
                    $owner=0;
                    for($i=1;$i<count($json->gamePlayers);$i++){
                        if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
                            $owner=$json->gamePlayers[$i]->owner;
                            echo $owner;
                        }
                    }
                    if($json->local == 0){
                        $login = $json->gamePlayers[$owner]->name;
                        echo $login;
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

                        $json->gameVictoryCond->winner = $login;
                        //$json->gameStatistic[$owner]["winner"] = 1;
                        $count_wins = $user['count_wins']+1;
                        echo $count_wins;
                        //$updateUsers = mysqli_query($connect, "UPDATE `users` SET `active_room` = 0 WHERE `active_room` = '$idRoom'");
                        $updateUser = mysqli_query($connect, "UPDATE `users` SET `count_wins` = '$count_wins', `win_table` = '$table' WHERE `login` = '$login'");
                        
                    }
                    $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 2, `last_mod` = '$ts',`date_end_game`='$ts'  WHERE `id_room` = '$idRoom'");


                }
            }
            
            $gameTurn = $json->gameTurn;
            $array =[];
            $array["field"] = $json->gameField;
            $array["stats"] = $json->gamePlayers;
            array_push($fields_json,$array);
            $fieldsData = json_encode($fields_json);
            $jsonData = json_encode($json);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData', `last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            $updateReplay = mysqli_query($connect, "UPDATE `rooms` SET `game_field_json` = '$fieldsData'  WHERE `id_room` = '$idRoom'");
            echo "success";
        }
    }

function updateField(){
    global $json;
    global $newRound;
    for($i=0;$i<count($json->gameField);$i++){
        for($j=0;$j<count($json->gameField[$i]);$j++){
            $cell = $json->gameField[$i][$j];
            //print_r($cell);
            if($cell->contains != false){
                if(isset($json->gamePlayers[$cell->contains->owner]) && $json->gamePlayers[$cell->contains->owner]->live!=false){
                    $json->gamePlayers[$cell->contains->owner]->counts++;
                    if($cell->contains->owner == $json->gameTurn){
                        if($cell->resCount && in_array('worker',$cell->contains->ability)){
                            $cell->resCount--;
                            setGold($cell->contains->owner,'+',1);
                        }
                        $cell->contains->canMove = true;
                        $cell->contains->canAction = true;
                        if(in_array('evasion',$cell->contains->ability)){
                            $search = array_search('evasion',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                            }
                        }
                    }
                };
                /*if($newRound==true && $cell->resCount && in_array('worker',$cell->contains->ability)){
                    $cell->resCount--;
                    $json->gamePlayers[$cell->contains->owner]->gold+=1;
                }*/
                if($newRound==true && $cell->contains->owner == 0){
                    if(in_array('regeneration',$cell->contains->ability) && $cell->contains->hp < $cell->contains->hpMax){
                        $cell->contains->hp +=1;
                    }
                    echo "1ok";
                    if($cell->contains->attack>0){
                        echo "2ok";
                        $arrUnits=[];
                        if(isset($json->gameField[$i+1][$j]) && $json->gameField[$i+1][$j]->contains != false && $json->gameField[$i+1][$j]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i+1][$j]);echo "0ok";}
                        if(isset($json->gameField[$i][$j+1]) && $json->gameField[$i][$j+1]->contains != false && $json->gameField[$i][$j+1]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i][$j+1]);echo "0ok";}
                        if(isset($json->gameField[$i-1][$j]) && $json->gameField[$i-1][$j]->contains != false && $json->gameField[$i-1][$j]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i-1][$j]);echo "0ok";}
                        if(isset($json->gameField[$i][$j-1]) && $json->gameField[$i][$j-1]->contains != false && $json->gameField[$i][$j-1]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i][$j-1]);echo "0ok";}
                        if(count($arrUnits)>0){echo "3ok";
                            $n = mt_rand(0,count($arrUnits)-1);{
                            
                            //$rndUnit = array_rand($arrUnits,1);
                            //var_dump($arrUnits[$n]);
                            //var_dump($rndUnit);
                            $k=$arrUnits[$n]->row;
                            $m=$arrUnits[$n]->column;
                            $atk = $cell->contains->attack;
                            if(in_array('evasion',$json->gameField[$k][$m]->contains->ability)){
                                $atk = 0;
                                $search = array_search('evasion',$json->gameField[$k][$m]->contains->ability);
                                if($search !== false){
                                    array_splice($json->gameField[$k][$m]->contains->ability,$search,1);
                                }
                            }
                            if($atk>0 && in_array('armor',$json->gameField[$k][$m]->contains->ability)){
                                $atk -= 1;
                                $search = array_search('armor',$json->gameField[$k][$m]->contains->ability);
                                if($search !== false){
                                    array_splice($json->gameField[$k][$m]->contains->ability,$search,1);
                                }
                            }
                            if($atk>0 && in_array('veteran',$json->gameField[$k][$m]->contains->ability)){
                                if($json->gameField[$k][$m]->contains->canAction == true){
                                    $atk -= 1;
                                    $json->gameField[$k][$m]->contains->canAction = false;
                                }
                                /*$chanceArray=[0,0,0,1,1,1,1,1,1,1];
                                $s= array_rand($chanceArray);
                                if($chanceArray[$s] == 0){
                                    $atk = 0;
                                }*/
                            }
                            }
                            if($json->gameField[$k][$m]->contains->hp-$atk<=0){
                                $json->gameField[$k][$m]->contains = false;echo "5ok";
                            }else{echo "6ok";
                                $json->gameField[$k][$m]->contains->hp -= $atk;
                            }
                            
                        }
                    }
                }
            }
        }
    }
}
?>