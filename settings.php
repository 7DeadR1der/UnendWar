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
    $cookieCET = $_COOKIE["checkEndTurn"]==1?"checked":"";
    $cookieEA = $_COOKIE["enableAnimation"]==1?"checked":"";
    echo 
    '<div>
        <div>
            <h2>Settings</h2>
        </div>
        <div>
        <h4>Profile settings</h4>
            <div>
                <label>name</label>
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
                <label>Old password</label>
                <br>
                <input name="oldPass" type="text">
                <br>
                <label>New password</label>
                <br>
                <input name="newPass" type="text">
                <br>
                <label>Confirm new password</label>
                <br>
                <input name="confirmPass" type="text">
                <br>
            </div>
            <br>
            <button onclick="saveProfile()">Save profile</button>
            <br>
            
        </div>

        <div>
            <h4>Local settings</h4>
            <div>
            <label>Confirm end turn</label>
                <input name="checkEndTurn" type="checkbox" '.$cookieCET.'>
                <br>
            <label>Animation</label>
                <input name="enableAnimation" type="checkbox" '.$cookieEA.'>
                <br>
            <button onclick="saveLocalSettings()">Save local settings</button>
            <br>
            </div>
        </div>
    </div>';
}
/*

            <label>Language</label>
            <select>
                <option '.$_COOKIE["language"]=="en"?"selected":"".' value="en">English</option>
                <option '.$_COOKIE["language"]=="ru"?"selected":"".' value="ru">Russian</option>
            </select>
            <br>
*/
//<h4>Настройки игры</h4>
//<p>Coming soon</p>
//<button onclick="clearSession()">Завершить все сессии учетной записи</button>
?>
<script src="js/settings.js"></script>
</body>
</html>