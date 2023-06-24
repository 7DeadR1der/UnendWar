<?php
$gameSettings = [
    "level1" => 5,
    "level2" => 15,
    "level3" => 30,
    "limit_workers" => 6,
    "limit_army" => 4,
    "limit_warchiefs" => 1,
    "limit_townhalls" => 2,
    "limit_towers" => 4,
    "skills" => [
        ["name" => "Strength I", "description" => "Увеличивает силу атаки Вождя на 1"],
        ["name" => "Strength II", "require" => "Strength I", "description" => "Увеличивает здоровье Вождя на 2"],
        ["name" => "Pathfinder", "description" => "Увеличивает скорость Вождя на 1"],
        ["name" => "Surgery", "description" => "Позволяет вождю лечить себя или союзников"],
        ["name" => "Estates I", "description" => "Единовременно дает 4 золота"],
        ["name" => "Estates II", "require" => "Estates I", "description" => "Каждый ход дает 1 золото"],
        ["name" => "Engineering", "description" => "Все здания получают +1 к прочности"]
    ],
    "undead_skills" => [
        ["name" => "Undead I", "description" => "Увеличивает здоровье Лича на 1 ед., зомби получают спсобность 'infect'"],
        ["name" => "Undead II", "require" => "Undead I", "description" => "Увеличивает здоровье Лича на 1 ед., Лич получает способность 'darkStorm'"]
    ],
    "orcs_skills" => [
        ["name" => "Scavengers", "description" => "T2 при убийстве восстанавливают себе здоровье"]  // и Warchief
    ]
];

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
        echo "error type";
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




class Player{
        public $name,$owner,$live,$gold,$counts,$level,$exp,$skills,$faction,$statistic,
        $count_workers,$count_army,$count_warchiefs,$count_townhalls,$count_towers;

        function __construct($name, $num, $faction)
        {
            $this->name = $name;
            $this->owner = $num;
            $this->live = true;
            $this->gold = 0;
            $this->level = 0;
            $this->exp = 0;
            $this->skills = [];
            $this->statistic = new stdClass;
                $this->statistic->score=0;
                $this->statistic->goldUp=0;
                $this->statistic->goldDown=0;
                $this->statistic->unitUp=0;
                $this->statistic->unitDown=0;
                $this->statistic->buildUp=0;
                $this->statistic->buildDown=0;
                $this->statistic->warchiefUp=0;
                $this->statistic->warchiefDown=0;

            $this->counts = 0;
            $this->count_workers = 0;
            $this->count_army = 0;
            $this->count_warchiefs = 0;
            $this->count_townhalls = 0;
            $this->count_towers = 0;
            switch ($faction){
                case "kingdom":
                    $this->faction = new Kingdom();
                    $this->gold += 3;
                    $this->statistic->goldUp+=3;
                    break;
                case "seamercs":
                    $this->faction = new SeaMercs();
                    break;
                case "undead":
                    $this->faction = new Undead();
                    break;
                case "orcs":
                    $this->faction = new Orcs();
                    break;
                case "elves":
                    //$this->faction = new Elves();
                    break;
                case "neutral":
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
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)ability-(9)image-(10)outgoing-(11)require
        $this->name = "Kingdom";
        $this->t1 = ['unit','t1','Peasant',1,1,1,1,0,['worker'],'img/units/KingdomT1.png','',''];
        $this->t2 = ['unit','t2','Scout',1,1,2,1,1,[],"img/units/KingdomT2.png",'',''];
        $this->t3 = ['unit','t3','Knight',3,1,1,1,2,[],"img/units/KingdomT3.png",'',''];
        $this->warchief = ['unit','warchief','Lord',4,1,2,1,5,['cavalryStrike'],"img/units/KingdomWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Townhall',5,0,0,0,4,['hire'],'img/units/KingdomTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Tower',3,1,0,2,3,['hire'],"img/units/KingdomTower.png",'t3-warchief',''];
    }
}

class SeaMercs {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
//(0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)ability-(9)image-(10)outgoing-(11)require
        $this->name = "SeaMercs";
        $this->t1 = ['unit','t1','Slave',1,1,1,1,0,['worker'],'img/units/SeaMercsT1.png','',''];
        $this->t2 = ['unit','t2','Raider',1,1,2,1,1,['pillage'],"img/units/SeaMercsT2.png",'',''];
        $this->t3 = ['unit','t3','Merc',2,1,1,1,1,['pillage'],"img/units/SeaMercsT3.png",'',''];
        $this->warchief = ['unit','warchief','Berserk',4,1,1,1,4,['veteran'],"img/units/SeaMercsWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Forge',5,0,0,0,4,['hire','smith'],'img/units/SeaMercsTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Outpost',3,1,0,2,3,['hire'],"img/units/SeaMercsTower.png",'t3-warchief',''];
    }
}

class Undead {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
        $this->name = "Undead";
        $this->t1 = ['unit','t1','Skeleton',1,1,1,1,0,['worker'],'img/units/UndeadT1.png','',''];
        $this->t2 = ['unit','t2','Zombie',2,1,1,1,1,[],"img/units/UndeadT2.png",'',''];
        $this->t3 = ['unit','t3','Ghost',2,1,2,1,3,['vampir'],"img/units/UndeadT3.png",'',''];
        $this->warchief = ['unit','warchief','Lich',2,1,1,1,3,['darkArmy'],"img/units/UndeadWarchief.png",'',''];
        $this->townhall = ['building','townhall','Necropolis',5,0,0,0,4,['hire'],'img/units/UndeadTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Soul tower',3,1,0,2,3,['hire'],"img/units/UndeadTower.png",'t3-warchief',''];
    }
}
class Orcs {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;
    //not work
    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)ability-(9)image-(10)outgoing-(11)require
        $this->name = "Orcs";
        $this->t1 = ['unit','t1','Goblin',1,1,1,1,0,['worker'],'img/units/OrcsT1.png','',''];
        $this->t2 = ['unit','t2','Orc',2,1,1,1,1,[],"img/units/OrcsT2.png",'',''];
        $this->t3 = ['building','t3','Onager',1,1,1,2,4,['siegeDmg'],"img/units/OrcsT3.png",'',''];
        $this->warchief = ['unit','warchief','Warchief',5,1,1,1,5,[],"img/units/OrcsWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','War House',5,0,0,0,4,['hire'],'img/units/OrcsTownhall.png','t1-t3',''];
        $this->tower = ['building','tower','Watch tower',2,1,0,2,2,['hire'],"img/units/OrcsTower.png",'t2-warchief',''];
    }
}
class Neutral {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
        // (0)type-(1)class-(2)name-(3)hp-(4)attack-(5)movePoint-(6)range-(7)price-(8)ability-(9)image-(10)outgoing-(11)require
        $this->name = "Neutral";
        $this->t1 = ['building','t1','Chest',2,0,0,0,0,['treasure','meleeOnly'],'img/units/NeutralT1.png','',''];
        $this->t2 = ['unit','t2','Wolf',2,1,1,1,1,[],"img/units/NeutralT2.png",'',''];
        $this->t3 = ['unit','t3','Ogre',4,1,1,1,4,['cannibal'],"img/units/NeutralT3.png",'',''];
        $this->warchief = ['unit','warchief','Dragon',8,2,2,1,10,['monster'],"img/units/NeutralWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Ogre fort',6,1,0,1,6,['prison'],'img/units/NeutralTownhall.png','',''];
        $this->tower = ['building','tower','Bandit outpost',3,1,0,1,3,[],"img/units/NeutralTower.png",'',''];
    }
}
?>