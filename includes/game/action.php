<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $player;
    $query = mysqli_query($connect,"SELECT `game_json`,`last_mod` FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
            for ($i=1;$i<count($json->gamePlayers);$i++){
                if($login == $json->gamePlayers[$i]->name){
                    $player = $i;
                }
            }
        if($json->local == 1 || $json->gameTurn == $player){
            $player = $json->gameTurn;
            include_once("classes.php");
            $fi=$_GET['fi'];
            $fj=$_GET['fj'];
            $btn=$_GET['btn'];
            $si=$_GET['si'];
            $sj=$_GET['sj'];
            $param=$_GET['param'];
            $animType = '';
            $animVariant = '';
            if($json->gameField[$fi][$fj]->contains!=false && $json->gameField[$fi][$fj]->contains->owner==$json->gameTurn){
                switch($btn){
                    case'':
                        if($json->gameField[$si][$sj]->contains == false){
                            //move
                            $speed = $json->gameField[$fi][$fj]->contains->movePoint;
                            if(in_array('rush',$json->gameField[$fi][$fj]->contains->ability)){
                                $speed += 1;
                                $search = array_search('rush',$json->gameField[$fi][$fj]->contains->ability);
                                if($search !== false){
                                    array_splice($json->gameField[$fi][$fj]->contains->ability,$search,1);
                                }
                            }
                            if($json->gameField[$fi][$fj]->contains->canMove==true && abs(($fi-$si)+($fj-$sj))<=$speed){
                                $json->gameField[$si][$sj]->contains=$json->gameField[$fi][$fj]->contains;
                                $json->gameField[$si][$sj]->contains->canMove=false;
                                $json->gameField[$fi][$fj]->contains=false;
                                $animType = 'move';
                                $animVariant = '';

                            }
                        }else if($json->gameField[$si][$sj]->contains->owner!=$json->gameField[$fi][$fj]->contains->owner){
                            //attack
                            if($json->gameField[$fi][$fj]->contains->canAction==true && abs(($fi-$si)+($fj-$sj))<=$json->gameField[$fi][$fj]->contains->range
                            && (!in_array('meleeOnly',$json->gameField[$si][$sj]->contains->ability)||(in_array('meleeOnly',$json->gameField[$si][$sj]->contains->ability) && abs(($fi-$si)+($fj-$sj))==1))){
                                $atkUnit=$json->gameField[$fi][$fj]->contains;
                                $defUnit=$json->gameField[$si][$sj]->contains;
                                $atk=$json->gameField[$fi][$fj]->contains->attack;
                                //atk mod
                                if(in_array('cavalryStrike',$json->gameField[$fi][$fj]->contains->ability)&&$json->gameField[$fi][$fj]->contains->canMove==false){
                                    $atk+=1;
                                }
                                if(in_array('siegeDmg',$json->gameField[$fi][$fj]->contains->ability) && $json->gameField[$si][$sj]->contains->type == 'building'){
                                    $atk+=1;
                                }
                                if(in_array('sharp',$json->gameField[$fi][$fj]->contains->ability)){
                                    $atk += 1;
                                    $search = array_search('sharp',$json->gameField[$fi][$fj]->contains->ability);
                                    if($search !== false){
                                        array_splice($json->gameField[$fi][$fj]->contains->ability,$search,1);
                                    }
                                }
                                if(in_array('evasion',$json->gameField[$si][$sj]->contains->ability)){
                                    $atk = 0;
                                    $search = array_search('evasion',$json->gameField[$si][$sj]->contains->ability);
                                    if($search !== false){
                                        array_splice($json->gameField[$si][$sj]->contains->ability,$search,1);
                                    }
                                }
                                if($atk>0 && in_array('veteran',$json->gameField[$si][$sj]->contains->ability)){
                                    if($json->gameField[$si][$sj]->contains->canAction == true){
                                        $atk -= 1;
                                        $json->gameField[$si][$sj]->contains->canAction = false;
                                    }
                                    /*$chanceArray=[0,0,0,1,1,1,1,1,1,1];
                                    $s= array_rand($chanceArray);
                                    if($chanceArray[$s] == 0){
                                        $atk = 0;
                                    }*/
                                }
                                if($atk>0 && in_array('armor',$json->gameField[$si][$sj]->contains->ability)){
                                    $atk -= 1;
                                    $search = array_search('armor',$json->gameField[$si][$sj]->contains->ability);
                                    if($search !== false){
                                        array_splice($json->gameField[$si][$sj]->contains->ability,$search,1);
                                    }
                                }

                                if($atk>0){
                                    if(in_array('vampir',$json->gameField[$fi][$fj]->contains->ability)&&$json->gameField[$fi][$fj]->contains->hp<$json->gameField[$fi][$fj]->contains->hpMax&&$json->gameField[$si][$sj]->contains->type != 'building'){
                                        $json->gameField[$fi][$fj]->contains->hp+=1;
                                    }

                                }
                                //atk math
                                if($defUnit->hp-$atk>0){
                                    $json->gameField[$si][$sj]->contains->hp-=$atk;
                                }else{
                                    kill($fi,$fj,$si,$sj);
                                }
                                $json->gameField[$fi][$fj]->contains->canAction=false;
                                $animType = 'attack';
                                $animVariant = '';
                            }
                        }else if($json->gameField[$si][$sj]->contains->owner==$json->gameField[$fi][$fj]->contains->owner){
                            //heal
                            if(in_array('surgery',$json->gameField[$fi][$fj]->contains->ability)){
                                if($json->gameField[$fi][$fj]->contains->canAction==true &&
                                $json->gameField[$si][$sj]->contains->hpMax > $json->gameField[$si][$sj]->contains->hp
                                && abs(($fi-$si)+($fj-$sj))<=1 && $json->gameField[$si][$sj]->contains->type == 'unit' 
                                /*&& ($json->gameField[$si][$sj]->contains->hp < ceil($json->gameField[$si][$sj]->contains->hpMax/2) &&
                                $json->gameField[$si][$sj]->contains->hp < 3 ||  $json->gameField[$si][$sj]->contains->hp == 1)*/

                                ){
                                    $json->gameField[$si][$sj]->contains->hp += 1;
                                    $json->gameField[$fi][$fj]->contains->canAction = false;
                                    $animType = 'heal';
                                    $animVariant = 'other';
                                }
                            }else if(in_array('sacrifice',$json->gameField[$fi][$fj]->contains->ability)){
                                if($json->gameField[$fi][$fj]->contains->canAction==true&&$json->gameField[$si][$sj]->contains->hpMax>$json->gameField[$si][$sj]->contains->hp
                                && abs(($fi-$si)+($fj-$sj))<=1 && $json->gameField[$si][$sj]->contains->type == 'unit'){
                                    $json->gameField[$si][$sj]->contains->hp+=1;
                                    $json->gameField[$fi][$fj]->contains = false;
                                }
                            }
                        }
                        break;
                    case'build':
                        if(in_array('worker',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                switch($param){
                                    case'townhall':
                                            $building=$json->gamePlayers[$player]->faction->townhall;
                                        break;
                                    case'tower':
                                            $building=$json->gamePlayers[$player]->faction->tower;
                                        break;
                                    default:
                                        echo 'error';
                                        break;
                                }
                                if($json->gameField[$fi][$fj]->resCount<=0){
                                    $spawn = spawn($building,$player);
                                    if($spawn != false){
                                        $json->gameField[$fi][$fj]->contains = $spawn;
                                        $animType = '';
                                        $animVariant = '';
                                    }
                                    /*if($checkLimit==true){
                                        if($json->gamePlayers[$player]->gold>=$building[7]){
                                            //$json->gamePlayers[$player]->gold-=$building[7];
                                            setGold($player,'-',$building[7]);
                                            $json->gameField[$fi][$fj]->contains = new Unit($building,$player,false);
                                            scoring($player,$json->gameField[$fi][$fj]->contains->price,"build","Up",1);
                                        }//else nothing gold
                                    }*///else nothing limit
                                }//else goldore 
                            }
                        }
                        break;
                    case'hire':
                        if(in_array('hire',$json->gameField[$fi][$fj]->contains->ability)){
                            $array=explode('-',$json->gameField[$fi][$fj]->contains->out);
                            if(in_array($param,$array)){
                                if($json->gameField[$fi][$fj]->contains->canAction==true && abs(($fi-$si)+($fj-$sj))<=1){
                                    $cell=$json->gameField[$si][$sj];
                                    if($cell->contains==false&&$cell->obstacle==0){
                                        $unit;
                                        switch($param){
                                            case't1':
                                                $unit = $json->gamePlayers[$player]->faction->t1;
                                                break;
                                            case 't2':
                                                $unit = $json->gamePlayers[$player]->faction->t2;
                                                break;
                                            case 't3':
                                                $unit = $json->gamePlayers[$player]->faction->t3;
                                                break;
                                            case 'warchief':
                                                $unit = $json->gamePlayers[$player]->faction->warchief;
                                                break;
                                            default:
                                                //alert('ошибка в выборе юнита');
                                                break;
                                        }
                                        $spawn = spawn($unit,$player);
                                        if($spawn != false){
                                            $cell->contains = $spawn;
                                            $json->gameField[$fi][$fj]->contains->canAction = false;
                                            $animType = '';
                                            $animVariant = '';
                                        }
                                        /*if($checkLimit==true){
                                            if($json->gamePlayers[$player]->gold>=$unit[7]){
                                                $json->gameField[$si][$sj]->contains = new Unit($unit,$player,false);
                                                $json->gameField[$fi][$fj]->contains->canAction = false;
                                                setGold($player,'-',$unit[7]);
                                                //$json->gamePlayers[$player]->gold-=$unit[7];
                                                if($json->gameField[$si][$sj]->contains->type == "unit"){
                                                    if($json->gameField[$si][$sj]->contains->class == "warchief"){
                                                        scoring($player,$json->gameField[$si][$sj]->contains->price,"warchief","Up",1);
                                                    }else if($json->gameField[$si][$sj]->contains->class == "t1"){
                                                        scoring($player,$json->gameField[$si][$sj]->contains->price,"worker","Up",1);
                                                    }else{
                                                        scoring($player,$json->gameField[$si][$sj]->contains->price,"unit","Up",1);
                                                    }
                                                }else{
                                                    scoring($player,$json->gameField[$si][$sj]->contains->price,"build","Up",1);
                                                }
                                            }
                                        }*/
                                    }
                                }
                            }
                        }

                        break;
                    case'heal':
                        if(in_array('surgery',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                if(/*(*/$json->gameField[$fi][$fj]->contains->hp < ceil($json->gameField[$fi][$fj]->contains->hpMax/2)/* &&
                                    $json->gameField[$fi][$fj]->contains->hp<3) || 
                                    ($json->gameField[$fi][$fj]->contains->hp==1 && 
                                    $json->gameField[$fi][$fj]->contains->hp<$json->gameField[$fi][$fj]->contains->hpMax)*/
                                ){
                                    $json->gameField[$fi][$fj]->contains->canAction =false;
                                    $json->gameField[$fi][$fj]->contains->hp+=1;
                                    $animType = 'heal';
                                    $animVariant = 'self';
                                }
                            }
                        }
                        break;
                    case'darkArmy':
                        if(in_array('darkArmy',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true){
                                $limitT1=countCalc($json->gameField,'t1',$player);
                                if($limitT1<$gameSettings["limit_workers"]){
                                    $arrayCells = [];
                                    if($fi+1<8&&$json->gameField[$fi+1][$fj]->contains==false&&$json->gameField[$fi+1][$fj]->obstacle==0) 
                                        array_push($arrayCells,$json->gameField[$fi+1][$fj]);
                                    if($fi-1>-1&&$json->gameField[$fi-1][$fj]->contains==false&&$json->gameField[$fi-1][$fj]->obstacle==0)
                                        array_push($arrayCells,$json->gameField[$fi-1][$fj]);
                                    if($fj+1<8&&$json->gameField[$fi][$fj+1]->contains==false&&$json->gameField[$fi][$fj+1]->obstacle==0) 
                                        array_push($arrayCells,$json->gameField[$fi][$fj+1]);
                                    if($fj-1>-1&&$json->gameField[$fi][$fj-1]->contains==false&&$json->gameField[$fi][$fj-1]->obstacle==0)
                                        array_push($arrayCells,$json->gameField[$fi][$fj-1]);
                                    
                                    if(count($arrayCells)>0){
                                        $count=0;
                                        $unit = GAME_OBJ["skeleton"];
                                        $count=countCalc($json->gameField,'townhall',$player);
                                        /*if(in_array('Undead I',$json->gamePlayers[$player]->skills)){
                                            $count=countCalc($json->gameField,'townhall',$player);
                                        }*/
                                        $count+=1;
                                        if($gameSettings["limit_workers"]<=$limitT1+$count){
                                            $count = $count-abs($gameSettings["limit_workers"]-($count+$limitT1));
                                        }
                                        for($k=0;$k<$count;$k++){
                                            if(count($arrayCells)>0){
                                                $n=rand(0,count($arrayCells)-1);
                                                if(isset($arrayCells[$n])){
                                                    $ti = $arrayCells[$n]->row;
                                                    $tj = $arrayCells[$n]->column;
                                                    $spawn = spawn($unit,$player,false);
                                                    if($spawn != false){
                                                        $json->gameField[$ti][$tj]->contains = $spawn;
                                                        $json->gameField[$fi][$fj]->contains->canAction =false;
                                                        array_splice($arrayCells,$n,1);
                                                        $animType = 'darkArmy';
                                                        $animVariant = '';
                                                    }
                                                    //$json->gameField[$ti][$tj]->contains = spawn($unit,$player,false);//new Unit($unit,$player,false);
                                                    //scoring($player,$json->gameField[$si][$sj]->contains->price,"worker","Up",1);
                                                    
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        };
                        break;
                    case'darkStorm':
                        if(in_array('darkStorm',$json->gameField[$fi][$fj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true && $json->gameField[$fi][$fj]->contains->canMove==true){
                                $hp = 0;
                                $arrayCells = [];
                                $json->gameField[$fi][$fj]->contains->canAction=false;
                                $json->gameField[$fi][$fj]->contains->canMove=false;
                                if($fi+1<8&&$json->gameField[$fi+1][$fj]->contains!=false) 
                                    array_push($arrayCells,$json->gameField[$fi+1][$fj]);
                                if($fi-1>-1&&$json->gameField[$fi-1][$fj]->contains!=false)
                                    array_push($arrayCells,$json->gameField[$fi-1][$fj]);
                                if($fj+1<8&&$json->gameField[$fi][$fj+1]->contains!=false) 
                                    array_push($arrayCells,$json->gameField[$fi][$fj+1]);
                                if($fj-1>-1&&$json->gameField[$fi][$fj-1]->contains!=false)
                                    array_push($arrayCells,$json->gameField[$fi][$fj-1]);
                                for($i=0;$i<count($arrayCells);$i++){
                                    if($arrayCells[$i]->contains->type == 'unit' && $json->gameField[$fi][$fj]->contains->hp < $json->gameField[$fi][$fj]->contains->hpMax){
                                        if($arrayCells[$i]->contains->owner == $player){
                                            $hp += 0.5;
                                        }else{
                                            $hp += 1;
                                        }
                                    }
                                    if($arrayCells[$i]->contains->hp>1){
                                        $arrayCells[$i]->contains->hp -= 1;
                                    }else{
                                        kill($fi,$fj,$arrayCells[$i]->row,$arrayCells[$i]->column);
                                    };

                                }
                                $hp = floor($hp);
                                if($json->gameField[$fi][$fj]->contains->hp + $hp <= $json->gameField[$fi][$fj]->contains->hpMax){
                                    $json->gameField[$fi][$fj]->contains->hp += $hp;
                                }else{
                                    $json->gameField[$fi][$fj]->contains->hp = $json->gameField[$fi][$fj]->contains->hpMax;
                                }
                                $animType = 'darkStorm';
                                $animVariant = '';

                            }
                        };
                        break;
                    case 'smith':
                        if(in_array('smith',$json->gameField[$fi][$fj]->contains->ability) && !in_array('armor',$json->gameField[$si][$sj]->contains->ability)){
                            if($json->gameField[$fi][$fj]->contains->canAction==true && $json->gameField[$fi][$fj]->contains->owner == $json->gameTurn
                            && abs(($fi-$si)+($fj-$sj))<=1 && $json->gameField[$si][$sj]->contains->type == 'unit' && $json->gamePlayers[$player]->gold>0){
                                
                                $json->gameField[$fi][$fj]->contains->canAction =false;
                                setGold($player,'-',1);
                                //$json->gamePlayers[$player]->scoring($player,1);
                                array_push($json->gameField[$si][$sj]->contains->ability,'armor');
                                $animType = 'smith';
                                $animVariant = '';
                            }
                        }
                        break;
                    case'spell':
                        break;
                    case'delete':
                        if($json->gameField[$fi][$fj]->contains->type=='building' && $json->gameField[$fi][$fj]->contains->canAction==true)
                            $json->gameField[$fi][$fj]->contains = false;
                        break;
                    default:
                        break;
                }
                $json->animation = [$fi,$fj,$animType,$si,$sj,$animVariant];
                $ts = time();
                $jsonData = json_encode($json);
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
                echo "success";
            }
        }

    }


    function kill(int $fi,int $fj,int $si,int $sj){
        global $gameSettings;
        global $json;
        //global $player;
        $player = $json->gameField[$fi][$fj]->contains->owner;
        $exp = 1;
        switch($json->gameField[$si][$sj]->contains->type){
            case "unit":
                if($json->gameField[$si][$sj]->contains->class == "warchief"){
                    scoring($player,$json->gameField[$si][$sj]->contains->price,"warchief","Down",1);
                    $exp += $json->gamePlayers[$json->gameField[$si][$sj]->contains->owner]->level + 1;
                }else if($json->gameField[$si][$sj]->contains->class == "t1"){
                    scoring($player,$json->gameField[$si][$sj]->contains->price,"worker","Down",1);
                }
                else{
                    scoring($player,$json->gameField[$si][$sj]->contains->price,"unit","Down",1);
                }
                break;
            case "building":
                scoring($player,$json->gameField[$si][$sj]->contains->price,"build","Down",1);
                break;
            default:
                break;
        }
        $killUnit = true;
        
        if(in_array('bloodAxe',$json->gameField[$fi][$fj]->contains->ability)&&$json->gameField[$si][$sj]->contains->type == 'unit'){
            if(!in_array('evasion',$json->gameField[$fi][$fj]->contains->ability)){
                array_push($json->gameField[$fi][$fj]->contains->ability,'evasion');
            }
            if(!in_array('rush',$json->gameField[$fi][$fj]->contains->ability)){
                array_push($json->gameField[$fi][$fj]->contains->ability,'rush');
            }
        }

        if(in_array('pillage',$json->gameField[$fi][$fj]->contains->ability) && $json->gameField[$si][$sj]->contains->type == 'building'){
            setGold($player,'+',1);
        }
        if(in_array('infect',$json->gameField[$fi][$fj]->contains->ability)&& $json->gameField[$si][$sj]->contains->type == 'unit'){
            
            $spawn = spawn(GAME_OBJ["skeleton"],$player,false);
            if($spawn != false){
                $json->gameField[$si][$sj]->contains = $spawn;
                $killUnit = false;
            }
            /*$count=countCalc($json->gameField,'t1',$player);
            if($count<$gameSettings["limit_workers"]){
                $json->gameField[$si][$sj]->contains = spawn($json->gamePlayers[$player]->faction->t1,$player,false);//new Unit ($json->gamePlayers[$player]->faction->t1,$player,false);
                $killUnit = false;
            }*/
        }
        if(in_array('cannibal',$json->gameField[$fi][$fj]->contains->ability)&&$json->gameField[$fi][$fj]->contains->hp<$json->gameField[$fi][$fj]->contains->hpMax&&$json->gameField[$si][$sj]->contains->type != 'building'){
            $json->gameField[$fi][$fj]->contains->hp+=1;
        }
        if(in_array('treasure',$json->gameField[$si][$sj]->contains->ability)){
            setGold($player,'+',3);
        }
        if(in_array('scavenger',$json->gameField[$fi][$fj]->contains->ability)){
            if($json->gameField[$si][$sj]->contains->class == "warchief"){
                if($json->gameField[$fi][$fj]->contains->hpMax - $json->gamePlayers[$json->gameField[$fi][$fj]->contains->owner]->faction->warchief[3] < 1){
                    $json->gameField[$fi][$fj]->contains->hpMax+=1;
                }
                if($json->gameField[$fi][$fj]->contains->hpMax > $json->gameField[$fi][$fj]->contains->hp){
                    $json->gameField[$fi][$fj]->contains->hp+=1;
                }
                
            }
        }
        if(in_array('monster',$json->gameField[$si][$sj]->contains->ability)){
            $json->gameField[$fi][$fj]->contains->hpMax+=1;
            $json->gameField[$fi][$fj]->contains->hp+=1;
            $exp += 3;
        }
        if(in_array('prison',$json->gameField[$si][$sj]->contains->ability)){
            $arrayChance = ['T','T','T','T','G','G','G','G','G','G'];
            $rnd =array_rand($arrayChance);
            $goldValue = 5;
            switch($arrayChance[$rnd]){
                case 'T':
                    $rndPl = array_rand($json->gamePlayers);
                    $spawn = spawn($json->gamePlayers[$rndPl]->faction->t3,$player,false);
                    if($spawn != false){
                        $json->gameField[$si][$sj]->contains = $spawn;
                        setGold($player,'+',$goldValue - $spawn->price);
                        $killUnit = false;
                    }
                    /*$count=countCalc($json->gameField,'t3',$player);
                    if($count<$gameSettings["limit_army"]){
                        $json->gameField[$si][$sj]->contains = new Unit ($json->gamePlayers[$rndPl]->faction->t3,$player,false);
                        setGold($player,'+',$goldValue - $json->gamePlayers[$player]->faction->t3[7]);
                        //$json->gamePlayers[$player]->gold += $goldValue - $json->gamePlayers[$player]->faction->t3[7];
                        $killUnit = false;
                    }*/
                    else{
                        setGold($player,'+',$goldValue);
                    }
                    break;
                case 'G':
                    setGold($player,'+',$goldValue);
                    break;
                default:
                    setGold($player,'+',$goldValue);
                    break;
            }
            
        }
        $json->gamePlayers[$player]->exp+=$exp;
        if($killUnit == true){
            $json->gameField[$si][$sj]->contains=false;
        }

    }

    //$unit = $json->gamePlayers[$owner]->faction->t2;
    //$json->gameField[i][j]->contains = spawn($unit,$owner,true,false,true);
    
?>