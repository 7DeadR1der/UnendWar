<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../connect.php';
    $gameName = $_POST['gameName'];
    echo $gameName;
    $gameMode = $_POST['gameMode'];
    switch($_POST['gameMap'][1]){
        case'0':
            $gameType= "classic";
            break;
        case'1':
            $gameType= "hunt";
            break;
        case'2':
            $gameType= "collect";
            break;
        default:
            $gameType= "classic";
            break;
    };
    //$gameType = $_POST['gameType'];
    $cwc = $_POST["cwc"]; //Classic Win Check
    $gameMap = $_POST['gameMap'];
    $gameCount = $_POST['gamePlayers'];
    $gameCreator = $_SESSION['user']['id'];
    $array[0] = ["id"=>$gameCreator, "name" => $_SESSION['user']['login'], "faction" => "random"];
    $gamePlayers = json_encode($array);
    //$gamePlayers = $_SESSION['user']['id'].'-';
    //$gameFactions = "random".'-';
    $gameLocal = $_POST['gameLocal'];
    if($_SESSION['user']['active_room'] == 0){
        $ts = time();
        mysqli_query($connect, "INSERT INTO `rooms` 
        (`name`, `game_map`, `game_mode`, `game_type`, `classic_win_check`, `id_creator`, `count_players`, `max_players`, `players_id`, `local`, `last_mod`)
        VALUES
        ('$gameName','$gameMap','$gameMode','$gameType', '$cwc','$gameCreator',1,'$gameCount','$gamePlayers','$gameLocal' , '$ts')");
        $query = mysqli_query($connect, "SELECT `id_room` FROM `rooms` WHERE (`id_creator` = '$gameCreator') ORDER BY `id_room` DESC LIMIT 1");
        $idroom = mysqli_fetch_row($query);
        mysqli_query($connect, "UPDATE `users` SET `active_room` = '$idroom[0]' WHERE `id_user` = '$gameCreator'");
        echo ' success';
    }
    include("../update.php");
?>