<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!empty($_POST["faction"]) || !empty($_POST["color"])){
    require_once '../general.php';
    $idRoom = $_SESSION['user']['active_room'];
    $idUser = $_SESSION['user']['id'];
    //$playerString = "";
    //$factionString = "";
    $query = mysqli_query($connect, "SELECT `count_players`, `players_id` FROM `rooms` WHERE `id_room` = '$idRoom'");
    $row = mysqli_fetch_row($query);
    $count = $row[0];
    $array = json_decode($row[1],true);
    
    $varSend=false;
    //$playerList = explode('-',$row[1]);
    //$factionList = explode('-',$row[2]);
    foreach($array as $key => $value){
        if($value["id"] == $idUser){
            if(!empty($_POST["faction"])){
                $array[$key]["faction"]=$_POST['faction'];
                $varSend=true;
            }
            if(!empty($_POST["color"])){
                if($_POST['color']==9){
                    $_POST['color']=0;
                }
                if($_POST['color']!=$array[$key]["color"]){
                    $colorArr=[];
                    for($l=0;$l<count($array);$l++){
                        array_push($colorArr,$array[$l]["color"]);
                    }
                    //var_dump($colorArr);
                    if(!in_array($_POST['color'],$colorArr)){
                        $array[$key]["color"]=$_POST['color'];
                        $varSend=true;
                    }
                }
            }
            break;
        }
    }
    if($varSend==true){
        $ids = json_encode($array);
        $ts = time();
        mysqli_query($connect, "UPDATE `rooms` SET `players_id` = '$ids', 
         `last_mod` = '$ts' WHERE `id_room` = '$idRoom'");
    }
    unset($playerList);
    unset($factionList);
    unset($playerString);
    unset($factionString);
}
    /*for($i=0;$i<$count;$i++){
        if($playerList[$i]==$idUser){
            $factionList[$i] = $_POST['faction'];
        }
    }
    foreach($playerList as $str){
        if($str != false){
            $playerString .= $str.'-';
        }
    };
    foreach ($factionList as $str){
        if($str != false){
            $factionString .= $str.'-';
        }
    }*/
?>