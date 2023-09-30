<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        require_once '../general.php';
        
    setcookie("checkEndTurn",(int)$_POST['checkEndTurn'],time() + $lifetimecookie, '/');
    
    setcookie("enableAnimation",(int)$_POST['enableAnimation'],time() + $lifetimecookie, '/');

    //$_COOKIE['checkEndTurn'] = (int)$_POST['checkEndTurn'];
    //$_COOKIE['enableAnimation'] = (int)$_POST['enableAnimation'];

    echo 'success';

?>