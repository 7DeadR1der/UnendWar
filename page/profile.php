<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}/*
if(!isset($_SESSION['user'])){
    
    include("../includes/login/checktoken.php");
}*/


if(isset($_SESSION['user'])){
    //setcookie('login',$_SESSION['user']['login']);
    echo "<h5>".$_SESSION['user']['login']."</h5>
    <div>
        <p>".$_SESSION['user']['name']."</p>
        <p>".$_SESSION['user']['email']."</p>
        <p>Всего Игр - ".$_SESSION['user']['count_games']."</p>
        <p>Всего Побед - ".$_SESSION['user']['count_wins']."</p>
    </div>
    <div>
        <a href='settings.php'>Настройки</a>
    </div>
    <div>
        <button onclick='logout()'>Выйти</button>
    </div>" ;
}

?>