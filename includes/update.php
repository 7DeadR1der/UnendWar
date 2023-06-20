<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../connect.php';
    $id = $_SESSION['user']['id'];
    $login = $_SESSION['user']['login'];
    $query = mysqli_query($connect, "SELECT * FROM `users` WHERE `id_user` = '$id' AND `login` = '$login'");
    if(mysqli_num_rows($query)>0){
        $user = mysqli_fetch_assoc($query);
        $_SESSION['user']=[
            "id"=>$user['id_user'],
            "login"=>$user['login'],
            "name"=>$user['name'],
            "email"=>$user['email'],
            "active_room"=>$user['active_room'],
            "count_wins"=>$user['count_wins'],
            "count_games"=>$user['count_games']
        ];
    }
?>