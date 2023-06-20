<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../connect.php';
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
                "gameVictoryCond" => [],
                "gamePlayers" => [],
                "gameField" => [],
                //"gameStatistic" => []
            ];
            if($room["classic_win_check"]==1){
                $game["gameVictoryCond"]["classicWin"] = true;
            }
            switch($room['game_type']){
                case"classic":
                    $game["gameVictoryCond"]["type"] = false;
                    $game["gameVictoryCond"]["condition"] = false;
                    break;
                case"hunt":
                    $game["gameVictoryCond"]["type"] = 'hunt';
                    $game["gameVictoryCond"]["condition"] = false;
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
            $neutral = new Player(' ',0,'neutral');
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
                    $getLoginUsers = mysqli_query($connect,"SELECT `login`, `count_games` FROM `users` WHERE `id_user` = '$id'");
                    $user = mysqli_fetch_row($getLoginUsers);
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
                    if($array[$k-1]["faction"]=="random"){
                        $num=mt_rand(1,4);//вместо 2 подставить количество фракций 
                        switch($num){
                            case 1:
                                $array[$k-1]["faction"]="kingdom";
                                break;
                            case 2:
                                $array[$k-1]["faction"]="seamercs";
                                break;
                            case 3://потом сменить на 3
                                $array[$k-1]["faction"]="undead";
                                break;
                            case 4:
                                $array[$k-1]["faction"]="orcs";
                                break;
                            case 5:
                                $array[$k-1]["faction"]="elves";
                                break;
                            default:
                                break;
                        }
                    }
                    $count_games = $user[1]+1;
                    $updateUser = mysqli_query($connect, "UPDATE `users` SET `count_games` = '$count_games' WHERE `id_user` = '$id'");
                    $game['gamePlayers'][$k] = new Player($user[0],$k,$array[$k-1]["faction"]);
                    //$game['gamePlayers'][$k]->faction->start($k);
                    $count_players = $room['count_players'];
                };
            }else{
                
                for($n=0;$n<6;$n++){
                    $k=1+$n;
                    $str = 'player'.$n;
                    if(!empty($_GET[$str])){
                        $faction = $_GET[$str];
                        echo $_GET[$str];
                        if($faction=="random"){
                            $num=mt_rand(1,3);
                            switch($num){
                                case 1:
                                    $faction="kingdom";
                                    break;
                                case 2:
                                    $faction="seamercs";
                                    break;
                                case 3://потом сменить на 3
                                    $faction="undead";
                                    break;
                                case 4:
                                    $faction="orcs";
                                    break;
                                case 5:
                                    $faction="elves";
                                    break;
                                default:
                                    break;
                            }
                        }
                        echo $faction;
                        echo $str;
                        $game['gamePlayers'][$k]= new Player($str,$k,$faction);
                    }
                }
                $count_players = count($game['gamePlayers'])-1;
            }
            $gameMap = mapMaker($game["gameField"],$room['game_map'],$count_players,$room['game_type'],$room['game_mode'],$game['gamePlayers']);
            $ts = time();
            $arr = [];
            $arr["field"] = $game['gameField'];
            $arr["stats"] = $game['gamePlayers'];
            $array = [];
            array_push($array,$arr);
            $startJson = json_encode($array);
            $jsonData = json_encode($game);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 1, `game_json` = '$jsonData', `game_field_json` = '$startJson' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            echo "success";
        }else{
            echo "Нельзя начать в одиночку";
        }
    }
    //echo $jsonData;
?>