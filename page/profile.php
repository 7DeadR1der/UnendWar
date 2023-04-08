<?php
session_start();
if(isset($_SESSION['user'])){
    //setcookie('login',$_SESSION['user']['login']);
    echo "<h5>".$_SESSION['user']['login']."</h5>
    <div>
        <p>".$_SESSION['user']['name']."</p>
        <p>".$_SESSION['user']['email']."</p>
    </div>
    <div>
        <button onclick='logout()'>Выйти</button>
    </div>" ;
}
?>