
<script scr="js/const.js"></script>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!$_SESSION['user']){
    header('Location: ', '');
    die();
}else{
    include("template/head.html");
    echo 
    '<a href="index.php">назад</a>
    <div>
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
            
        </div>
    </div>';
}
//<h4>Настройки игры</h4>
//<p>Coming soon</p>
?>
<script src="js/const.js"></script>
<script src="js/settings.js"></script>