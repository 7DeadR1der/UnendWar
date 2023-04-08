<?php
    session_start();
    require_once '../connect.php';
    include_once("classes.php");
    include_once("mapmaker.php");
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect, "SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
    $room = mysqli_fetch_row($query);
    $game = [
        "gameTurn" => 1,
        "gamePlayers" => [],
        "gameField" => []
    ];
    $playerList = explode('-',$room[8]);
    $factionList = explode('-',$room[10]);
    for($i=0;$i<8;$i++){
        for($j=0;$j<8;$j++){
            $game['gameField'][$i][$j] = new Cell($i,$j);
        }
    };
    $neutral = new Player(' ',0,'neutral');
    array_push($game['gamePlayers'], $neutral);
    for ($k=1; $k<=$room[6]; $k++){
        $id = $playerList[$k-1];
        $getLoginUsers = mysqli_query($connect,"SELECT `login` FROM `users` WHERE `id_user` = '$id'");
        $login = mysqli_fetch_row($getLoginUsers);
        $game['gamePlayers'][$k] = new Player($login[0],$k,$factionList[$k-1]);
        //$game['gamePlayers'][$k]->faction->start($k);
    };
    $gameMap = mapMaker($game["gameField"],$room[2],$room[6],$room[4],$room[3],$game['gamePlayers']);
    $ts = time();
    $jsonData = json_encode($game);
    $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_state` = 1, `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
    echo "success";
    //echo $jsonData;
?>