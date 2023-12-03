<?php
    session_start();
    require_once 'general.php';
    $type = $_GET['type'];
    if(isset($_SESSION['user'])){
        switch($_GET['type']){
            case 'user':
                echo response(1);
                break;
            case 'active_room':
                if($_SESSION['user']['active_room'] > 0){
                    
                    echo response(2,'',$_SESSION['user']['active_room']);
                    //echo $_SESSION['user']['active_room'];
                }else{
                    echo response(3);
                    //echo 'rooms';
                }
                break;
            default:
                echo response(0,'invalid type');
                break;
        }
    }

?>