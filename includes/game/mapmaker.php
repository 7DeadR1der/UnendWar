<?php
//map name 21lr - 1 цифра макс кол-во игроков, 2 цифра режим игры который может играться 0-классик 1-охота 2-сбор
$mapSettings;

function mapMaker($field, $map, $count, $type, $mode, $players){
    global $json;
    global $mapSettings;
    $startPositions = [];
    $rndArrUnit = [];
    // new
    if($map != "40rnd"){
        $jsonMap = json_decode(file_get_contents("../../maps/".$map.".JSON"));
        $mapSettings = $jsonMap->settings;
        $json->gameLand = $jsonMap->settings->land;
        neutralByLand($json->gameLand);
        $json->gameSize[0]= $mapSettings->rowLength;
        $json->gameSize[1]= $mapSettings->columnLength;
        
        for($i=0;$i<$mapSettings->rowLength;$i++){
            for($j=0;$j<$mapSettings->columnLength;$j++){
                //$field[$i][$j] = new Cell($i,$j);
                $json->gameField[$i][$j] = new Cell($i,$j);
            }
        };



        if($jsonMap->settings->customGame == true && isset($jsonMap->factions) && $jsonMap->factions->customFactions){
            for($i=0;$i<count($players);$i++){
                $name = $players[$i]->faction->name;
                if(property_exists($jsonMap->factions, $name)){
                    $players[$i] = $jsonMap->factions->$name;
                }
            }
        }
        foreach ($jsonMap->startPositions as $key => $value) {
            array_push($startPositions,$json->gameField[$value[0]][$value[1]]);
        }
        if($jsonMap->settings->randomSpawn == true){
            shuffle($startPositions);
        }
        foreach ($jsonMap->field as $keyRow => $valueRow) {
            foreach ($valueRow as $keyCell => $valueCell) {
                $i = $valueCell->row;
                $j = $valueCell->column;
                $json->gameField[$i][$j]->resCount = $valueCell->resCount;
                $json->gameField[$i][$j]->obstacle = $valueCell->obstacle;
                if($jsonMap->settings->customGame == true){
                    $json->gameField[$i][$j]->contains = $valueCell->contains;
                }
            }
        }
        foreach ($jsonMap->creationUnits as $key => $value){
            $i = $value->row;
            $j = $value->column;
            $o = $value->owner;
            $t = $value->type;
            $default = $value->defaultUnit;
            $pack = $value->pack;
            if($default){
                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[$o]->faction->$t,(int)$o,false,true,false);
            }else{
                if($pack){
                    if($t == "rnd"){
                        $t = array_rand($jsonMap->unitPacks->$pack);
                    }
                    if (is_numeric($t)){
                        $u = is_array($jsonMap->unitPacks->$pack[$t]) ? $jsonMap->unitPacks->$pack[$t] : GAME_OBJ[$jsonMap->unitPacks->$pack[$t]];
                        $json->gameField[$i][$j]->contains = spawn($u,$o,false,true,false);
                    }else{
                        //error
                    }
                }else{
                    $json->gameField[$i][$j]->contains = spawn(GAME_OBJ[$t],$o,false,true,false);
                }
            }
        }
        //random generation
    }else{
        $mapSettings = 'rnd';
        neutralByLand($json->gameLand);
        $i_f=$json->gameSize[0];
        $j_f=$json->gameSize[1];
        $start_value = mt_rand(15,21);
        $count_zone_cells = ($i_f*$j_f)-($count*9);
        $zone_value = mt_rand(round($count_zone_cells*1.5), round($count_zone_cells*2.5));
        $pack_start = ['g','g','g','g','A','c','c','c','T1','T2'];
        $pack_res = ['g','g','g','g','c','c','c','t','t','Th'];
        $pack_enemy = ['T2','T2','T2','T2','T2','T2','T3','T3','T3','W'];
        for($i=0;$i<$i_f;$i++){
            for($j=0;$j<$j_f;$j++){
                $json->gameField[$i][$j] = new Cell($i,$j);
            }
        };
        
        $hash_field = [];
        for($i=0;$i<$i_f;$i++){
            for ($j=0;$j<$j_f;$j++){
                array_push($hash_field,[$i,$j]);
            }
        }
        $chanceArr = [1,2,3,4];
        $startArray = [[],[],[],[]];
        shuffle($chanceArr);
        for($l=0;$l<$count;$l++){
            $startArray[$l] = [];
            $i_s = false;
            $j_s = false;
            switch ($chanceArr[$l]){
                case 1:
                    //0-0
                    $i_s = 0;
                    $j_s = 0;
                    break;
                case 2:
                    //0-j
                    $i_s = 0;
                    $j_s = $j_f - 3;
                    break;
                
                case 3:
                    //i-0
                    $i_s = $i_f - 3;
                    $j_s = 0;
                    break;
                
                case 4:
                    //i-j
                    $i_s = $i_f - 3;
                    $j_s = $j_f - 3;
                    break;
                default:
                    echo "ERROR 111";
                    break;
            }
            for($i=$i_s;$i<$i_s+3;$i++){
                for($j=$j_s;$j<$j_s+3;$j++){
                    //$ss = &$json->gameField[$i][$j];
                    array_push($startArray[$l],[$i,$j]);
                    $hash_field[(($i*8)+$j)]=[-1,-1];
                }
            }
            
            array_push($startPositions,$json->gameField[$startArray[$l][4][0]][$startArray[$l][4][1]]);
            //$field[$startArray[$l][4][1]][$startArray[$l][4][2]][0]='img/units/Kingdom/Townhall.png';
            array_splice($startArray[$l],4,1);
            $flag = true;
            $value = $start_value;
            
            while($flag && $value>0){
                $rnd = mt_rand(0,count($startArray[$l])-1);
                $i=$startArray[$l][$rnd][0];
                $j=$startArray[$l][$rnd][1];
                if($value<5){
                    $json->gameField[$i][$j]->resCount += $value;
                    $flag=false;
                    $value=0;
                }   
                $ch = $pack_start[array_rand($pack_start)];
                switch($ch){
                    case 'g':
                        if($json->gameField[$i][$j]->resCount == 0){
                            if($value>=5){
                                $json->gameField[$i][$j]->resCount += 5;
                                $value-=5;
                                array_splice($startArray[$l],$rnd,1);
                            }
                        }
                        break;
                    case 'A':
                        if($json->gameField[$i][$j]->resCount == 0){
                            if($value>=10){
                                $json->gameField[$i][$j]->resCount += 11;
                                $value-=10;
                                array_splice($startArray[$l],$rnd,1);
                            }
                        }
                        break;
                    case 'c':
                        if($json->gameField[$i][$j]->contains == false){
                            if($value>=5){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->t1,0,false,true,false);
                                $value-=5;
                                array_splice($startArray[$l],$rnd,1);
                            }
                        }
                        break;
                    case 'T1':
                        if($json->gameField[$i][$j]->contains == false){
                            if($value>=2){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[$l+1]->faction->t1,$l+1,false,true,false);
                                $value-=2;
                                array_splice($startArray[$l],$rnd,1);
                            }
                        }
                        break;
                    case 'T2':
                        if($json->gameField[$i][$j]->contains == false){
                            if($value>=4){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[$l+1]->faction->t2,$l+1,false,true,false);
                                $value-=4;
                                array_splice($startArray[$l],$rnd,1);
                            }
                        }
                        break;
                        
                }
                if(count($startArray[$l])<=0){
                    $flag=false;
                }
            }
            //*/
        }


        //gen value in neutral
        $flag = true;
        $value_obj = $zone_value;
        $value_enemy = $zone_value;
        
        while($flag && $value_obj>=5){
            $rnd = mt_rand(0,count($hash_field)-1);
            $i=$hash_field[$rnd][0];
            $j=$hash_field[$rnd][1];
            if($hash_field[$rnd][0]!== -1 && $hash_field[$rnd][1]!== -1){ //  || $field[$i][$j][0]==='g' || $field[$i][$j][0]==='q'
                $ch = $pack_res[array_rand($pack_res)];
                switch($ch){
                    case 'g':
                            if($value_obj>=5){
                                $json->gameField[$i][$j]->resCount += 5;
                                $value_obj-=5;
                            }
                        break;
                    case 'c':
                            if($value_obj>=5){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->t1,0,false,true,false);
                                $value_obj-=5;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                    case 't':
                            if($value_obj>=7){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->tower,0,false,true,false);
                                $json->gameField[$i][$j]->resCount += 7;
                                $value_obj-=7;
                                $value_enemy-=7;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                    case 'Th':
                            if($value_obj>=12){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->townhall,0,false,true,false);
                                $value_obj-=12;
                                $value_enemy-=12;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                        
                }
            }else{
                array_splice($hash_field,$rnd,1);
            }
            if(count($hash_field)<=0){
                $flag=false;
            }
        
        }
        //*/
        
        // enemy
        $flag = true;
        
        while($flag && $value_enemy>=5){
            $rnd = mt_rand(0,count($hash_field)-1);
            $i=$hash_field[$rnd][0];
            $j=$hash_field[$rnd][1];
            if($hash_field[$rnd][0]!== -1 && $hash_field[$rnd][1]!== -1){ //  || $field[$i][$j][0]==='g' || $field[$i][$j][0]==='q'
                $ch = $pack_enemy[array_rand($pack_enemy)];
                switch($ch){
                    case 'T2':
                            if($value_enemy>=5){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->t2,0,false,true,false);
                                $value_enemy-=5;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                    case 'T3':
                            if($value_enemy>=5){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->t3,0,false,true,false);
                                $value_enemy-=5;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                    case 'W':
                            if($value_enemy>=12){
                                $json->gameField[$i][$j]->contains = spawn($json->gamePlayers[0]->faction->leader,0,false,true,false);
                                $value_enemy-=12;
                                array_splice($hash_field,$rnd,1);
                            }
                        break;
                        
                }
            }else{
                array_splice($hash_field,$rnd,1);
            }
            if(count($hash_field)<=0){
                $flag=false;
            }
        
        }
        //*/
        
        // obstacle
        $max_obstacle = intdiv($count_zone_cells,4);
        $min_obstacle = intdiv($max_obstacle,2);
        $count_obstacle = mt_rand($min_obstacle, $max_obstacle);
        $flag=true;
        if(count($hash_field)>0){
            
            while($flag && $count_obstacle>0){
                $rnd = mt_rand(0,count($hash_field)-1);
                $i=$hash_field[$rnd][0];
                $j=$hash_field[$rnd][1];
                if($hash_field[$rnd][0]!== -1 && $hash_field[$rnd][1]!== -1 &&
                $json->gameField[$i][$j]->resCount === 0 && $json->gameField[$i][$j]->contains == false){ //  || $field[$i][$j][0]==='g' || $field[$i][$j][0]==='q'
                    $json->gameField[$i][$j]->obstacle = mt_rand(1,2);
                    $count_obstacle-=1;
                    array_splice($hash_field,$rnd,1);
                }else{
                    array_splice($hash_field,$rnd,1);
                }
                if(count($hash_field)<=0){
                    $flag=false;
                }
            } 
            //*/
        }
    }
    playerMaker($mode,$startPositions,$count,$players);
}

class Pattern{
    public $zones;
    function __construct($field, $namePattern)
    {
        switch($namePattern){
            case'firePat':
                for($i=0;$i<9;$i++){
                }
                break;
            default:
                break;
        }
    }
}

class Zone{
    public $dif, $cells;
    function __construct($dif,$array){

    }
}


function playerMaker($mode,$startPositions,$count,$players){
    global $mapSettings;
    $n = $count;
    $l = count($startPositions);
    for ($i=0;$i<$l;$i++){
        if($n>0){
            
            $startPositions[$i]->resCount = 0;
                switch($mode){
                    case 'classic':
                        //$startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                        if($mapSettings == 'rnd' || $mapSettings->spawnStartUnit == true){
                            $startPositions[$i]->contains = spawn($players[$i+1]->faction->townhall,$i+1,false,true,false);
                        }
                        break;
                    case 'fat':
                        //$startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                        if($mapSettings == 'rnd' || $mapSettings->spawnStartUnit == true){
                            $startPositions[$i]->contains = spawn($players[$i+1]->faction->townhall,$i+1,false,true,false);
                        }
                        $players[$i+1]->gold += 12;
                        break;
                    case 'nomad':
                        //$startPositions[$k]->contains = new Unit($players[$i+1]->faction->t1,$i+1,true);
                        if($mapSettings == 'rnd' || $mapSettings->spawnStartUnit == true){
                            $startPositions[$i]->contains = spawn($players[$i+1]->faction->t1,$i+1,false,true,false);
                        }
                        $players[$i+1]->gold +=$players[$i+1]->faction->townhall[7];
                        break;
                    default:
                        //$startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                        $startPositions[$i]->contains = spawn($players[$i+1]->faction->townhall,$i+1,false,true,false);
                        break;
                
                }
            
            $n-=1;
        }
        else{
            if($mapSettings == 'rnd' || $mapSettings->neutralSpawnOnStart == true){
                $startPositions[$i]->contains = spawn($players[0]->faction->tower,0,false,true,false);
            }
        }
    }

    
}

function neutralByLand($land){
    global $json;
    switch($land){
        case 0:
            $json->gamePlayers[0]->faction->t1 = GAME_OBJ['Chest'];
            $json->gamePlayers[0]->faction->t2 = GAME_OBJ['Wolf'];
            $json->gamePlayers[0]->faction->t3 = GAME_OBJ['Ogre'];
            $json->gamePlayers[0]->faction->leader = GAME_OBJ['Dragon'];
            $json->gamePlayers[0]->faction->townhall = GAME_OBJ['Ogre fort'];
            $json->gamePlayers[0]->faction->tower = GAME_OBJ['Bandit outpost'];
            break;
        case 1:
            $json->gamePlayers[0]->faction->t1 = GAME_OBJ['Chest'];
            $json->gamePlayers[0]->faction->t2 = GAME_OBJ['Wolf'];
            $json->gamePlayers[0]->faction->t3 = GAME_OBJ['Ogre'];
            $json->gamePlayers[0]->faction->leader = GAME_OBJ['Dragon'];
            $json->gamePlayers[0]->faction->townhall = GAME_OBJ['Ogre fort'];
            $json->gamePlayers[0]->faction->tower = GAME_OBJ['Bandit outpost'];
            break;
        case 2:
            $json->gamePlayers[0]->faction->t1 = GAME_OBJ['Chest'];
            $json->gamePlayers[0]->faction->t2 = GAME_OBJ['Boar'];
            $json->gamePlayers[0]->faction->t3 = GAME_OBJ['Ogre'];
            $json->gamePlayers[0]->faction->leader = GAME_OBJ['Dragon'];
            $json->gamePlayers[0]->faction->townhall = GAME_OBJ['Ogre fort'];
            $json->gamePlayers[0]->faction->tower = GAME_OBJ['Bandit outpost'];
            break;
        case 3:
            $json->gamePlayers[0]->faction->t1 = GAME_OBJ['Grave'];
            $json->gamePlayers[0]->faction->t2 = GAME_OBJ['Wolf'];
            $json->gamePlayers[0]->faction->t3 = GAME_OBJ['Spider'];
            $json->gamePlayers[0]->faction->leader = GAME_OBJ['Herzog'];
            $json->gamePlayers[0]->faction->townhall = GAME_OBJ['Sacrifice stones'];
            $json->gamePlayers[0]->faction->tower = GAME_OBJ['Bandit outpost'];
            break;
        default:
            $json->gamePlayers[0]->faction->t1 = GAME_OBJ['Chest'];
            $json->gamePlayers[0]->faction->t2 = GAME_OBJ['Wolf'];
            $json->gamePlayers[0]->faction->t3 = GAME_OBJ['Ogre'];
            $json->gamePlayers[0]->faction->leader = GAME_OBJ['Dragon'];
            $json->gamePlayers[0]->faction->townhall = GAME_OBJ['Ogre fort'];
            $json->gamePlayers[0]->faction->tower = GAME_OBJ['Bandit outpost'];
            break;    
    }
}
?>