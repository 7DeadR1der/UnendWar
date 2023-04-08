<?php
    session_start();
    $type = $_GET['type'];
    if(isset($_SESSION['user'])){
        switch($_GET['type']){
            case 'user':
                echo 'ok';
                break;
            case 'active_room':
                if($_SESSION['user']['active_room'] > 0){
                    echo $_SESSION['user']['active_room'];
                }else{
                    echo 'rooms';
                }
                break;
            default:
                break;
        }
    }

?>