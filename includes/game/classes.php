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
        ["name" => "Undead I", "description" => "Позволяет Личу призывать скелетов в количестве равному количеству Некрополисов"],
        ["name" => "Undead II", "require" => "Undead I", "description" => "Увеличивает здоровье Лича на 5"]
    ],
];

class Player{
        public $name,$owner,$gold,$counts,$level,$exp,$skills,$faction,
        $count_workers,$count_army,$count_warchiefs,$count_townhalls,$count_towers;
        function __construct($name, $num, $faction)
        {
            $this->name = $name;
            $this->owner = $num;
            $this->gold = 0;
            $this->level = 0;
            $this->exp = 0;
            $this->skills = [];

            $this->counts = 0;
            $this->count_workers = 0;
            $this->count_army = 0;
            $this->count_warchiefs = 0;
            $this->count_townhalls = 0;
            $this->count_towers = 0;
            switch ($faction){
                case "kingdom":
                    $this->faction = new Kingdom();
                    $this->gold += 2;
                    break;
                case "seamercs":
                    //$this->faction = new SeaMercs();
                    break;
                case "undead":
                    $this->faction = new Undead();
                    break;
                case "orcs":
                    //$this->faction = new Orcs();
                    break;
                case "elves":
                    //$this->faction = new Elves();
                    break;
                case "neutral":
                    $this->faction = new Kingdom();
                    break;
                case "random":
                    $this->faction = new Kingdom();
                    $this->gold += 2;
                    break;
                default: //random
                    $this->faction = new Kingdom();
                    break;
            }
            //$this->faction = $faction;
        }
}
class Unit{
    public $type,$class,$name,$description,$hpMax,$hp,$attack,$movePoint,$range,$ability,
    $canMove,$canAction,$owner,$image,$out,$require;
    function __construct($array, $owner, $action)
    {
        $this->type = $array[0];
        $this->class = $array[1];
        $this->name = $array[2];
        $this->description = $array[3];
        $this->hpMax = $array[4];
        $this->hp = $array[4];
        $this->attack = $array[5];
        $this->movePoint = $array[6];
        $this->range = $array[7];
        $this->ability = $array[9];
        $this->canMove = $action;
        $this->canAction = $action;
        $this->owner = $owner;
        $this->image = $array[10];
        $this->out = $array[11];
        $this->require = $array[12];
    }
}

// -----  Fields  -----
class Cell {
    public $contains,$resCount,$availability,$mountains,$row,$column,$view;
    function __construct($i,$j)
    {
        $this->contains = false;
        $this->resCount = 0;
        $this->availability = true;
        $this->mountains = false;
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
        // (0)type-(1)class-(2)name-(3)desc-(4)hp-(5)attack-(6)movePoint-(7)range-(8)price-(9)ability-(10)image-(11)outgoing-(12)require
        $this->name = "Kingdom";
        $this->t1 = ['unit','t1','Peasant','desc',1,1,1,1,0,['worker'],'img/units/KingdomT1.png','',''];
        $this->t2 = ['unit','t2','Scout','desc',1,1,2,1,1,[],"img/units/KingdomT2.png",'',''];
        $this->t3 = ['unit','t3','Knight','desc',3,1,1,1,2,[],"img/units/KingdomT3.png",'',''];
        $this->warchief = ['unit','warchief','Lord','desc',4,1,2,1,5,['cavalryStrike'],"img/units/KingdomWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Townhall','desc',5,0,0,0,4,['hire'],'img/units/KingdomTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Tower','desc',3,1,0,2,3,['hire'],"img/units/KingdomTower.png",'t3-warchief',''];
    }
}

class SeaMercs {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
        $this->name = "Sea Mercs";
        $this->t1 = ['unit','t1','Peasant','desc',1,1,1,1,1,['worker'],'img/units/KingdomT1.png'];
        $this->t2 = [];
        $this->t3 = [];
        $this->warchief = [];
        $this->townhall = ['building','townhall','Townhall','desc',5,0,0,0,4,['hire'],'img/units/KingdomTownhall.png'];
        $this->tower = [];
    }
}

class Undead {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;

    function __construct()
    {
        $this->name = "Undead";
        $this->t1 = ['unit','t1','Skeleton','desc',1,1,1,1,0,['worker'],'img/units/UndeadT1.png','',''];
        $this->t2 = ['unit','t2','Zombie','desc',2,1,1,1,1,[],"img/units/UndeadT2.png",'',''];
        $this->t3 = ['unit','t3','Ghost','desc',2,1,2,1,3,['vampir'],"img/units/UndeadT3.png",'',''];
        $this->warchief = ['unit','warchief','Lich','desc',2,1,1,1,2,[],"img/units/UndeadWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Necropolis','desc',5,0,0,0,4,['hire'],'img/units/UndeadTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Tower','desc',3,1,0,2,3,['hire'],"img/units/UndeadTower.png",'t3-warchief',''];
    }
}
class Orcs {
    public $name, $t1, $t2, $t3, $warchief, $townhall, $tower;
    //not work
    function __construct()
    {
        $this->name = "Orcs";
        $this->t1 = ['unit','t1','Skeleton','desc',1,1,1,1,0,['worker'],'img/units/UndeadT1.png','',''];
        $this->t2 = ['unit','t2','Zombie','desc',2,1,1,1,1,[],"img/units/UndeadT2.png",'',''];
        $this->t3 = ['unit','t3','Ghost','desc',2,1,2,1,3,['vampir'],"img/units/UndeadT3.png",'',''];
        $this->warchief = ['unit','warchief','Lich','desc',2,1,1,1,2,[],"img/units/UndeadWarchief.png",'','2t'];
        $this->townhall = ['building','townhall','Necropolis','desc',5,0,0,0,4,['hire'],'img/units/UndeadTownhall.png','t1-t2',''];
        $this->tower = ['building','tower','Tower','desc',3,1,0,2,3,['hire'],"img/units/UndeadTower.png",'t3-warchief',''];
    }
}
?>