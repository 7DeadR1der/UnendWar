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
        <details>
        <summary>Статистика по фракциям</summary>
            ";//<ul class='profile-win-table'>
        $winTable = $_SESSION['user']['win_table'];
        if(array_key_exists('Kingdom',$winTable)){
            echo "<p>Kingdom - ".$winTable['Kingdom']['wins']."/".$winTable['Kingdom']['games']."</p>";
        }
        if(array_key_exists('SeaMercs',$winTable)){
            echo "<p>SeaMercs - ".$winTable['SeaMercs']['wins']."/".$winTable['SeaMercs']['games']."</p>";
        }
        if(array_key_exists('Undead',$winTable)){
            echo "<p>Undead - ".$winTable['Undead']['wins']."/".$winTable['Undead']['games']."</p>";
        }
        if(array_key_exists('Orcs',$winTable)){
            echo "<p>Orcs - ".$winTable['Orcs']['wins']."/".$winTable['Orcs']['games']."</p>";
        }
        if(array_key_exists('Elves',$winTable)){
            echo "<p>Elves - ".$winTable['Elves']['wins']."/".$winTable['Elves']['games']."</p>";
        }
        echo /*</ul>*/"
        </details>
    </div>
    <div>
        <a href='settings.php'>Настройки</a>
    </div>
    <div>
        <button onclick='logout()'>Выйти</button>
    </div>" ;
}

?>