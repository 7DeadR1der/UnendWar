<?php
//session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/general.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        include("template/head.html");
    ?>
    <script src="js/const.js"></script>
</head>
<body>
<?php
    echo '<a href="index.php">назад</a>';
    //var_dump($_SESSION);
if(!isset($_SESSION['user'])){
    echo "dieeee";
    //header('Location: ', 'index.php?page=authors');
    die();
}else{
    echo 
    '<div>
        <div>
            <h2>Settings</h2>
        </div>
        <div>
        <h4>Настройки профиля</h4>
            <div>
                <label>Имя</label>
                <br>
                <input name="name" type="text" value="'.$_SESSION['user']['name'].'">
            </div>
            <div>
                <label>Email</label>
                <br>
                <input name="email" type="text" value="'.$_SESSION['user']['email'].'">
            </div>
            <div>
            <br>
                <label>Старый Пароль</label>
                <br>
                <input name="oldPass" type="text">
                <br>
                <label>Новый Пароль</label>
                <br>
                <input name="newPass" type="text">
                <br>
                <label>Подтвердите Пароль</label>
                <br>
                <input name="confirmPass" type="text">
                <br>
            </div>
            <br>
            <button onclick="saveProfile()">Сохранить профиль</button>
            <br>
            
        </div>
    </div>';
}
//<h4>Настройки игры</h4>
//<p>Coming soon</p>
//<button onclick="clearSession()">Завершить все сессии учетной записи</button>
?>
<script src="js/settings.js"></script>
</body>
</html>