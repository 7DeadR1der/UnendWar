<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../connect.php';
    if($_SESSION['user']){
        $id = $_SESSION['user']['id'];
        if($_POST['oldPass'] != 0){
            if(strlen($_POST['newPass'])>7){
                if($_POST['newPass'] == $_POST['confirmPass']){
                    $check_user = mysqli_query($connect, "SELECT `password` FROM `users` WHERE `id_user` = '$id'");
                    $array = mysqli_fetch_assoc($check_user);
                    $pass = md5($_POST['oldPass']);
                    if($pass == $array['password']){
                        $newPass = md5($_POST['newPass']);
                        mysqli_query($connect, "UPDATE `users` SET `password` = '$newPass' WHERE `id_user` = '$id'");
                        
                    }else{
                        echo 'Старый пароль неверный';
                        die();
                    }
                }else{
                    echo 'Пароли не совпадают';
                    die();
                }
            }else{
                echo 'Слишком короткий пароль';
                die();
            }
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        mysqli_query($connect, "UPDATE `users` SET `name` = '$name', `email` = '$email' WHERE `id_user` = '$id'");
        echo 'success';
        include '../update.php';
    }else{
        die();
    }
?>