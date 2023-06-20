<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../connect.php';
$id = $_GET["id"];
$query = mysqli_query($connect,"SELECT `game_json`, `game_field_json` FROM `rooms` WHERE `id_room`='$id' AND `game_state` = 2");
if(mysqli_num_rows($query)>0){
    
    header('Content-type: application/json');
    $response = mysqli_fetch_assoc($query);
    $array = [];
    $array[0]=$response["game_field_json"];
    $array[1]=$response["game_json"];
    echo json_encode($array);
}

?>