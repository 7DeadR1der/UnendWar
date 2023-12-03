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
if(!isset($_COOKIE["language"])){
    setcookie("language","en",time() + $lifetimecookie, '/');
}


date_default_timezone_set('Europe/Moscow');
//date_default_timezone_set('UTC');
function response($status, $msg='', $data=false){
    //status 0 - error 1 - success 2 - anything
    //
    if($msg==''){
        if($status==0){
            $msg = "Unknown error";
        }
        else if($status==1){
            $msg = "Success";
        }
    }
    $response = [
        "status" => $status,
        "message" => $msg,
        "data" => $data
    ];
    return json_encode($response);

}
?>