<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../general.php';
    if(isset($_POST['login']) && isset($_POST['pass']) && isset($_POST['passConfirm']) && $_POST['pass']===$_POST['passConfirm']){
        $login = $_POST['login'];
        $password = $_POST['pass'];
        $password_confirm = $_POST['passConfirm'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        if(preg_match("/^[a-z0-9_]+$/i",$login)){
            if(strlen($login)>3 && strlen($login)<21 && strlen($password)>7){
                $check_login = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login'");
                if(mysqli_num_rows($check_login)===0){
                    $password = md5($password);
                    mysqli_query($connect, "INSERT INTO `users` (`login`, `password`, `name`, `email`, `win_table`) VALUES ('$login', '$password', '$name', '$email', '{}')");
                    echo "success";
                }else{
                    echo "Такой Логин уже существует.";
                }
            }else{
                echo "Длина логина должна быть от 4 до 20 символов, длина пароля минимум 8 символов.";
            }
        }else{
            echo "Логин должен состоять только из латинских букв и цифр";
        }
    }
    else{
        echo 'Ошибка в заполнении логина или пароля';
    }

?>