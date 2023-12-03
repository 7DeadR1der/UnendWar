<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $player;
    $query = mysqli_query($connect,"SELECT `game_json`,`last_mod` FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
            for ($i=1;$i<count($json->gamePlayers);$i++){
                if($login == $json->gamePlayers[$i]->name){
                    $player = $i;
                }
            }
        if($json->local == 1 || $json->gameTurn == $player){
            $player = $json->gameTurn;
            include_once("classes.php");
            if(action((int)$_GET['fi'], (int)$_GET['fj'], $_GET['btn'], (int)$_GET['si'], (int)$_GET['sj'], $_GET['param'])){
                $ts = time();
                $jsonData = json_encode($json);
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
                echo response(1,'success');
            }
        }

    }


    //$unit = $json->gamePlayers[$owner]->faction->t2;
    //$json->gameField[i][j]->contains = spawn($unit,$owner,true,false,true);
    
?>