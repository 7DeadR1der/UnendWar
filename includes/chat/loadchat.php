<?php
require_once '../connect.php';
$first = true;
$chat_query = mysqli_query($connect,'SELECT * FROM `chat_general` ORDER BY `id_msg`');
$last_msg_query = mysqli_query($connect,'SELECT `id_msg` FROM `chat_general` ORDER BY `id_msg` DESC LIMIT 1');
$last_msg = mysqli_fetch_row($last_msg_query);
setcookie('lmgc', $last_msg[0]);
while($row = mysqli_fetch_array($chat_query)){
    echo "<div class='chat-message'><h5>".htmlspecialchars($row['login'])."</h5><p>".htmlspecialchars($row['text'])."</p><p>".date('H:i d.m.Y',$row['time'])."</p></div>";
}

?>