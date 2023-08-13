<?php

    require_once '../general.php';
    $last_msg_query = mysqli_query($connect,'SELECT `id_msg` FROM `chat_general` ORDER BY `id_msg` DESC LIMIT 1');
    $last_msg = mysqli_fetch_row($last_msg_query);
    //setcookie('lmgc', $last_msg_query);
    if($_COOKIE['lmgc'] != $last_msg[0]){
        echo 'success';
    }

?>