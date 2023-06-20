<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../connect.php';

if(isset($_POST['msg']) && isset($_SESSION['user']['login'])){
    $login = $_SESSION['user']['login'];
    $msg = $_POST['msg'];
    //$text = 'test message';
    $ts = time();
$query = mysqli_query($connect, "INSERT INTO `chat_general` (`login`, `text`, `time`) VALUES ('$login', '$msg', '$ts')");
echo 'success';
}

$chat_query = mysqli_query($connect,'SELECT * FROM `chat_general` ORDER BY `id_msg` DESC');
if(mysqli_num_rows($chat_query)>50){
    $last_msg_query = mysqli_query($connect,'SELECT `id_msg` FROM `chat_general` ORDER BY `id_msg` DESC LIMIT 49, 1');
    $last_msg = mysqli_fetch_row($last_msg_query);
    $l = $last_msg[0];
    $del_query = mysqli_query($connect, "DELETE FROM `chat_general` WHERE `id_msg` < '$l'");
}
?>