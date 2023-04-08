<?php
        session_start();
        require_once '../connect.php';
    if(isset($_POST['login']) && isset($_POST['password'])){
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        $check_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");
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
                //setcookie('login','asdasd',0,'/');
                echo "success";
            }else{
                echo "Ошибка в логине или пароле.";
            }
        
    }
    else{
        echo 'Ошибка в заполнении логина или пароля';
    }

?>