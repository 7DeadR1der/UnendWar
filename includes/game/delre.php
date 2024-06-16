<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../general.php';

$delReplayFromRooms = mysqli_query($connect, "DELETE FROM `rooms` WHERE `game_state` = 2");
if($delReplayFromRooms){
    echo "it's okay!";
}else{
    echo "hmm, something working wrong... Error";
};