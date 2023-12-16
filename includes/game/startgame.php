<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    include_once("classes.php");
    include_once("mapmaker.php");
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect, "SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0 ){
        $room = mysqli_fetch_assoc($query);
        if($room['count_players']>1 || $room['local']==1){

            $game = [
                "gameTurn" => 1,
                "gameLand" => mt_rand(0,2),
                "local" => $room['local'],
                "gameSize" => [8,8],
                "gameVictoryCond" => [],
                "gamePlayers" => [],
                "gameField" => [],
                //"gameStatistic" => [],
                //"animation" => [],
                "gameLevelSkills" => [],
                "animation" => ['','','','','','']

            ];
            $game["gameLevelSkills"]["owner"] = 0;
            $game["gameLevelSkills"]["skills"] = [];


            if($room["classic_win_check"]==1){
                $game["gameVictoryCond"]["classicWin"] = true;
            }
            switch($room['game_type']){
                case"classic":
                    $game["gameVictoryCond"]["type"] = false;
                    $game["gameVictoryCond"]["condition"] = false;
                    break;
                case"score":
                    $game["gameVictoryCond"]["type"] = 'score';
                    $game["gameVictoryCond"]["condition"] = 45;
                    break;
                case"collect":
                    $game["gameVictoryCond"]["type"] = 'collect';
                    $game["gameVictoryCond"]["condition"] = 10;
                    break;
                default:
                    $game["gameVictoryCond"]["type"] = false;
                    $game["gameVictoryCond"]["condition"] = false;
                    break;
            }
            $game["gameVictoryCond"]["winner"] = false;
            $array = json_decode($room['players_id'],true);
            for($i=0;$i<8;$i++){
                for($j=0;$j<8;$j++){
                    $game['gameField'][$i][$j] = new Cell($i,$j);
                }
            };
            $neutral = new Player(' ',0,'Neutral');
            // $game['gameStatistic'][0]=[
            //     "name" => 'Neutral',
            //     "owner"=>0,
            //     "winner"=>0,
            //     "score"=>0,
            //     "goldUp"=>0,
            //     "goldDown"=>0,
            //     "unitUp"=>0,
            //     "unitDown"=>0,
            //     "buildUp"=>0,
            //     "buildDown"=>0,
            //     "expUp"=>0,
            //     "skills"=>[]
            // ];
            array_push($game['gamePlayers'], $neutral);
            if($room['local'] == 0){
                for ($k=1; $k<=$room['count_players']; $k++){
                    $id = $array[$k-1]["id"];
                    $getLoginUsers = mysqli_query($connect,"SELECT `login`, `count_games`,`win_table` FROM `users` WHERE `id_user` = '$id'");
                    $user = mysqli_fetch_assoc($getLoginUsers);
                    
                    // $game['gameStatistic'][$k]=[
                    //     "name" => $user[0],
                    //     "owner"=>$k,
                    //     "winner"=>0,
                    //     "score"=>0,
                    //     "goldUp"=>0,
                    //     "goldDown"=>0,
                    //     "unitUp"=>0,
                    //     "unitDown"=>0,
                    //     "buildUp"=>0,
                    //     "buildDown"=>0,
                    //     "expUp"=>0,
                    //     "skills"=>[]
                    // ];
                    if($array[$k-1]["faction"]=="Random"){
                        $num=mt_rand(1,4);//вместо 2 подставить количество фракций 
                        switch($num){
                            case 1:
                                $array[$k-1]["faction"]="Kingdom";
                                break;
                            case 2:
                                $array[$k-1]["faction"]="SeaMercs";
                                break;
                            case 3://потом сменить на 3
                                $array[$k-1]["faction"]="Undead";
                                break;
                            case 4:
                                $array[$k-1]["faction"]="Orcs";
                                break;
                            case 5:
                                $array[$k-1]["faction"]="Elves";
                                break;
                            default:
                                break;
                        }
                    } 
                    $table = json_decode($user['win_table'],true);
                    $factionName = $array[$k-1]["faction"];
                    if(!array_key_exists($factionName,$table)){
                        $table[$factionName] = ["games" => 0, "wins" => 0];
                    }
                    $table[$factionName]["games"] += 1;
                    $table = json_encode($table);
                    $count_games = $user['count_games']+1;
                    $updateUser = mysqli_query($connect, "UPDATE `users` SET `count_games` = '$count_games', `win_table` = '$table' WHERE `id_user` = '$id'");
                    $game['gamePlayers'][$k] = new Player($user['login'],$k,$array[$k-1]["faction"],$array[$k-1]["color"]);
                    //$game['gamePlayers'][$k]->faction->start($k);
                    $count_players = $room['count_players'];
                };
            }else{
                
                for($n=0;$n<6;$n++){
                    $k=1+$n;
                    $str = 'player'.$n;
                    if(!empty($_GET[$str])){
                        $faction = $_GET[$str];
                        //echo $_GET[$str];
                        if($faction=="Random"){
                            $num=mt_rand(1,4);
                            switch($num){
                                case 1:
                                    $faction="Kingdom";
                                    break;
                                case 2:
                                    $faction="SeaMercs";
                                    break;
                                case 3://потом сменить на 3
                                    $faction="Undead";
                                    break;
                                case 4:
                                    $faction="Orcs";
                                    break;
                                case 5:
                                    $faction="Elves";
                                    break;
                                default:
                                    break;
                            }
                        }
                        //echo $faction;
                        //echo $str;
                        $game['gamePlayers'][$k]= new Player($str,$k,$faction);
                    }
                }
                $count_players = count($game['gamePlayers'])-1;
            }
            $json = json_decode(json_encode($game));
            $gameMap = mapMaker($json->gameField,$room['game_map'],$count_players,$room['game_type'],$room['game_mode'],$json->gamePlayers);
            $ts = time();
            $arr = [];
            $arr["field"] = $json->gameField;
            $arr["stats"] = $json->gamePlayers;
            $array = [];
            array_push($array,$arr);
            $startJson = json_encode($array);
            $jsonData = json_encode($json);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 1, `game_json` = '$jsonData', `game_field_json` = '$startJson' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            echo response(1);
        }else{
            echo response(0,"You can't solo start");
        }
    }
    //echo $jsonData;
?>