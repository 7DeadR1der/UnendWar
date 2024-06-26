<?php
// $gameSettings = [
//     "level1" => 5,
//     "level2" => 15,
//     "level3" => 30,
//     "limit_workers" => 6,
//     "limit_army" => 4,
//     "limit_leaders" => 1,
//     "limit_townhalls" => 2,
//     "limit_towers" => 4,
//     "skills" => [
//         ["name" => "Strength I", "description" => "Увеличивает силу атаки Лидера на 1"],
//         ["name" => "Strength II", "require" => "Strength I", "description" => "Увеличивает здоровье Лидера на 2"],
//         ["name" => "Pathfinder", "description" => "Увеличивает скорость Лидера на 1"],
//         ["name" => "Surgery", "description" => "Позволяет Лидеру лечить себя или союзников"],
//         ["name" => "Estates I", "description" => "Единовременно дает 5 золота"],
//         //["name" => "Estates II", "require" => "Estates I", "description" => "Каждый ход дает 1 золото"],
//         ["name" => "Engineering", "description" => "Все здания получают +1 к прочности"]
//     ],
//     "undead_skills" => [
//         ["name" => "Undead I", "description" => "Увеличивает здоровье Лича на 1 ед., зомби получают спсобность 'infect'"],
//         ["name" => "Undead II", "require" => "Undead I", "description" => "Увеличивает здоровье Лича на 1 ед., Лич получает способность 'darkStorm'"]
//     ],
//     "orcs_skills" => [
//         ["name" => "Scavengers", "description" => "Leader получает спсобность 'scavenger'"]  // и Leader
//     ],
//     "elves_skills" => [
//         ["name" => "Lighting", "description" => "Leader получает ..."]
//     ]
// ];
//define("GAME_SETTINGS", $gameSettings);
define("GAME_SETTINGS", json_decode(file_get_contents("./game_settings.JSON"),true));
define("GAME_OBJ", json_decode(file_get_contents("./game_obj.JSON"),true));

class Player{
        public $name,$owner,$live,$bot,$color,$gold,$counts,$level,$exp,$skills,$faction,$statistic,
        $count_workers,$count_army,$count_leaders,$count_townhalls,$count_towers,$count_afk;

        function __construct($name, $num, $faction, $color=false, $bot=false)
        {
            $this->name = $name;
            $this->owner = $num;
            $this->live = true;
            $this->bot = $bot;
            
            $this->color = $color || $color === 0 ? $color : $num;

            $this->gold = 0;
            //$this->food = 0;
            $this->level = 0;
            $this->exp = 0;
            $this->skills = [];
            $this->statistic = new stdClass;
                $this->statistic->winner=0;
                $this->statistic->score=0;
                $this->statistic->goldUp=0;
                $this->statistic->goldDown=0;
                //$this->statistic->foodUp=0;
                //$this->statistic->foodDown=0;
                $this->statistic->workerUp=0;
                $this->statistic->workerDown=0;
                $this->statistic->unitUp=0;
                $this->statistic->unitDown=0;
                $this->statistic->buildUp=0;
                $this->statistic->buildDown=0;
                $this->statistic->leaderUp=0;
                $this->statistic->leaderDown=0;

            $this->counts = 0;
            $this->count_workers = 0;
            $this->count_army = 0;
            $this->count_leaders = 0;
            $this->count_townhalls = 0;
            $this->count_towers = 0;
            $this->count_afk = 0;

            switch ($faction){
                case "Kingdom":
                    $this->faction = new Kingdom();
                    $varGold = 2;
                    $this->gold += $varGold;
                    $this->statistic->goldUp+=$varGold;
                    break;
                case "SeaMercs":
                    $this->faction = new SeaMercs();
                    break;
                case "Undead":
                    $this->faction = new Undead();
                    break;
                case "Orcs":
                    $this->faction = new Orcs();
                    break;
                case "Elves":
                    $this->faction = new Elves();
                    break;
                case "Neutral":
                    $this->faction = new Neutral();
                    break;
                default: //random
                    $this->faction = new Kingdom();
                    break;
            }
            //$this->faction = $faction;
        }
}
class Unit{
    public $type,$class,$name,$hpMax,$hp,$attack,$movePoint,$range,$price,$ability,
    $canMove,$canAction,$owner,$image,$out,$require;
    function __construct($array, $owner, $action)
    {
        $this->type = $array[0];
        $this->class = $array[1];
        $this->name = $array[2];
        $this->hpMax = $array[3];
        $this->hp = $array[3];
        $this->attack = $array[4];
        $this->movePoint = $array[5];
        $this->range = $array[6];
        $this->price = $array[7];
        //$this->food = $array[8];
        $this->ability = $array[8];
        $this->canMove = $action;
        $this->canAction = $action;
        $this->owner = $owner;
        $this->image = $array[9];
        $this->out = $array[10];
        $this->require = $array[11];
    }
}

// -----  Fields  -----
class Cell {
    public $contains,$resCount,$availability,$obstacle,$row,$column,$view;
    function __construct($i,$j)
    {
        $this->contains = false;
        $this->resCount = 0;
        $this->availability = true;
        $this->obstacle = 0;
        $this->row = $i;
        $this->column = $j;
        $this->view = false;
    }
}
// ----- Factions -----
class Kingdom {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;

    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)foodprice-(9)ability-(10)image-(11)outgoing-(12)require
        $this->name = "Kingdom";
        $this->t1 = GAME_OBJ['Peasant'];
        $this->t2 = GAME_OBJ['Scout'];
        $this->t3 = GAME_OBJ['Knight'];
        $this->leader = GAME_OBJ['Lord'];
        $this->townhall = GAME_OBJ['Townhall'];
        $this->tower = GAME_OBJ['Tower'];
    }
}

class SeaMercs {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;

    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)foodprice-(9)ability-(10)image-(11)outgoing-(12)require
        $this->name = "SeaMercs";
        $this->t1 = GAME_OBJ['Slave'];
        $this->t2 = GAME_OBJ['Raider'];
        $this->t3 = GAME_OBJ['Merc'];
        $this->leader = GAME_OBJ['Berserk'];
        $this->townhall = GAME_OBJ['Forge'];
        $this->tower = GAME_OBJ['Outpost'];
    }
}

class Undead {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;

    function __construct()
    {
        $this->name = "Undead";
        $this->t1 = GAME_OBJ['Cultist'];
        $this->t2 = GAME_OBJ['Zombie'];
        $this->t3 = GAME_OBJ['Ghost'];
        $this->leader = GAME_OBJ['Lich'];
        $this->townhall = GAME_OBJ['Necropolis'];
        $this->tower = GAME_OBJ['Soul tower'];
    }
}
class Orcs {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;
    //not work
    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)foodprice-(9)ability-(10)image-(11)outgoing-(12)require
        $this->name = "Orcs";
        $this->t1 = GAME_OBJ['Goblin'];
        $this->t2 = GAME_OBJ['Battler'];
        $this->t3 = GAME_OBJ['Onager'];
        $this->leader = GAME_OBJ['War Boss'];
        $this->townhall = GAME_OBJ['Warhouse'];
        $this->tower = GAME_OBJ['Turret'];
    }
}
class Elves {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;
    function __construct(){
        $this->name = "Elves";
        $this->t1 = GAME_OBJ['Wind'];
        $this->t2 = GAME_OBJ['Treant'];
        $this->t3 = GAME_OBJ['Pegas'];
        $this->leader = GAME_OBJ['Ihasabia'];
        $this->townhall = GAME_OBJ['Temple'];
        $this->tower = GAME_OBJ['Torre'];
    
    }
}
class Neutral {
    public $name, $t1, $t2, $t3, $leader, $townhall, $tower;

    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)foodprice-(9)ability-(10)image-(11)outgoing-(12)require
        $this->name = "Neutral";
        $this->t1 = GAME_OBJ['Chest'];
        $this->t2 = GAME_OBJ['Wolf'];
        $this->t3 = GAME_OBJ['Ogre'];
        $this->leader = GAME_OBJ['Dragon'];
        $this->townhall = GAME_OBJ['Ogre fort'];
        $this->tower = GAME_OBJ['Bandit outpost'];
    }
}
/*
$gameObjs = [
    // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)foodprice-(9)ability-(10)image-(11)outgoing-(12)require
        
    //Kingdom
        "Peasant" => ['unit','t1','Peasant',1,1,1,1,0,['worker'],'img/units/Kingdom/Peasant.png','',''],
        "Scout" => ['unit','t2','Scout',1,1,2,1,1,[],"img/units/Kingdom/Scout.png",'',''],
        "Knight" => ['unit','t3','Knight',3,1,1,1,2,[],"img/units/Kingdom/Knight.png",'',''],
        "Lord" => ['unit','leader','Lord',4,1,2,1,6,['cavalryStrike'],"img/units/Kingdom/Lord.png",'','2t'],
        "Townhall" => ['building','townhall','Townhall',5,0,0,0,4,['hire'],'img/units/Kingdom/Townhall.png','t1-t2',''],
        "Tower" => ['building','tower','Tower',3,1,0,2,3,['hire'],"img/units/Kingdom/Tower.png",'t3-leader',''],
    //SeaMercs
        "Slave" => ['unit','t1','Slave',1,1,1,1,0,['worker'],'img/units/SeaMercs/Slave.png','',''],
        "Raider" => ['unit','t2','Raider',1,1,2,1,1,[],"img/units/SeaMercs/Raider.png",'',''],
        "Merc" => ['unit','t3','Merc',2,1,1,1,1,['pillage','mercPath'],"img/units/SeaMercs/Merc.png",'',''],
        "Berserk" => ['unit','leader','Berserk',3,1,1,1,4,['bloodAxe'],"img/units/SeaMercs/Berserk.png",'','2t'],
        "Forge" => ['building','townhall','Forge',5,0,0,0,4,['hire','smith'],'img/units/SeaMercs/Forge.png','t1-t2',''],
        "Outpost" => ['building','tower','Outpost',3,1,0,2,3,['hire'],"img/units/SeaMercs/Outpost.png",'t3-leader',''],

    //Undead
        "Cultist" => ['unit','t1','Cultist',1,1,1,1,0,['worker'],'img/units/Undead/Cultist.png','',''],
        "Zombie" => ['unit','t2','Zombie',2,1,1,1,1,[],"img/units/Undead/Zombie.png",'',''],
        "Ghost" => ['unit','t3','Ghost',2,1,2,1,3,['vampir'],"img/units/Undead/Ghost.png",'',''],
        "Lich" => ['unit','leader','Lich',2,1,1,1,3,['darkArmy'],"img/units/Undead/Lich.png",'',''],
        "Necropolis" => ['building','townhall','Necropolis',5,0,0,0,4,['hire'],'img/units/Undead/Necropolis.png','t1-t2',''],
        "Soul tower" => ['building','tower','Soul tower',3,1,0,2,3,['hire'],"img/units/Undead/Soul tower.png",'t3-leader',''],
        "Skeleton" => ['unit','t1','Skeleton',1,1,1,1,0,[],'img/units/Undead/Skeleton.png','',''],
    //Orcs
        "Goblin" => ['unit','t1','Goblin',1,1,1,1,0,['worker'],'img/units/Orcs/Goblin.png','',''],
        "Battler" => ['unit','t2','Battler',2,1,1,1,1,[],"img/units/Orcs/Battler.png",'',''],
        "Onager" => ['building','t3','Onager',1,1,1,2,4,['siegeDmg'],"img/units/Orcs/Onager.png",'',''],
        "War Boss" => ['unit','leader','War Boss',4,1,1,1,4,[],"img/units/Orcs/War Boss.png",'','2t'],
        "Warhouse" => ['building','townhall','Warhouse',5,0,0,0,4,['hire'],'img/units/Orcs/Warhouse.png','t1-t3',''],
        "Turret" => ['building','tower','Turret',2,1,0,2,3,['hire'],"img/units/Orcs/Turret.png",'t2-leader',''],

    //Elves
        "Wind" => ['unit','t1','Wind',1,0,1,0,0,['worker','ethereal','growUp'],'img/units/Elves/Wind.png','',''],
        "Treant" => ['unit','t2','Treant',2,1,1,1,0,[],"img/units/Elves/Treant.png",'',''],
        "Pegas" => ['unit','t3','Pegas',2,2,3,1,4,[],"img/units/Elves/Pegas.png",'',''],
        "Ihasabia" => ['unit','leader','Ihasabia',2,1,1,1,3,['farSight','magic'],"img/units/Elves/Ihasabia.png",'','2t'],
        "Temple" => ['building','townhall','Temple',5,0,0,0,4,['hire'],'img/units/Elves/Temple.png','t1-t2-wachief',''],
        "Torre" => ['building','tower','Torre',3,1,0,2,3,['hire'],"img/units/Elves/Torre.png",'t3',''],
    
    //Neutral

        "Chest" => ['building','t1','Chest',2,0,0,0,0,['treasure','object'],'img/units/Neutral/Chest.png','',''],
        "Wolf" => ['unit','t2','Wolf',2,1,1,1,1,[],"img/units/Neutral/Wolf.png",'',''],
        "Ogre" => ['unit','t3','Ogre',4,1,1,1,3,[],"img/units/Neutral/Ogre.png",'',''],
        "Dragon" => ['unit','leader','Dragon',7,2,2,1,10,['monster'],"img/units/Neutral/Dragon.png",'','2t'],
        "Ogre fort" => ['building','townhall','Ogre fort',6,1,0,1,6,['prison'],'img/units/Neutral/Ogre fort.png','',''],
        "Bandit outpost" => ['building','tower','Bandit outpost',3,1,0,1,3,[],"img/units/Neutral/Bandit outpost.png",'',''],

];*/



/*==================================================================== */
/*=== FUNCTIONS === */
/*==================================================================== */
function setGold($owner, $type, $count){
    global $json;
    //$pl = $json->gamePlayers[$owner];
    if($type == '+'){
        $json->gamePlayers[$owner]->gold += $count;
        scoring($owner,0,'gold','Up',$count);
    }else if($type == '-'){
        $json->gamePlayers[$owner]->gold -= $count;
        scoring($owner,0,'gold','Down',$count);
    }else{
        echo response(0,"error type");
    }
    //$json->gamePlayers[$owner] = $pl;
    //$d = $json->gamePlayers;
    //$json->gamePlayers = (array) $d;
}

function scoring($owner,$scorePoints=0,$type=false,$set=false,$number=false){
    global $json;
    if($type != false && $set != false && $number != false){
        $string = $type.$set;
        $json->gamePlayers[$owner]->statistic->$string += $number;
    }
    $json->gamePlayers[$owner]->statistic->score += $scorePoints;
}

function spawn(array $unit, int $owner, bool $payable = true, bool $action = false, bool $limit = true){
    //global $gameSettings;
    global $json;
        if($limit == true){
            $count=countCalc($json->gameField,$unit[1],$owner);
            //$checkLimit = ($limit == true) ? false : true;
            $checkLimit = false;
            switch($unit[1]){
                case't1':
                    if($count<GAME_SETTINGS["limit_workers"]){
                        $checkLimit = true;
                    }
                    break;
                case 't2':
                    if($count<GAME_SETTINGS["limit_army"]){
                        $checkLimit = true;
                    }
                    break;
                case 't3':
                    if($count<GAME_SETTINGS["limit_army"]){
                        $checkLimit = true;
                    }
                    break;
                case 'leader':
                    if($count<GAME_SETTINGS["limit_leaders"]){
                        if($unit[11]=='2t'){
                            $countTowers=countCalc($json->gameField,'tower',$owner);
                            if($countTowers>=2){
                                $checkLimit = true;
                            }//else alert('Для Лидера нужно 2 башни');
                        }else {
                            $checkLimit = true;
                        }
                    }
                    break;
                case 'townhall':
                    if($count<GAME_SETTINGS["limit_townhalls"]){
                        $checkLimit=true;
                    }
                    break;
                case 'tower':
                    if($count<GAME_SETTINGS["limit_towers"]){
                        $checkLimit=true;
                    }
                    break;
                default:
                    //alert('ошибка в выборе юнита');
                    break;
            }
        }else{
            $checkLimit=true;
        }
        if($checkLimit==true){
            if($payable == false || ($json->gamePlayers[$owner]->gold>=$unit[7])){// && $json->gamePlayers[$owner]->food>=$unit[8]
                if($payable == true){
                    setGold($owner,'-',$unit[7]);
                    //setFood($owner,'-',$unit[9]);
                }
                //$json->gamePlayers[$player]->gold-=$unit[7];
                if($unit[0] == "unit"){
                    if($unit[1] == "leader"){
                        scoring($owner,$unit[7],"leader","Up",1);
                    }else if($unit[1] == "t1"){
                        scoring($owner,$unit[7],"worker","Up",1);
                    }else{
                        scoring($owner,$unit[7],"unit","Up",1);
                    }
                }else{
                    scoring($owner,$unit[7],"build","Up",1);
                }
                return new Unit($unit,$owner,$action);
            }
        }else{
            return false;
        }
}

function countCalc($gf,$class,$owner){
    $num=0;
    for($i=0;$i<count($gf);$i++){
        for($j=0;$j<count($gf[$i]);$j++){
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


/*==================================================================== */
/*=== ACTION FUNCTION === */
/*==================================================================== */
function action(int $fi, int $fj, $btn, int $si, int $sj, $param){
    //global GAME_SETTINGS;
    global $json;
    $iMax = $json->gameSize[0];
    $jMax = $json->gameSize[1];
    $player = $json->gameField[$fi][$fj]->contains->owner;
    $fUnit = &$json->gameField[$fi][$fj]->contains;
    $sUnit = &$json->gameField[$si][$sj]->contains;
    $animType = '';
    $animVariant = '';
    if($fUnit!=false && ($fUnit->owner==$json->gameTurn || $fUnit->owner == 0)){
        switch($btn){
            case'':
                if($sUnit == false && $json->gameField[$si][$sj]->obstacle == 0){
                    //move
                    $speed = $fUnit->movePoint;
                    if(in_array('rush',$fUnit->ability)){
                        $speed += 1;
                        $search = array_search('rush',$fUnit->ability);
                        if($search !== false){
                            array_splice($fUnit->ability,$search,1);
                        }
                    }
                    if($fUnit->canMove==true && (abs($fi-$si)+abs($fj-$sj))<=$speed){
                        $sUnit = clone $fUnit;
                        $sUnit->canMove = false;
                        $fUnit = false;
                        $animType = 'move';
                        $animVariant = '';

                    }
                }else if($sUnit->owner!=$fUnit->owner){
                    //attack
                    //new
                    if($fUnit->canAction == true && (abs($fi-$si)+abs($fj-$sj))<=$fUnit->range){
                        if(in_array('object',$sUnit->ability)){
                            if((abs($fi-$si)+abs($fj-$sj))<=1){
                                //open chest
                                $sUnit->hp -= 1;
                                if($sUnit->hp <= 0){
                                    kill($fi,$fj,$si,$sj);
                                }
                                $fUnit->canAction=false;
                                $animType = 'move';
                                $animVariant = '';
                            }

                        }else if(in_array('magic',$fUnit->ability)){
                            //magic hit
                            //magicHit();
                            $animType = 'magic';
                            $animVariant = '';
                            if(in_array('chainLight', $fUnit->ability)){
                                
                                $animType = 'chainLight';
                                $arrayCells = [];
                                $arrayDirect = [];
                                if($si+1<$iMax &&$json->gameField[$si+1][$sj]->contains!=false && $json->gameField[$si+1][$sj]->contains->owner != $fUnit->owner){
                                    array_push($arrayDirect,'down');
                                    $arrayCells[$arrayDirect[count($arrayDirect)-1]] = &$json->gameField[$si+1][$sj];
                                }
                                if($si-1>-1&&$json->gameField[$si-1][$sj]->contains!=false && $json->gameField[$si-1][$sj]->contains->owner != $fUnit->owner){
                                    array_push($arrayDirect,'up');
                                    $arrayCells[$arrayDirect[count($arrayDirect)-1]] = &$json->gameField[$si-1][$sj];
                                }
                                if($sj+1<$jMax&&$json->gameField[$si][$sj+1]->contains!=false && $json->gameField[$si][$sj+1]->contains->owner != $fUnit->owner){
                                    array_push($arrayDirect,'right');
                                    $arrayCells[$arrayDirect[count($arrayDirect)-1]] = &$json->gameField[$si][$sj+1];
                                }
                                if($sj-1>-1&&$json->gameField[$si][$sj-1]->contains!=false && $json->gameField[$si][$sj-1]->contains->owner != $fUnit->owner){
                                    array_push($arrayDirect,'left');
                                    $arrayCells[$arrayDirect[count($arrayDirect)-1]] = &$json->gameField[$si][$sj-1];
                                }
                                if(count($arrayCells)>0){
                                    shuffle($arrayDirect);
                                    $direct = $arrayDirect[0];
                                    $animVariant = $direct;
                                    $n = $arrayCells[$direct]->row;
                                    $m = $arrayCells[$direct]->column;
                                    magicHit($fi,$fj,$n,$m,1);
                                }
                            }
                            magicHit($fi,$fj,$si,$sj);
                            $fUnit->canAction=false;

                        }else {
                            //default phys attack
                            hit($fi,$fj,$si,$sj);
                            $fUnit->canAction=false;
                            $animType = 'attack';
                            $animVariant = '';
                        }
                    }

                }else if($sUnit->owner==$fUnit->owner){
                    //heal
                    if(in_array('surgery',$fUnit->ability)){
                        if($fUnit->canAction==true &&
                        $sUnit->hpMax > $sUnit->hp
                        && (abs($fi-$si)+abs($fj-$sj))<=1 && $sUnit->type == 'unit' 
                        /*&& ($json->gameField[$si][$sj]->contains->hp < ceil($json->gameField[$si][$sj]->contains->hpMax/2) &&
                        $json->gameField[$si][$sj]->contains->hp < 3 ||  $json->gameField[$si][$sj]->contains->hp == 1)*/

                        ){
                            $sUnit->hp += 1;
                            $fUnit->canAction = false;
                            $animType = 'heal';
                            $animVariant = 'other';
                        }
                    }else if(in_array('sacrifice',$fUnit->ability)){
                        if($fUnit->canAction == true && $sUnit->hpMax > $sUnit->hp
                        && (abs($fi-$si)+abs($fj-$sj))<=1 && $sUnit->type == 'unit'){
                            $sUnit->hp+=1;
                            $fUnit = false;
                        }
                    }
                }
                break;
            case'build':
                if(in_array('worker',$fUnit->ability)){
                    if($fUnit->canAction==true){
                        switch($param){
                            case'townhall':
                                    $building=$json->gamePlayers[$player]->faction->townhall;
                                break;
                            case'tower':
                                    $building=$json->gamePlayers[$player]->faction->tower;
                                break;
                            default:
                                echo response(0,'error param');
                                break;
                        }
                        if($json->gameField[$fi][$fj]->resCount<=0){
                            $spawn = spawn($building,$player);
                            if($spawn != false){
                                $fUnit = $spawn;
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
                if(in_array('hire',$fUnit->ability)){
                    $array=explode('-',$fUnit->out);
                    if(in_array($param,$array)){
                        if($fUnit->canAction==true && (abs($fi-$si)+abs($fj-$sj))<=1){
                            $cell=&$json->gameField[$si][$sj];
                            if($cell->contains==false&&$cell->obstacle==0){
                                $unit = false;
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
                                    case 'leader':
                                        $unit = $json->gamePlayers[$player]->faction->leader;
                                        break;
                                    default:
                                        //alert('ошибка в выборе юнита');
                                        break;
                                }
                                $spawn = spawn($unit,$player);
                                if($spawn != false){
                                    $cell->contains = $spawn;
                                    $fUnit->canAction = false;
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
                                            if($json->gameField[$si][$sj]->contains->class == "leader"){
                                                scoring($player,$json->gameField[$si][$sj]->contains->price,"leader","Up",1);
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
                if(in_array('surgery',$fUnit->ability)){
                    if($fUnit->canAction==true){
                        if($fUnit->hp < $fUnit->hpMax
                            /*(*///$fUnit->hp < ceil($fUnit->hpMax/2)
                            /* &&
                            $json->gameField[$fi][$fj]->contains->hp<3) || 
                            ($json->gameField[$fi][$fj]->contains->hp==1 && 
                            $json->gameField[$fi][$fj]->contains->hp<$json->gameField[$fi][$fj]->contains->hpMax)*/
                        ){
                            $fUnit->canAction =false;
                            $fUnit->hp+=1;
                            if(!in_array('weak',$fUnit->ability)){
                                array_push($fUnit->ability,'weak');
                            }
                            $animType = 'heal';
                            $animVariant = 'self';
                        }
                    }
                }
                break;
            case'darkPact':
                if(in_array('darkPact',$fUnit->ability)){
                    if($fUnit->canAction==true && (abs($fi-$si)+abs($fj-$sj))<=1 
                    && $sUnit->type == 'unit' && $fUnit->owner==$sUnit->owner){
                        
                        $fUnit->canAction =false;
                        if($fUnit->hp<$fUnit->hpMax){
                            $fUnit->hp += 1;
                        }
                        kill($fi,$fj,$si,$sj);
                        $animType = 'darkPact';
                        $animVariant = '';
                    }
                }
                break;
            case'darkArmy':
                if(in_array('darkArmy',$fUnit->ability)){
                    if($fUnit->canAction==true){
                        $limitT1=countCalc($json->gameField,'t1',$player);
                        if($limitT1<GAME_SETTINGS["limit_workers"]){
                            
                            $arrayCells = [false,false,false,false];
                            if($fi-1>-1&&$json->gameField[$fi-1][$fj]->contains==false&&$json->gameField[$fi-1][$fj]->obstacle==0){
                                //array_push($arrayCells,$json->gameField[$fi-1][$fj]);
                                $arrayCells[0] = $json->gameField[$fi-1][$fj];
                                //array_push($arrayCells,$topCell);
                            }
                            if($fj+1<$iMax&&$json->gameField[$fi][$fj+1]->contains==false&&$json->gameField[$fi][$fj+1]->obstacle==0){
                                //array_push($arrayCells,$json->gameField[$fi][$fj+1]);
                                $arrayCells[1] = $json->gameField[$fi][$fj+1];
                                //array_push($arrayCells,$rgtCell);
                            } 
                            if($fi+1<$jMax&&$json->gameField[$fi+1][$fj]->contains==false&&$json->gameField[$fi+1][$fj]->obstacle==0){
                                //array_push($arrayCells,$json->gameField[$fi+1][$fj]);
                                $arrayCells[2] = $json->gameField[$fi+1][$fj]; 
                                //array_push($arrayCells,$botCell);
                            }
                            if($fj-1>-1&&$json->gameField[$fi][$fj-1]->contains==false&&$json->gameField[$fi][$fj-1]->obstacle==0){
                                //array_push($arrayCells,$json->gameField[$fi][$fj-1]);
                                $arrayCells[3] = $json->gameField[$fi][$fj-1];
                                //array_push($arrayCells,$lftCell);
                            }
                            $fff = [$si-$fi,$sj-$fj];
                            switch([$si-$fi,$sj-$fj]){
                                case [-1,0]://top
                                    break;
                                case [0,1]://right
                                    array_push($arrayCells,array_shift($arrayCells));
                                    break;
                                case [1,0]://bot
                                    array_push($arrayCells,array_shift($arrayCells));
                                    array_push($arrayCells,array_shift($arrayCells));
                                    break;
                                case [0,-1]://left
                                    array_push($arrayCells,array_shift($arrayCells));
                                    array_push($arrayCells,array_shift($arrayCells));
                                    array_push($arrayCells,array_shift($arrayCells));
                                    break;
                                default:
                                    break;
                            }


                            if(count($arrayCells)>0){
                                $count=2;
                                if(in_array('Undead Horde', $json->gamePlayers[$player]->skills)){
                                    $unit = GAME_OBJ["Skeleton warrior"];
                                }else{
                                    $unit = GAME_OBJ["Skeleton"];
                                }
                                //$count=countCalc($json->gameField,'townhall',$player);
                                /*if(in_array('Undead I',$json->gamePlayers[$player]->skills)){
                                    $count=countCalc($json->gameField,'townhall',$player);
                                }*/
                                //$count+=1;
                                if(GAME_SETTINGS["limit_workers"]<=$limitT1+$count){
                                    $count = $count-abs(GAME_SETTINGS["limit_workers"]-($count+$limitT1));
                                }
                                $k=0;
                                while($count>0){
                                    if(count($arrayCells)>0){
                                        if(isset($arrayCells[$k]) && $arrayCells[$k]!= false){
                                            $ti = $arrayCells[$k]->row;
                                            $tj = $arrayCells[$k]->column;
                                            $spawn = spawn($unit,$player,false);
                                            if($spawn != false){
                                                //only 1 skeleton warrior spawn
                                                $unit = GAME_OBJ["Skeleton"];
                                                $count--;
                                                $json->gameField[$ti][$tj]->contains = $spawn;
                                                $fUnit->canAction =false;
                                                $animType = 'darkArmy';
                                                $animVariant = '';
                                            }
                                        }
                                    }
                                    $k++;
                                    if($k>5){
                                        $count = 0;
                                    }
                                    /*
                                //for($k=0;$k<$count;$k++){
                                    if(count($arrayCells)>0){
                                        $n=rand(0,count($arrayCells)-1);
                                        if(isset($arrayCells[$n])){
                                            $ti = $arrayCells[$n]->row;
                                            $tj = $arrayCells[$n]->column;
                                            $spawn = spawn($unit,$player,false);
                                            if($spawn != false){
                                                $json->gameField[$ti][$tj]->contains = $spawn;
                                                $fUnit->canAction =false;
                                                array_splice($arrayCells,$n,1);
                                                $animType = 'darkArmy';
                                                $animVariant = '';
                                            }
                                            //$json->gameField[$ti][$tj]->contains = spawn($unit,$player,false);//new Unit($unit,$player,false);
                                            //scoring($player,$json->gameField[$si][$sj]->contains->price,"worker","Up",1);
                                            
                                            
                                        }
                                    }*/
                                }
                            }
                        }
                    }
                };
                break;
            case'darkBolt':
                if(in_array('darkBolt',$fUnit->ability)){
                    if($fUnit->canAction==true && (abs($fi-$si)+abs($fj-$sj))<=2 
                    && $fUnit->owner!=$sUnit->owner){ //&& $sUnit->type == 'unit' 
                        $sClass = $sUnit->class == 'leader' ? true: false;
                        
                        $fUnit->canAction = false;
                        if(!in_array('weak',$sUnit->ability)){
                            array_push($sUnit->ability,'weak');
                        }
                        $result = magicHit($fi,$fj,$si,$sj,1);
                        if($result==true && $sClass==true){
                            $fUnit->hpMax += 1;
                            $fUnit->hp += 1;
                        }
                        $animType = 'darkPact';
                        $animVariant = '';
                    }
                }
                break;
            case'darkStorm':
                if(in_array('darkStorm',$fUnit->ability)){
                    if($fUnit->canAction==true && $fUnit->canMove==true){
                        $hp = 0;
                        $arrayCells = [];
                        $fUnit->canAction=false;
                        $fUnit->canMove=false;
                        if($fi+1<$iMax&&$json->gameField[$fi+1][$fj]->contains!=false) 
                            array_push($arrayCells,$json->gameField[$fi+1][$fj]);
                        if($fi-1>-1&&$json->gameField[$fi-1][$fj]->contains!=false)
                            array_push($arrayCells,$json->gameField[$fi-1][$fj]);
                        if($fj+1<$jMax&&$json->gameField[$fi][$fj+1]->contains!=false) 
                            array_push($arrayCells,$json->gameField[$fi][$fj+1]);
                        if($fj-1>-1&&$json->gameField[$fi][$fj-1]->contains!=false)
                            array_push($arrayCells,$json->gameField[$fi][$fj-1]);
                        for($i=0;$i<count($arrayCells);$i++){
                            if($arrayCells[$i]->contains->type == 'unit' && $fUnit->hp < $fUnit->hpMax){
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
                        if($fUnit->hp + $hp <= $fUnit->hpMax){
                            $fUnit->hp += $hp;
                        }else{
                            $fUnit->hp = $fUnit->hpMax;
                        }
                        $animType = 'darkStorm';
                        $animVariant = '';

                    }
                };
                break;
            case 'smith':
                if(in_array('smith',$fUnit->ability) && !in_array('armor',$sUnit->ability)){
                    if($fUnit->canAction==true && (abs($fi-$si)+abs($fj-$sj))<=1 
                    && $sUnit->type == 'unit' && $json->gamePlayers[$player]->gold>0){
                        
                        $fUnit->canAction =false;
                        setGold($player,'-',1);
                        scoring($player,1);
                        //$json->gamePlayers[$player]->scoring($player,1);
                        array_push($sUnit->ability,'armor');
                        $animType = 'smith';
                        $animVariant = '';
                    }
                }
                break;
            case 'growUp':
                if(in_array('growUp',$fUnit->ability)){
                    if($fUnit->canAction==true){
                        $cell=$json->gameField[$fi][$fj];
                        if($cell->obstacle == 0){
                            $spawn = spawn(GAME_OBJ["Treant"],$fUnit->owner);
                            if($spawn != false){
                                $fUnit = $spawn;
                                $fUnit->hp = 1;
                                array_push($fUnit->ability,'healing');
                                $animType = 'heal';
                                $animVariant = 'self';
                            }

                        }
                    }
                }
                break;
            case 'teleport':
                if(in_array('teleport',$fUnt->ability) && $sUnit == false && $json->gameField[$si][$sj]->obstacle == 0){
                    //move
                    $checkBuilding = false;
                    $arrayCells = [];
                    if($si+1<$json->gameSize[0])array_push($arrayCells,$json->gameField[$si+1][$sj]);
                    if($sj+1<$json->gameSize[1])array_push($arrayCells,$json->gameField[$si][$sj+1]);
                    if($si-1>-1)array_push($arrayCells,$json->gameField[$si-1][$sj]);
                    if($sj-1>-1)array_push($arrayCells,$json->gameField[$si][$sj-1]);
                    foreach ($arrayCells as $key => $value) {
                        if($value->contains!=false && $value->contains->type == 'building' && $value->contains->owner == $fUnit->owner){
                            $checkBuilding = true;
                        }
                    }
                    if($fUnit->canAction==true && $checkBuilding==true){
                        $sUnit = clone $fUnit;
                        $sUnit->canAction = false;
                        $fUnit = false;
                        $search = array_search('teleport',$sUnit->ability);
                        if($search !== false){
                            array_splice($sUnit->ability,$search,1);
                        }
                        $animType = 'move';
                        $animVariant = '';

                    }
                }
                break;
            case'lifeLocket':
                if(in_array('lifeLocket',$fUnit->ability)){
                    if($fUnit->canAction==true){
                        $arrayCells = [];
                        $fUnit->canAction=false;
                        $search = array_search('lifeLocket',$fUnit->ability);
                        if($search !== false){
                            array_splice($fUnit->ability,$search,1);
                        }
                        if($fi+1<$iMax&&$json->gameField[$fi+1][$fj]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi+1][$fj]);} 
                        if($fi-1>-1&&$json->gameField[$fi-1][$fj]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi-1][$fj]);} 
                        if($fj+1<$jMax&&$json->gameField[$fi][$fj+1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi][$fj+1]);} 
                        if($fj-1>-1&&$json->gameField[$fi][$fj-1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi][$fj-1]);} 
                        if($fi+2<$iMax&&$json->gameField[$fi+2][$fj]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi+2][$fj]);} 
                        if($fj+2<$jMax&&$json->gameField[$fi][$fj+2]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi][$fj+2]);} 
                        if($fi-2>-1&&$json->gameField[$fi-2][$fj]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi-2][$fj]);} 
                        if($fj-2>-1&&$json->gameField[$fi][$fj-2]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi][$fj-2]);} 
                        if($fi+1<$iMax&&$fj+1<$jMax&&$json->gameField[$fi+1][$fj+1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi+1][$fj+1]);} 
                        if($fi+1<$iMax&&$fj-1>-1&&$json->gameField[$fi+1][$fj-1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi+1][$fj-1]);} 
                        if($fi-1>-1&&$fj+1<$jMax&&$json->gameField[$fi-1][$fj+1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi-1][$fj+1]);} 
                        if($fi-1>-1&&$fj-1>-1&&$json->gameField[$fi-1][$fj-1]->contains!=false){
                            array_push($arrayCells,$json->gameField[$fi-1][$fj-1]);} 

                        for($l=0;$l<count($arrayCells);$l++){
                            if($arrayCells[$l]->contains->type == 'unit' && $arrayCells[$l]->contains->owner == $player){
                                if($arrayCells[$l]->contains->hp<$arrayCells[$l]->contains->hpMax){
                                    $arrayCells[$l]->contains->hp += 1;
                                    array_push($arrayCells[$l],'healing');
                                    //maybe 'healing'
                                }
                            }
                        }
                        if($fUnit->type=='unit' && $fUnit->hp<$fUnit->hpMax){
                            $fUnit += 1;
                        }
                        
                        $animType = 'lifeLocket';
                        $animVariant = '';
                    }
                }
                break;
            case'spell':
                break;
            case'delete':
                if($fUnit->type=='building' && $fUnit->canAction==true)
                    $fUnit = false;
                break;
            default:
                break;
        }
        $json->animation = [$fi,$fj,$animType,$si,$sj,$animVariant];
        return true;
    }

}
function hit($fi,$fj,$si,$sj,$dmg=false){
    global $json;

    $fUnit = &$json->gameField[$fi][$fj]->contains;
    $sUnit = &$json->gameField[$si][$sj]->contains;
    $atk = $dmg ? $dmg : $fUnit->attack;
    //atk mod
    if(in_array('cavalryStrike',$fUnit->ability)&&$fUnit->canMove==false){
        $atk+=1;
    }
    if(in_array('siegeDmg',$fUnit->ability) && $sUnit->type == 'building'){
        $atk+=1;
    }
    if(in_array('weak',$sUnit->ability)){
        $atk+=1;
        $search = array_search('weak',$sUnit->ability);
        if($search !== false){
            array_splice($sUnit->ability,$search,1);
        }
    }
    if(in_array('sharp',$fUnit->ability)){
        $atk += 1;
        $search = array_search('sharp',$fUnit->ability);
        if($search !== false){
            array_splice($fUnit->ability,$search,1);
        }
    }
    if(in_array('evasion',$sUnit->ability)){
        $atk = 0;
        $search = array_search('evasion',$sUnit->ability);
        if($search !== false){
            array_splice($sUnit->ability,$search,1);
        }
    }
    if($atk>0 && in_array('bloodShield',$sUnit->ability)){
        $atk -= 1;
        $search = array_search('bloodShield',$sUnit->ability);
        if($search !== false){
            array_splice($sUnit->ability,$search,1);
        }

    }
    if($atk>0 && in_array('veteran',$sUnit->ability)){
        if($sUnit->canAction == true){
            $atk -= 1;
            $sUnit->canAction = false;
        }
        /*$chanceArray=[0,0,0,1,1,1,1,1,1,1];
        $s= array_rand($chanceArray);
        if($chanceArray[$s] == 0){
            $atk = 0;
        }*/
    }
    if($atk>0 && in_array('armor',$sUnit->ability)){
        $atk -= 1;
        $search = array_search('armor',$sUnit->ability);
        if($search !== false){
            array_splice($sUnit->ability,$search,1);
        }
    }

    if($atk>0){

        if(in_array('vampir',$fUnit->ability)&&$sUnit->type != 'building'){
            if($fUnit->hp == $fUnit->hpMax){
                if(!in_array('bloodShield', $fUnit->ability)){
                    array_push($fUnit->ability,'bloodShield');
                }
            }else{
                $fUnit->hp+=1;
            }
        }
        if(in_array('toxin',$fUnit->ability)&&$sUnit->type != 'building'){
            if(!in_array('poisoned',$sUnit->ability)){
                array_push($sUnit->ability,'poisoned');
            }
        }
    }
    //atk math
    $sUnit->hp -= $atk;
    if($sUnit->hp <= 0){
        kill($fi,$fj,$si,$sj);
        return true;
    }
    return false;
}
function magicHit($fi,$fj,$si,$sj,$dmg=false){
    global $json;

    $fUnit = &$json->gameField[$fi][$fj]->contains;
    $sUnit = &$json->gameField[$si][$sj]->contains;
    $atk = $dmg ? $dmg : $fUnit->attack;
    //atk mod

    if(in_array('ethereal', $sUnit->ability)){
        $atk += 1;
    }
    if($sUnit->type == 'building'){
        $atk -= 1;
    }
    if(in_array('object', $sUnit->ability)){
        $atk = 0;
    }
    //atk math
    $sUnit->hp -= $atk;
    if($sUnit->hp <= 0){
        kill($fi,$fj,$si,$sj);
        return true;
    }
    return false;
}
/*
function 

/*==================================================================== */
/*=== KILL FUNCTION === */
/*==================================================================== */
function kill($fi,$fj,$si,$sj){
    //global GAME_SETTINGS;
    global $json;
    //global $player;
    $player = $json->gameField[$fi][$fj]->contains->owner;
    $fUnit = &$json->gameField[$fi][$fj]->contains;
    $sUnit = &$json->gameField[$si][$sj]->contains;
    $exp = 0;
    $points = 0;
    $hashUnit = false;
    $killUnit = true;
    if($sUnit->owner!=$player){
        $exp = 1;
        $points = $sUnit->price;
        if(in_array('Scavengers', $json->gamePlayers[$player]->skills)){
            $exp+=1;
        }
    }
    switch($sUnit->type){
        case "unit":
            if($sUnit->class == "leader"){
                scoring($player,$points,"leader","Down",1);
                $exp += $json->gamePlayers[$sUnit->owner]->level + 1;
            }else if($sUnit->class == "t1"){
                scoring($player,$points,"worker","Down",1);
            }
            else{
                scoring($player,$points,"unit","Down",1);
            }
            break;
        case "building":
            scoring($player,$points,"build","Down",1);
            break;
        default:
            break;
    }
    // check onDeath ability
    if(in_array('rebirth', $sUnit->ability) && $killUnit == true){
        $search = array_search('rebirth',$sUnit->ability);
            if($search !== false){
                array_splice($sUnit->ability,$search,1);
            }
        $killUnit = false;
        $sUnit->hp = $sUnit->hpMax;
        array_push($sUnit->ability,'evasion');
    }
    if(in_array('lifeLocket',$sUnit->ability)){
        $search = array_search('lifeLocket',$sUnit->ability);
            if($search !== false){
                array_splice($sUnit->ability,$search,1);
            }
        array_push($fUnit->ability,'lifeLocket');
    }

    if(!in_array('ethereal', $sUnit->ability)){
        if(in_array('bloodAxe',$fUnit->ability)&&$sUnit->type == 'unit'){
            if(!in_array('evasion',$fUnit->ability)){
                array_push($fUnit->ability,'evasion');
            }
            if(!in_array('rush',$fUnit->ability)){
                array_push($fUnit->ability,'rush');
            }
        }
    
        if(in_array('pillage',$fUnit->ability) && $sUnit->type == 'building'){
            setGold($player,'+',1);
        }
        if(in_array('infect',$fUnit->ability)&& $sUnit->type == 'unit' && $killUnit == true){
            $spawn = $sUnit->class == 'leader' ? spawn(GAME_OBJ["Skeleton warrior"],$player,false) : spawn(GAME_OBJ["Skeleton"],$player,false);
            //$spawn = spawn(GAME_OBJ["Skeleton"],$player,false);
            if($spawn != false){
                $hashUnit = $spawn;
                $killUnit = false;
            }
            /*$count=countCalc($json->gameField,'t1',$player);
            if($count<GAME_SETTINGS["limit_workers"]){
                $sUnit = spawn($json->gamePlayers[$player]->faction->t1,$player,false);//new Unit ($json->gamePlayers[$player]->faction->t1,$player,false);
                $killUnit = false;
            }*/
        }
        if(in_array('cannibal',$fUnit->ability)&&$fUnit->hp<$fUnit->hpMax&&$sUnit->type != 'building'){
            $fUnit->hp+=1;
        }
        if(in_array('scavenger',$fUnit->ability)){
            if($sUnit->class == "leader"){
                // if($fUnit->hpMax - $json->gamePlayers[$fUnit->owner]->faction->leader[3] < 1){
                //     $fUnit->hpMax+=1;
                // }
                if($fUnit->hpMax > $fUnit->hp){
                    $fUnit->hp+=1;
                }
                
            }
        }
    }
    if(in_array('mercPath',$fUnit->ability)){
        if($sUnit->type != 'building'){
            if(!in_array('mp1',$fUnit->ability)){
                array_push($fUnit->ability,'mp1');
            }else{
                $search = array_search('mercPath',$fUnit->ability);
                if($search !== false){
                    array_splice($fUnit->ability,$search,1);
                }
                $search = array_search('mp1',$fUnit->ability);
                if($search !== false){
                    array_splice($fUnit->ability,$search,1);
                }
                array_push($fUnit->ability,'veteran');
                if(!in_array('armor',$fUnit->ability)){
                    array_push($fUnit->ability,'armor');
                }
                $fUnit->image = "img/units/SeaMercs/MercUp.png";
            }
        }
    }
    if(in_array('treasure',$sUnit->ability)){
        setGold($player,'+',3);
    }
    if(in_array('grave',$sUnit->ability)){
        setGold($player,'+',2);
        if($killUnit == true){
            $spawn = spawn(GAME_OBJ["Skeleton"],0,false,true,false);
            if($spawn != false){
                $hashUnit = $spawn;
                $killUnit = false;
            }
        }
    }    
    if(in_array('monster',$sUnit->ability)){
        $fUnit->hpMax+=1;
        $fUnit->hp+=1;
        $exp += 3;
    }
    if(in_array('lifeGift',$sUnit->ability)){
        if($fUnit->type == 'unit' && !in_array('rebirth',$fUnit->ability)){
            array_push($fUnit->ability,'rebirth');
        }
    }

    if(in_array('prison',$sUnit->ability)){
        $arrayChance = ['T','T','T','T','T','T','T','G','G','G'];
        $rnd =array_rand($arrayChance);
        $goldValue = 6;
        switch($arrayChance[$rnd]){
            case 'T':
                $rndPl = array_rand($json->gamePlayers);
                $spawn = spawn($json->gamePlayers[$rndPl]->faction->t3,$player,false);
                if($spawn != false && $killUnit == true){
                    $hashUnit = $spawn;
                    setGold($player,'+',$goldValue - $spawn->price);
                    $killUnit = false;
                }
                /*$count=countCalc($json->gameField,'t3',$player);
                if($count<GAME_SETTINGS["limit_army"]){
                    $sUnit = new Unit ($json->gamePlayers[$rndPl]->faction->t3,$player,false);
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
        $sUnit=false;
    }else{
        $sUnit=$hashUnit;
    }

}



/*==================================================================== */
/*=== END TURN FUNCTION === */
/*==================================================================== */
function endTurn(){
    global $json;
    global $fields_json;
    $result = [
        "win"=>[
            "type"=>0,  //0-not win, 1-default win
            "players"=>[],
        ],

    ];
    //before math for replay
    
    $array =[];
    $array["field"] = $json->gameField;
    $array["stats"] = $json->gamePlayers;
    array_push($fields_json,$array);

    $newRound = false;
    $numLast = 0;
    $num=0;

    //new
    do{
        $num=0;
        //print_r($json);
                //check скилла казначейства на +1 голду
        /*if($newRound==true){
            for($i=1;$i<count($json->gamePlayers);$i++){
                if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){            
                    if(in_array('Estates II',$json->gamePlayers[$i]->skills)){
                        setGold($i,'+',1);
                        //$json->gamePlayers[$i]->gold += 1;
                    }
                }
            }
        }*/
        
        $num = checkCountsPlayers();
        $json->gameTurn++;
        $var1=0;
        while((!isset($json->gamePlayers[$json->gameTurn]) || $json->gamePlayers[$json->gameTurn]->live==false) && $var1<16){
            $var1++;
            if($json->gameTurn>=count($json->gamePlayers)){
                $json->gameTurn=0;
                //code for bot and etc
                $newRound=true;
                updateAfterTurn($newRound);
            }
            $json->gameTurn++;
        }if($var1>=16)die("Error, no live players");
        updateAfterTurn($newRound);
        $numLast = $num;
        $num = checkCountsPlayers();
        //check Victory
        //win from domination
        if($json->gameVictoryCond->type==false || $json->gameVictoryCond->classicWin==true){
            if($num==1){
                $result["win"]["type"] = 1;
    
                $owner=0;
                for($i=1;$i<count($json->gamePlayers);$i++){
                    if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
                        $owner=$json->gamePlayers[$i]->owner;
                        array_push($result["win"]["players"],$owner);
                        //echo $owner;
                    }
                }
            }
        }
        if($json->gameVictoryCond->type=='score'){
            for($i=1;$i<count($json->gamePlayers);$i++){
                if($json->gamePlayers[$i]->statistic->score >= $json->gameVictoryCond->condition){
                    $result["win"]["type"] = 1;

                    if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
                        $owner=$json->gamePlayers[$i]->owner;
                        array_push($result["win"]["players"],$owner);
                        //echo $owner;
                    }
                }
            }
        }
        /* after math write for replay
        $array =[];
        $array["field"] = $json->gameField;
        $array["stats"] = $json->gamePlayers;
        array_push($fields_json,$array);
        */
        if($json->gamePlayers[$json->gameTurn]->bot == true){
            botCalculate();
        }
    }while($num>1 && ($json->gamePlayers[$json->gameTurn]->live == false || $json->gamePlayers[$json->gameTurn]->bot == true));

    return $result;
}

function checkCountsPlayers(){
    global $json;
    $num = 0;

    for($i=1;$i<count($json->gamePlayers);$i++){
        if(isset($json->gamePlayers[$i])){
            //
            $json->gamePlayers[$i]->counts = 0;
        }
    }

    for($i=0;$i<count($json->gameField);$i++){
        for($j=0;$j<count($json->gameField[$i]);$j++){
            $cell = $json->gameField[$i][$j];
            //print_r($cell);
            if($cell->contains != false){
                if(isset($json->gamePlayers[$cell->contains->owner])){
                    $json->gamePlayers[$cell->contains->owner]->counts++;
                }
            }
        }
    }
    //checkCountsPlayers();
    for($i=1;$i<count($json->gamePlayers);$i++){
        if(isset($json->gamePlayers[$i]) && $json->gamePlayers[$i]->live!=false){
            if($json->gamePlayers[$i]->counts==0){
                //player lose
                $json->gamePlayers[$i]->live = false;
                //unset($json->gamePlayers[$i]);
            }else{
                $num+=1;
            }
        }
    }
    return $num;

}

function updateAfterTurn($newRound){
    global $json;
    /*
    if($newRound==true){
        for($i=0;$i<count($json->gameField);$i++){
            for($j=0;$j<count($json->gameField[$i]);$j++){
                $cell = $json->gameField[$i][$j];
                if($cell->contains != false && $cell->contains->owner == 0){
                    $cell->contains->canMove = true;
                    $cell->contains->canAction = true;

                    if(in_array('regeneration',$cell->contains->ability) && $cell->contains->hp < $cell->contains->hpMax){
                        $cell->contains->hp +=1;
                    }
                    if($cell->contains->attack>0){
                        $arrUnits=[];
                        if(isset($json->gameField[$i+1][$j]) && $json->gameField[$i+1][$j]->contains != false && $json->gameField[$i+1][$j]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i+1][$j]);}
                        if(isset($json->gameField[$i][$j+1]) && $json->gameField[$i][$j+1]->contains != false && $json->gameField[$i][$j+1]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i][$j+1]);}
                        if(isset($json->gameField[$i-1][$j]) && $json->gameField[$i-1][$j]->contains != false && $json->gameField[$i-1][$j]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i-1][$j]);}
                        if(isset($json->gameField[$i][$j-1]) && $json->gameField[$i][$j-1]->contains != false && $json->gameField[$i][$j-1]->contains->owner != 0)
                            {array_push($arrUnits,$json->gameField[$i][$j-1]);}
                        if(count($arrUnits)>0){
                            $n = mt_rand(0,count($arrUnits)-1);{
                            
                            //$rndUnit = array_rand($arrUnits,1);
                            //var_dump($arrUnits[$n]);
                            //var_dump($rndUnit);
                            $k=$arrUnits[$n]->row;
                            $m=$arrUnits[$n]->column;
                            action($i,$j,'',$k,$m,'');
                            }
                            
                        }
                    }
                }
            }
        }
    }
    */
    
    for($i=0;$i<count($json->gameField);$i++){
        for($j=0;$j<count($json->gameField[$i]);$j++){
            $cell = $json->gameField[$i][$j];
            //print_r($cell);
            if($cell->contains != false){
                if(isset($json->gamePlayers[$cell->contains->owner]) && $json->gamePlayers[$cell->contains->owner]->live!=false){
                    if($cell->contains->owner == $json->gameTurn){
                        $cell->contains->canMove = true;
                        $cell->contains->canAction = true;
                        $arrUnits=[];
                        if(
                            ($cell->contains->owner == 0 && $cell->contains->attack>0 && $cell->contains->range>0) 
                            ||
                            (in_array('deathAura',$cell->contains->ability)))
                            {
                                if(isset($json->gameField[$i+1][$j]) && $json->gameField[$i+1][$j]->contains != false && $json->gameField[$i+1][$j]->contains->owner != $cell->contains->owner)
                                    {array_push($arrUnits,$json->gameField[$i+1][$j]);}
                                if(isset($json->gameField[$i][$j+1]) && $json->gameField[$i][$j+1]->contains != false && $json->gameField[$i][$j+1]->contains->owner != $cell->contains->owner)
                                    {array_push($arrUnits,$json->gameField[$i][$j+1]);}
                                if(isset($json->gameField[$i-1][$j]) && $json->gameField[$i-1][$j]->contains != false && $json->gameField[$i-1][$j]->contains->owner != $cell->contains->owner)
                                    {array_push($arrUnits,$json->gameField[$i-1][$j]);}
                                if(isset($json->gameField[$i][$j-1]) && $json->gameField[$i][$j-1]->contains != false && $json->gameField[$i][$j-1]->contains->owner != $cell->contains->owner)
                                    {array_push($arrUnits,$json->gameField[$i][$j-1]);}

                            }
                        

                        if($cell->contains->owner == 0){ //neutraal block
                            if(count($arrUnits)>0 && $cell->contains->attack>0 && $cell->contains->range>0){
                                $n = mt_rand(0,count($arrUnits)-1);{
                                //$rndUnit = array_rand($arrUnits,1);
                                //var_dump($arrUnits[$n]);
                                //var_dump($rndUnit);
                                $k=$arrUnits[$n]->row;
                                $m=$arrUnits[$n]->column;
                                action($i,$j,'',$k,$m,'');
                                }
                            }
                            
                        }

                        if($cell->resCount && in_array('worker',$cell->contains->ability) &&$cell->contains->owner !=0){
                            $cell->resCount--;
                            setGold($cell->contains->owner,'+',1);
                        }
                        if(in_array('deathAura', $cell->contains->ability)){
                            if(count($arrUnits)>0){
                                for($n=0;$n<count($arrUnits);$n++){
                                    magicHit($i,$j,$arrUnits[$n]->row,$arrUnits[$n]->column,1);
                                }
                            }
                        }
                        if(in_array('evasion',$cell->contains->ability)){
                            $search = array_search('evasion',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                            }
                        }
                        if(in_array('weak',$cell->contains->ability)){
                            $search = array_search('weak',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                            }
                        }
                        if(in_array('poisoned',$cell->contains->ability)){
                            $search = array_search('poisoned',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                            }
                            if($cell->contains->hp>1){
                                $cell->contains->hp -= 1;
                            }
                        }
                        if(in_array('bloodShield',$cell->contains->ability)){
                            $search = array_search('bloodShield',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                            }
                        }
                        if(in_array('healing',$cell->contains->ability)){
                            $search = array_search('healing',$cell->contains->ability);
                            if($search !== false){
                                array_splice($cell->contains->ability,$search,1);
                                if($cell->contains->hp < $cell->contains->hpMax){
                                    $cell->contains->hp += 1;
                                }
                            }
                        }
                        if(in_array('regeneration',$cell->contains->ability) && $cell->contains->hp < $cell->contains->hpMax){
                            $cell->contains->hp +=1;
                        }
                    }
                };
                /*if($newRound==true && $cell->resCount && in_array('worker',$cell->contains->ability)){
                    $cell->resCount--;
                    $json->gamePlayers[$cell->contains->owner]->gold+=1;
                }*/
            }
        }
    }
}
?>