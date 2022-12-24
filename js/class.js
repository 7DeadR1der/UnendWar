"use strict";
let idIndex = 1;
const players = new Array();
const gameSettings = {
    turnOwner: 0,
    level1: 5,
    level2: 10,
    level3: 15,
    limit_workers : 6,
    limit_army : 4,
    limit_warchiefs : 1,
    limit_townhalls : 2,
    limit_towers : 3,
    //skills:['Strength I','Strength II','Pathfinder','Surgery','Estates I', 'Estates'];
    skills:[
        {name:'Strength I', description:'Увеличивает силу атаки Вождя на 1'},
        {name:'Strength II', description:'Увеличивает здоровье Вождя на 2'},
        {name:'Pathfinder', description:'Увеличивает скорость Вождя на 1'},
        {name:'Surgery', description:'Позволяет вождю лечить себя или союзников'},
        {name:'Estates I', description:'Единовременно дает 4 золота'},
        {name:'Estates II', description:'Каждый ход дает 1 золото'}]
};
class Kingdom {
    constructor(){
        this.name = 'Kingdom';
        this.t1 = ['unit','t1','Peasant','description',1,1,1,1,0,['worker'],"img/units/KingdomT1.png",'Townhall'];
        this.t2 = ['unit','t2','Scout','description',1,1,2,1,1,[],"img/units/KingdomT2.png",'Townhall'];
        this.t3 = ['unit','t3','Knight','description',3,1,1,1,2,[],"img/units/swordsman.png",'Tower'];
        this.warchief = ['unit','warchief','Lord','description',4,1,2,1,5,['cavalryStrike'],"img/units/KingdomWarchief.png",'Tower'];
        this.townhall = ['building','townhall','Townhall','description',5,0,0,0,5,['hire'],"img/units/citadel.png"];
        this.tower = ['building','tower','Tower','description',3,1,0,2,3,['hire'],"img/units/Tower.png"];
    }
    start(owner) {
        players[owner].gold +=2;
        //this.gold += 2;
    };
};



class Player{
    constructor(name, num, factionType){
        this.name = name;
        this.owner = num;
        //this.color
        this.gold = 0;
        this.counts = 0
        this.level = 0;
        this.exp = 0;
        this.skills = [];
        this.count_workers = 0;
        this.count_army = 0;
        this.count_warchiefs = 0;
        this.count_townhalls = 0;
        this.count_towers = 0;
        switch(factionType){
            case ('Kingdom'):
                this.faction = new Kingdom();
                break;
            case ('SeaMercenaries'):
                //this.faction = new Kingdom();
                break;
            default:
                break;
        }
    }
}

class Unit{
    constructor(arrInf, owner, action){
        this.type = arrInf[0];
        this.class = arrInf[1];
        this.name = arrInf[2];
        this.description = arrInf[3];
        this.hpMax = arrInf[4];
        this.hp = arrInf[4];
        this.attack = arrInf[5];
        this.movePoint = arrInf[6];
        this.range = arrInf[7];
        this.ability = arrInf[9];
        this.canMove = action;
        this.canAction = action;
        this.owner = owner;
        this.id = idIndex++;
        this.image = arrInf[10];
        this.out = arrInf[11]
        
    }
}

class Building{
    constructor(arrInf, owner, action){
        this.type = arrInf[0];
        this.class = arrInf[1];
        this.name = arrInf[2];
        this.description = arrInf[3];
        this.hpMax = arrInf[4];
        this.hp = arrInf[4];
        this.attack = arrInf[5];
        this.movePoint = arrInf[6];
        this.range = arrInf[7];
        this.cost = arrInf[8];
        this.ability = arrInf[9];
        this.canMove = action;
        this.canAction = action;
        this.owner = owner;
        this.id = idIndex++;
        this.image = arrInf[10];
    }
}



// DELETE HIR AFTER 
function mapMaker(num){
    if(num == 1){
        gameField[7][0].resCount = 10;
        gameField[0][7].resCount = 10;
        gameField[0][3].resCount = 10;
        gameField[2][0].resCount = 10;
        gameField[7][4].resCount = 10;
        gameField[5][7].resCount = 10;
        gameField[6][1].contains = new Building(players[1].faction.townhall,1,true);
        gameField[1][6].contains = new Building(players[2].faction.townhall,2,true);
        gameField[6][2].contains = new Unit(players[1].faction.t1,1,true);
        gameField[5][1].contains = new Unit(players[1].faction.t1,1,true);
        gameField[2][6].contains = new Unit(players[2].faction.t1,2,true);
        gameField[1][5].contains = new Unit(players[2].faction.t1,2,true);
        gameField[5][2].contains = new Unit(players[1].faction.t2,1,true);
        gameField[2][5].contains = new Unit(players[2].faction.t2,2,true);
        gameField[0][0].mountains = true;
        gameField[0][1].mountains = true;
        gameField[1][0].mountains = true;
        gameField[2][4].mountains = true;
        gameField[3][7].mountains = true;
        gameField[4][0].mountains = true;
        gameField[5][3].mountains = true;
        gameField[6][7].mountains = true;
        gameField[7][6].mountains = true;
        gameField[7][7].mountains = true;
    }
    else if(num == 2){
        gameField[7][7].contains = new Building(players[1].faction.townhall,1,true);
        gameField[0][0].contains = new Building(players[2].faction.townhall,2,true);
        gameField[7][6].contains = new Unit(players[1].faction.t1,1,true);
        gameField[6][7].contains = new Unit(players[1].faction.t1,1,true);
        gameField[1][0].contains = new Unit(players[2].faction.t1,2,true);
        gameField[0][1].contains = new Unit(players[2].faction.t1,2,true);
        gameField[6][6].contains = new Unit(players[1].faction.t2,1,true);
        gameField[1][1].contains = new Unit(players[2].faction.t2,2,true);
        gameField[7][0].resCount = 5;
        gameField[0][7].resCount = 5;
        gameField[3][4].resCount = 5;
        gameField[4][3].resCount = 5;
        gameField[0][2].resCount = 1;
        gameField[0][4].resCount = 1;
        gameField[0][5].resCount = 1;
        gameField[1][3].resCount = 1;
        gameField[1][5].resCount = 1;
        gameField[2][0].resCount = 1;
        gameField[2][2].resCount = 1;
        gameField[2][4].resCount = 1;
        gameField[2][6].resCount = 1;
        gameField[3][1].resCount = 1;
        gameField[3][5].resCount = 1;
        gameField[3][7].resCount = 1;
        gameField[7][5].resCount = 1;
        gameField[7][3].resCount = 1;
        gameField[7][2].resCount = 1;
        gameField[6][4].resCount = 1;
        gameField[6][2].resCount = 1;
        gameField[5][7].resCount = 1;
        gameField[5][5].resCount = 1;
        gameField[5][3].resCount = 1;
        gameField[5][1].resCount = 1;
        gameField[4][6].resCount = 1;
        gameField[4][2].resCount = 1;
        gameField[4][0].resCount = 1;
    }else if(num == 3){
        gameField[0][2].contains = new Building(players[1].faction.townhall,1,true);
        gameField[2][7].contains = new Building(players[2].faction.townhall,2,true);
        gameField[7][5].contains = new Building(players[3].faction.townhall,3,true);
        gameField[5][0].contains = new Building(players[4].faction.townhall,4,true);
        gameField[0][0].resCount = 10;
        gameField[0][7].resCount = 10;
        gameField[7][0].resCount = 10;
        gameField[7][7].resCount = 10;
        gameField[0][4].resCount = 10;
        gameField[3][0].resCount = 10;
        gameField[4][7].resCount = 10;
        gameField[7][3].resCount = 10;
        gameField[2][4].mountains = true;
        gameField[3][2].mountains = true;
        gameField[4][5].mountains = true;
        gameField[5][3].mountains = true;
    }else if(num == 4){
        players[1].gold += 5;
        players[2].gold += 5;
        players[3].gold += 5;
        players[4].gold += 5;
        gameField[3][0].contains = new Unit(players[1].faction.t1,1,true);
        gameField[0][3].contains = new Unit(players[2].faction.t1,2,true);
        gameField[4][7].contains = new Unit(players[3].faction.t1,3,true);
        gameField[7][4].contains = new Unit(players[4].faction.t1,4,true);
        gameField[0][0].resCount = 15;
        gameField[0][7].resCount = 15;
        gameField[7][0].resCount = 15;
        gameField[7][7].resCount = 15;
        gameField[3][3].resCount = 15;
        gameField[4][4].resCount = 15;
        gameField[0][2].mountains = true;
        gameField[1][6].mountains = true;
        gameField[2][0].mountains = true;
        gameField[3][4].mountains = true;
        gameField[4][3].mountains = true;
        gameField[5][7].mountains = true;
        gameField[6][1].mountains = true;
        gameField[7][5].mountains = true;
    }
}