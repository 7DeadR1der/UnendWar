<?php
    session_start();
    require_once '../connect.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
        for ($i=1;$i<count($json->gamePlayers);$i++){
            if($login == $json->gamePlayers[$i]->name){
                $id_player = $i;
            }
        }
        if($json->gameTurn == $id_player){
            include_once("classes.php");
            $fi=$_GET['fi'];
            $fj=$_GET['fj'];
            $btn=$_GET['btn'];
            $si=$_GET['si'];
            $sj=$_GET['sj'];
            $param=$_GET['param'];
            if($json->gameField[$fi][$fj]->contains!=false && $json->gameField[$fi][$fj]->contains->owner==$json->gameTurn){
                switch($btn){
                    case'':
                        if($json->gameField[$si][$sj]->contains == false){
                            //move
                            if($json->gameField[$fi][$fj]->contains->canMove==true){
                                $json->gameField[$si][$sj]->contains=$json->gameField[$fi][$fj]->contains;
                                $json->gameField[$si][$sj]->contains->canMove=false;
                                $json->gameField[$fi][$fj]->contains=false;
                            }
                        }else if($json->gameField[$si][$sj]->contains->owner!=$json->gameField[$fi][$fj]->contains->owner){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                $atkUnit=$json->gameField[$fi][$fj]->contains;
                                $defUnit=$json->gameField[$si][$sj]->contains;
                                $atk=$json->gameField[$fi][$fj]->contains->attack;
                                //atk mod
                                if(in_array('cavalryStrike',$json->gameField[$fi][$fj]->contains->ability)&&$json->gameField[$fi][$fj]->contains->canMove==false){
                                    $atk+=1;
                                }
                                //atk math
                                if($defUnit->hp-$atk>0){
                                    $json->gameField[$si][$sj]->contains->hp-=$atk;
                                }else{
                                    $json->gameField[$si][$sj]->contains=false;
                                    $json->gamePlayers[$json->gameField[$fi][$fj]->contains->owner]->exp+=1;

                                }
                                $json->gameField[$fi][$fj]->contains->canAction=false;
                            }
                        }else if($json->gameField[$si][$sj]->contains->owner==$json->gameField[$fi][$fj]->contains->owner){
                            //heal
                            if(in_array('surgery',$json->gameField[$fi][$fj]->contains->ability)){
                                if($json->gameField[$fi][$fj]->contains->canAction==true&&$json->gameField[$si][$sj]->contains->hpMax>$json->gameField[$si][$sj]->contains->hp){
                                    $json->gameField[$si][$sj]->contains->hp+=1;
                                    $json->gameField[$fi][$fj]->contains->canAction = false;
                                }
                            }else if(in_array('sacrifice',$json->gameField[$fi][$fj]->contains->ability)){

                            }
                        }
                        break;
                    case'build':
                        if(in_array('worker',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                $count=countCalc($json->gameField,$param,$id_player);
                                $building;
                                $checkLimit=false;
                                switch($param){
                                    case'townhall':
                                        if($count<$gameSettings["limit_townhalls"]){
                                            $checkLimit=true;
                                            $building=$json->gamePlayers[$id_player]->faction->townhall;
                                        }
                                        break;
                                    case'tower':
                                        if($count<$gameSettings["limit_towers"]){
                                            $checkLimit=true;
                                            $building=$json->gamePlayers[$id_player]->faction->tower;
                                        }
                                        break;
                                    default:
                                        echo 'error';
                                        break;
                                }
                                if($json->gameField[$fi][$fj]->resCount<=0){
                                    if($checkLimit==true){
                                        if($json->gamePlayers[$id_player]->gold>=$building[8]){
                                            $json->gamePlayers[$id_player]->gold-=$building[8];
                                            $json->gameField[$fi][$fj]->contains = new Unit($building,$id_player,false);
                                        }//else nothing gold
                                    }//else nothing limit
                                }//else goldore 
                            }
                        }
                        break;
                    case'hire':
                        if(in_array('hire',$json->gameField[$fi][$fj]->contains->ability)){
                            $array=explode('-',$json->gameField[$fi][$fj]->contains->out);
                            if(in_array($param,$array)){
                                if($json->gameField[$fi][$fj]->contains->canAction==true){
                                    $cell=$json->gameField[$si][$sj];
                                    if($cell->contains==false&&$cell->mountains==false){
                                        $count=countCalc($json->gameField,$param,$id_player);
                                        $unit;
                                        $checkLimit = false;
                                        switch($param){
                                            case't1':
                                                if($count<$gameSettings["limit_workers"]){
                                                    $checkLimit = true;
                                                    $unit = $json->gamePlayers[$id_player]->faction->t1;
                                                }
                                                break;
                                            case 't2':
                                                if($count<$gameSettings["limit_army"]){
                                                    $checkLimit = true;
                                                    $unit = $json->gamePlayers[$id_player]->faction->t2;
                                                }
                                                break;
                                            case 't3':
                                                if($count<$gameSettings["limit_army"]){
                                                    $checkLimit = true;
                                                    $unit = $json->gamePlayers[$id_player]->faction->t3;
                                                }
                                                break;
                                            case 'warchief':
                                                if($count<$gameSettings["limit_warchiefs"]){
                                                    if($unit = $json->gamePlayers[$id_player]->faction->warchief[12]=='2t'){
                                                        $countTowers=countCalc($json->gameField,'tower',$id_player);
                                                        if($countTowers>=2){
                                                            $checkLimit = true;
                                                            $unit = $json->gamePlayers[$id_player]->faction->warchief;
                                                        }//else alert('Для Вождя нужно 2 башни');
                                                    }else {
                                                        $checkLimit = true;
                                                        $unit = $json->gamePlayers[$id_player]->faction->warchief;
                                                    }
                                                }
                                                break;
                                            default:
                                                //alert('ошибка в выборе юнита');
                                                break;
                                        }
                                        if($checkLimit==true){
                                            if($json->gamePlayers[$id_player]->gold>=$unit[8]){
                                                $json->gameField[$si][$sj]->contains = new Unit($unit,$id_player,false);
                                                $json->gameField[$fi][$fj]->contains->canAction = false;
                                                $json->gamePlayers[$id_player]->gold-=$unit[8];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        break;
                    case'heal':
                        if(in_array('surgery',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                if($json->gameField[$fi][$fj]->contains->hp<$json->gameField[$fi][$fj]->contains->hpMax)
                                $json->gameField[$fi][$fj]->contains->canAction =false;
                                $json->gameField[$fi][$fj]->contains->hp+=1;
                            }
                        }
                        break;
                    case'spell':
                        break;
                    case'delete':
    
                        break;
                    default:
                        break;
                }
                
                $ts = time();
                $jsonData = json_encode($json);
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
                echo "success";
            }
        }

    }
    function countCalc($gf,$class,$owner){
        $num=0;
        for($i=0;$i<8;$i++){
            for($j=0;$j<8;$j++){
                if($gf[$i][$j]->contains!=false){
                    if($gf[$i][$j]->contains->owner==$owner){
                        if($gf[$i][$j]->contains->class==$class){
                            $num+=1;
                        }
                    }
                }
            }
        }
        return $num;
    }
?>