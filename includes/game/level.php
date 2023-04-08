<?php
    session_start();
    require_once '../connect.php';
    include_once("classes.php");
    
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT * FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $type = $_GET['type'];
        $owner = $_GET['owner'];
        $choise = $_GET['choise'];
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
        if($json->gamePlayers[$owner]->name == $login && $json->gamePlayers[$owner]==$json->gamePlayers[$json->gameTurn]){
            $player = $json->gamePlayers[$owner];
            $dataType="";
            $dataF="";
            $dataS="";
            switch($type){
                case 1:
                    $skills=false;
                    if($player->level==0 && $player->exp>=$gameSettings["level1"]){
                        $skills = rndSkills($player,$gameSettings);
                    }else if($player->level==1 && $player->exp>=$gameSettings["level2"]){
                        $skills = rndSkills($player,$gameSettings);
                    }else if($player->faction->name == 'Orcs' && $player->level==2 && $player->exp>=$gameSettings["level3"]){
                        $skills = rndSkills($player,$gameSettings);
                    }
                    if($skills!=false){
                        $dataType = "choice";
                        $dataF = $skills[0];
                        $dataS = $skills[1];
                        $jsonSkills = $dataType.'-'.$dataF.'-'.$dataS;
                        echo $jsonSkills;
                    }
                    break;
                case 2:
                    $flag=false;
                    if($player->level==0 && $player->exp>=$gameSettings["level1"]){
                        $player->level=1;
                        $flag=true;
                    }else if($player->level==1 && $player->exp>=$gameSettings["level2"]){
                        $player->level=2;
                        $flag=true;
                    }else if($player->faction->name == 'Orcs' && $player->level==2 && $player->exp>=$gameSettings["level3"]){
                        $player->level=3;
                        $flag=true;
                    }
                    if($choise!=''&&$player->level!=0&&$player->level<=3&&$flag==true){
                        switch($choise){
                            case "Strength I": //Strength I
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('warchief',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->attack +=1;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->warchief[5] += 1;
                                break;
                            case "Strength II":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('warchief',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->hp +=2;
                                        $array[$k]->hpMax +=2;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->warchief[4] += 2;
                                break;
                            case "Pathfinder":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('warchief',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->movePoint +=1;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->warchief[6] += 1;
                                break;
                            case "Surgery":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('warchief',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'surgery');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->warchief[9],'surgery');
                                break;
                            case "Estates I":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $json->gamePlayers[$owner]->gold +=4;
                                break;
                            case "Estates II":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                break;
                            case "Engineering":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('building',$owner,$json->gameField);
                                if($json->gamePlayers[$owner]->faction->name == "Orcs"){
                                    $json->gamePlayers[$owner]->faction->t3[4]+=1;
                                }
                                $json->gamePlayers[$owner]->faction->tower[4]+=1;
                                $json->gamePlayers[$owner]->faction->townhall[4]+=1;
                                /*foreach($json->gameplayers[$owner]->faction as $unit){
                                    //if(is_array($unit)){
                                        if($unit[0]=='building'){
                                            $unit[4]+=1;
                                        }
                                    //}
                                }*/
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->hp +=1;
                                        $array[$k]->hpMax +=1;
                                    }
                                }
                                break;
                            case "Undead I":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                break;
                            case "Undead II":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                break;
                            default:
                                $player->level-=1;
                                //error
                                break;
                        }
                        $dataType='ok';
                    }
                    break;
                default:
                    break;
            }
            $ts = time();
            $jsonData = json_encode($json);
            $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
            
        }
    }
    function rndSkills($player,$gS){
        $skillsPoint = 0;
        $skillsChoise = [false,false];
        $skillsData = $gS["skills"];
        while($skillsPoint < 2){
            if($player->faction->name == 'Undead' && $skillsPoint==0){

            }else{
                $rndSkill = $skillsData[rand(0,count($skillsData)-1)];

                if(!in_array($rndSkill["name"],$player->skills) && $skillsChoise[0]!=$rndSkill["name"]){
                    if(array_key_exists("require",$rndSkill)){
                        if(in_array($rndSkill["require"],$player->skills)){
                            $skillsChoise[$skillsPoint] = $rndSkill["name"];
                            $skillsPoint+=1;
                        }
                    }else{
                        $skillsChoise[$skillsPoint] = $rndSkill["name"];
                        $skillsPoint+=1;
                    }
                }
            }
        }
        if($skillsChoise[0]!=false && $skillsChoise[1]!=false){
            return $skillsChoise;
        }
    }
    function selectUnits($class,$owner,$gf){
        $array = [];
        for($i=0;$i<8;$i++){
            for($j=0;$j<8;$j++){
                if($gf[$i][$j]->contains!=false){
                    if($class=='building'){
                        if($gf[$i][$j]->contains->type == $class && $gf[$i][$j]->contains->owner == $owner){
                            array_push($array,$gf[$i][$j]->contains);
                        }
                    }else{
                        if($gf[$i][$j]->contains->class == $class && $gf[$i][$j]->contains->owner == $owner){
                            array_push($array,$gf[$i][$j]->contains);
                        }
                    }
                }
            }
        }
        return $array;
    }
?>