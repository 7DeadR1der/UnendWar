<?php
require_once 'connect.php';
$lifetimecookie = (90*24*60*60);
//local const cookie
//check all consts
if(!isset($_COOKIE["checkEndTurn"])){
    setcookie("checkEndTurn",1,time() + $lifetimecookie, '/');
}
if(!isset($_COOKIE["enableAnimation"])){
    setcookie("enableAnimation",0,time() + $lifetimecookie, '/');
}


date_default_timezone_set('Europe/Moscow');
//date_default_timezone_set('UTC');

?>