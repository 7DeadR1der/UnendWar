<?php 
    session_start();
    require_once '../connect.php';
    $gameName = $_POST['gameName'];
    echo $gameName;
    $gameMode = $_POST['gameMode'];
    $gameType = $_POST['gameType'];
    $gameMap = $_POST['gameMap'];
    $gameCount = $_POST['gamePlayers'];
    $gameCreator = $_SESSION['user']['id'];
    $gamePlayers = $_SESSION['user']['id'].'-';
    $gameFactions = "random".'-';
    if($_SESSION['user']['active_room'] == 0){
        $ts = time();
        mysqli_query($connect, "INSERT INTO `rooms` 
        (`name`, `game_map`, `game_mode`, `game_type`, `id_creator`, `count_players`, `max_players`, `players_id`, `players_faction`, `last_mod`)
        VALUES
        ('$gameName','$gameMap','$gameMode','$gameType','$gameCreator',1,'$gameCount','$gamePlayers','$gameFactions', '$ts')");
        $query = mysqli_query($connect, "SELECT `id_room` FROM `rooms` WHERE (`id_creator` = '$gameCreator') ORDER BY `id_room` DESC LIMIT 1");
        $idroom = mysqli_fetch_row($query);
        mysqli_query($connect, "UPDATE `users` SET `active_room` = '$idroom[0]' WHERE `id_user` = '$gameCreator'");
        echo 'success';
    }
    include("../updatedatauser.php");
?>