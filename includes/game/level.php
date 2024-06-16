<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
    include_once("classes.php");
    
    $login = $_SESSION['user']['login'];
    $idRoom = $_SESSION['user']['active_room'];
    $query = mysqli_query($connect,"SELECT `last_mod`, `game_json` FROM `rooms` WHERE `id_room` = '$idRoom'");
    if(mysqli_num_rows($query)>0){
        $type = $_GET['type'];
        $owner = $_GET['owner'];
        $choise = $_GET['choise'];
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        $json = json_decode($game['game_json']);
        if($json->local == 1) $login = $json->gamePlayers[$owner]->name;
        if($json->gamePlayers[$owner]->name == $login && $json->gamePlayers[$owner]==$json->gamePlayers[$json->gameTurn]){
            $player = $json->gamePlayers[$owner];
            $dataType="";
            $dataF="";
            $dataS="";
            switch($type){
                case 1:
                    $skills=false;
                    if($player->level==0 && $player->exp>=GAME_SETTINGS["level1"]){
                        $skills = rndSkills($player,GAME_SETTINGS);
                    }else if($player->level==1 && $player->exp>=GAME_SETTINGS["level2"]){
                        $skills = rndSkills($player,GAME_SETTINGS);
                    }else if($player->faction->name == 'Orcs' && $player->level==2 && $player->exp>=GAME_SETTINGS["level3"]){
                        $skills = rndSkills($player,GAME_SETTINGS);
                    }
                    if($skills!=false){
                        $dataType = "choice";
                        $dataF = $skills[0];
                        $dataS = $skills[1];
                        $jsonSkills = $dataType.'-'.$dataF.'-'.$dataS;
                        echo response(1,'',$jsonSkills);
                    }
                    break;
                case 2:
                    $flag=false;
                    if($player->level==0 && $player->exp>=GAME_SETTINGS["level1"]){
                        $player->level=1;
                        $flag=true;
                    }else if($player->level==1 && $player->exp>=GAME_SETTINGS["level2"]){
                        $player->level=2;
                        $flag=true;
                    }else if($player->faction->name == 'Orcs' && $player->level==2 && $player->exp>=GAME_SETTINGS["level3"]){
                        $player->level=3;
                        $flag=true;
                    }
                    if($choise!=''&&$player->level!=0&&$player->level<=3&&$flag==true){
                        switch($choise){
                            case "Strength I": //Strength I
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->attack +=1;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->leader[4] += 1;
                                break;
                            case "Endurance": // Strength II
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->hp +=2;
                                        $array[$k]->hpMax +=2;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->leader[3] += 2;
                                break;
                            case "Pathfinder":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->movePoint +=1;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->leader[5] += 1;
                                break;
                            case "Surgery":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'surgery');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->leader[8],'surgery');
                                break;
                            case "Estates I":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                setGold($owner,'+',5);
                                //$json->gamePlayers[$owner]->gold +=4;
                                break;
                            case "Estates II":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                break;
                            case "Engineering":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('building',$owner,$json->gameField);
                                if($json->gamePlayers[$owner]->faction->name == "Orcs"){
                                    $json->gamePlayers[$owner]->faction->t3[3]+=1;
                                }
                                $json->gamePlayers[$owner]->faction->tower[3]+=1;
                                $json->gamePlayers[$owner]->faction->townhall[3]+=1;
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
                            case "Scavengers":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                /*$array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'cannibal');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->leader[8],'cannibal');*/
                                $array = selectUnits('t2',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'cannibal');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->t2[8],'cannibal');

                                /*
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'scavenger');
                                        $array[$k]->hp +=1;
                                        $array[$k]->hpMax +=1;
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->leader[3] += 1;
                                array_push($json->gamePlayers[$owner]->faction->leader[8],'scavenger');

                                array_push($json->gamePlayers[$owner]->faction->t1[8],'rush');
                                */
                                break;
                            case "Undead Horde":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'teleport');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->leader[8],'teleport');
                                /*
                                $array = selectUnits('t2',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        array_push($array[$k]->ability,'infect');
                                    }
                                }
                                array_push($json->gamePlayers[$owner]->faction->t2[8],'infect');
                                */
                                break;
                            case "Undead Unholy":
                                array_push($json->gamePlayers[$owner]->skills,$choise);
                                $array = selectUnits('leader',$owner,$json->gameField);
                                if(count($array)>0){
                                    for($k=0;$k<count($array);$k++){
                                        $array[$k]->hp +=1;
                                        $array[$k]->hpMax +=1;
                                        array_push($array[$k]->ability,'darkBolt');
                                    }
                                }
                                $json->gamePlayers[$owner]->faction->leader[3] += 1;
                                array_push($json->gamePlayers[$owner]->faction->leader[8],'darkBolt');
                                break;
                            default:
                                $player->level-=1;
                                //error
                                break;
                        }
                        $dataType='ok';
                        $ts = time();
                        $jsonData = json_encode($json);
                        $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$idRoom'");
                        
                    }
                    break;
                default:
                    break;
            }
        }
    }
    function rndSkills($player,$gS){
        $skillsPoint = 0;
        $skillsChoise = [false,false];
        $skillsData = $gS["skills"];
        //if($player->faction->name == "Orcs"){
        //    array_push($skillsData,$gS["orcs_skills"][0]);
        //}
        while($skillsPoint < 2){
            if($player->faction->name == 'Undead' && $player->level == 1){
                $skillsChoise[$skillsPoint] = $gS["skills"][0]["name"];
                $skillsPoint+=1;
                $skillsChoise[$skillsPoint] = $gS["skills"][1]["name"];
                $skillsPoint+=1;
                /*
                if(in_array($gS["skills"][0]["name"],$player->skills)){
                    $skillsChoise[$skillsPoint] = $gS["skills"][1]["name"];
                    $skillsPoint+=1;
                }else{
                    $skillsChoise[$skillsPoint] = $gS["skills"][0]["name"];
                    $skillsPoint+=1;
                }*/
            //}else if(in_array(,$player->skills)){

            }else{
                $rndSkill = $skillsData[rand(0,count($skillsData)-1)];
                
                if(!in_array($rndSkill["name"],$player->skills) && 
                $skillsChoise[0]!=$rndSkill["name"] && 
                (!array_key_exists("faction",$rndSkill) || $rndSkill["faction"] == $player->faction->name)){
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
        for($i=0;$i<count($gf);$i++){
            for($j=0;$j<count($gf[$i]);$j++){
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