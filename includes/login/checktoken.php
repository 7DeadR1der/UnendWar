<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
};
if(!isset($_SESSION['user'])){
    require_once '../connect.php';
    if(isset($_COOKIE["token"])){
        $token = $_COOKIE["token"];
        $query = mysqli_query($connect,"SELECT * FROM `session_tokens` WHERE `token` = '$token'");
        if(mysqli_num_rows($query)>0){
            $q = mysqli_fetch_assoc($query);
            $login = $q["login"];
            $check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login'");
            if(mysqli_num_rows($check_user)>0){
                $user = mysqli_fetch_assoc($check_user);
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
        }
    }
}
?>