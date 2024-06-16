<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../general.php';
$id = $_GET["id"];
if($id != 0){
    $query = mysqli_query($connect,"SELECT `game_json`, `game_field_json` FROM `replays` WHERE `id_room`='$id' AND `game_state` = 2");
    if(mysqli_num_rows($query)>0){
        
        header('Content-type: application/json');
        $response = mysqli_fetch_assoc($query);
        $array = [];
        $array[0]=$response["game_field_json"];
        $array[1]=$response["game_json"];
        echo response(1,'',$array);
    }
}else{
    $query = mysqli_query($connect,"SELECT `id_room`, `name`, `game_map`, `local`, `date_create`, `date_end_game` FROM `replays` WHERE `game_state` = 2 ORDER BY `id_room` DESC");
    $array = [];
    header('Content-type: application/json');
    while($row = mysqli_fetch_assoc($query)){
        if($row['date_create'] != null){

        }
        $row['date_create'] = ($row['date_create'] == null) ? 'Неизвестно' : date('H:i d.m.Y',$row['date_create']);
        $row['date_end_game'] = ($row['date_end_game'] == null) ? 'Неизвестно' : date('H:i d.m.Y',$row['date_end_game']);
        array_push($array,$row);
        
    }
    echo response(2,'',$array);

}

?>