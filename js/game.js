"use strict";
let turnFlag = 0;
let cashUnit = undefined;;
let cashCell = {
    i: undefined,
    j: undefined
}
//Variables


function pressCell(i,j){
    
    if (gameField[i][j].contains != undefined && gameField[i][j].availability == true && turnFlag == 0){
        //alert(`${i}-${j}`);
        if(gameField[i][j].contains.owner == gameSettings.turnOwner){
            if(gameField[i][j].contains.canMove == true || gameField[i][j].contains.canAction == true){
                lockAllCells();
                if(gameField[i][j].contains.canMove == true)unlockCells(gameField[i][j].contains.movePoint, i, j, 'move');
                
                if(gameField[i][j].contains.canAction == true)unlockCells(gameField[i][j].contains.range, i, j, 'atk');
                turnFlag = 1;
                document.getElementById('btnCancel').style.display = 'inline';
                cashCell.i = gameField[i][j].row;
                cashCell.j = gameField[i][j].column;

                cashUnit = gameField[i][j].contains;
            }
            /*if(gameField[i][j].contains.canAction == true){
                
            }*/
        }
    }
    else if(gameField[i][j].contains == undefined && gameField[i][j].availability == true && turnFlag == 1){
        //moving unit  
        if (gameField[cashCell.i][cashCell.j].contains.canMove == true){  
            
            gameField[cashCell.i][cashCell.j].contains = undefined;
            gameField[i][j].contains = cashUnit;
            gameField[i][j].contains.canMove = false;
            cancel();
            /*cashUnit = undefined;
            turnFlag = 0;
            unlockAllCells();
            update();
            document.getElementById('btnCancel').style.display = 'none';*/
        }
    }
    // -----Атака-----
    else if (gameField[i][j].contains != undefined && gameField[i][j].contains.owner != gameSettings.turnOwner && gameField[i][j].availability == true && turnFlag == 1){
        if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
            let atkUnit = cashUnit;
            let defUnit = gameField[i][j].contains;
            if(defUnit.hp - atkUnit.attack > 0){
                defUnit.hp = defUnit.hp - atkUnit.attack;
            }else {
                gameField[i][j].contains = undefined;
                players[atkUnit.owner].exp += 1;
                let lvl = undefined;
                switch(players[atkUnit.owner].level){
                    case 0:
                        lvl = gameSettings.level1;
                        break;
                    case 1:
                        lvl = gameSettings.level2;
                        break;
                    case 2:
                        if (players[atkUnit.owner].faction == 'Orcs')
                        lvl = gameSettings.level3;
                        break;
                    default:
                        break;
                }
                if(lvl != undefined){
                    if(players[atkUnit.owner].exp>=lvl){
                        lvlUp(atkUnit.owner,);
                    }
                }
            }
            atkUnit.canAction = false;
            cancel();
        }
    }else if(gameField[i][j].contains != undefined && gameField[i][j].contains.owner == gameSettings.turnOwner && gameField[i][j].availability == true && turnFlag == 1){
        gameField[i][j].contains.hp +=1;
        cashUnit.canAction = false;
        cancel();
    }else if(turnFlag == 1 && gameField[i][j] == gameField[cashCell.i][cashCell.j]){//
        cancel();//отмена путем нажатия на туже клетку что и в первый раз
    }
    
}

function lvlUp(playerLvlUp){

    let skillsPoint = 0;
    players[playerLvlUp].level += 1;
    let skillsChoise = new Array(2);
    while(skillsPoint <2){
        let randomSkill = gameSettings.skills[getRandomInt(0,6)];
        if(!players[playerLvlUp].skills.includes(randomSkill,0)){
            if (skillsChoise[0] != randomSkill && randomSkill == gameSettings.skills[1] && players[playerLvlUp].skills.includes(gameSettings.skills[0],0)){
                skillsChoise[skillsPoint] = randomSkill;
                skillsPoint+=1;
            }else if(skillsChoise[0] != randomSkill && randomSkill == gameSettings.skills[5] && players[playerLvlUp].skills.includes(gameSettings.skills[4],0)){
                skillsChoise[skillsPoint] = randomSkill;
                skillsPoint+=1;
            }else if (skillsChoise[0] != randomSkill && randomSkill != gameSettings.skills[1] && randomSkill != gameSettings.skills[5]){
                skillsChoise[skillsPoint] = randomSkill;
                skillsPoint+=1;
            }
        }
    }
    let result = null;
    while(result == null || (result != 1 && result != 2)){
        result = prompt(`У вас на выбор два навыка: 1)${skillsChoise[0].name} - ${skillsChoise[0].description} 2)${skillsChoise[1].name} - ${skillsChoise[1].description}, введите цифру`);
    }
    players[playerLvlUp].skills.push(skillsChoise[result-1]);
    let obj;
    gameField.forEach(arr => {
        arr.forEach(elem => {
            if (elem.contains != undefined){
                if (elem.contains.class == 'Warchief' && elem.contains.owner == playerLvlUp){
                    obj = elem.contains;
                }
            }
        });
    });
    switch(skillsChoise[result-1]){
        case gameSettings.skills[0]: //Strength I - +1 attack to Warchief
            players[playerLvlUp].faction.warchief[5] += 1;
            obj.attack += 1;
            break;
        case gameSettings.skills[1]: //Strength II - +2 hp to Warchief
            players[playerLvlUp].faction.warchief[4] += 2;
            obj.hpMax += 2;
            break;
        case gameSettings.skills[2]: //Pathfinder - +1 move to Warchief
            players[playerLvlUp].faction.warchief[6] += 1;
            obj.movePoint += 1;
            break;
        case gameSettings.skills[3]: //Surgery - add skill "Heal" to Warchief
            players[playerLvlUp].faction.warchief[9].push('surgery');
            obj.ability.push('surgery');
            break;
        case gameSettings.skills[4]: //Estates I - +5 gold 
            players[playerLvlUp].gold += 5;
            break;
        case gameSettings.skills[5]: //Estates II - +1 gold every turn
            break;
        default:
            break;
    }
}


// -------BTN FUNCTIONS-------
function build(typeBuilding){ // Постройка зданий
    let building;
    let checkLimit = false;
    switch(typeBuilding){
        case 'townhall':
            if(players[gameSettings.turnOwner].count_townhalls<gameSettings.limit_townhalls)
                checkLimit = true;
            building = players[gameSettings.turnOwner].faction.townhall;
            break;
        case 'tower':
            if(players[gameSettings.turnOwner].count_towers<gameSettings.limit_towers)
                checkLimit = true;
            building = players[gameSettings.turnOwner].faction.tower;
            break;
        default:
            alert('ошибка в выборе юнита');
            break;
    }
    if(gameField[cashCell.i][cashCell.j].resCount <=0){
        if(players[gameSettings.turnOwner].gold>=building[8]){
            players[gameSettings.turnOwner].gold -= building[8];
            gameField[cashCell.i][cashCell.j].contains = new Building(building,gameSettings.turnOwner,false);
            cancel();
        }else alert('недостаточно денег, надо ' + building[8])
    }else alert('нельзя строить, тут есть золото')
}
function buyUnit(typeUnit){
    let unit;
    let checkLimit = false;
    switch(typeUnit){
        case't1':
            if(players[gameSettings.turnOwner].count_workers<gameSettings.limit_workers)
                checkLimit = true;
            unit = players[gameSettings.turnOwner].faction.t1;
            break;
        case 't2':
            if(players[gameSettings.turnOwner].count_army<gameSettings.limit_army)
                checkLimit = true;
            unit = players[gameSettings.turnOwner].faction.t2;
            break;
        case 't3':
            if(players[gameSettings.turnOwner].count_army<gameSettings.limit_army)
                checkLimit = true;
            unit = players[gameSettings.turnOwner].faction.t3;
            break;
        case 'warchief':
            if(players[gameSettings.turnOwner].count_warchiefs<gameSettings.limit_warchiefs)
                checkLimit = true;
            unit = players[gameSettings.turnOwner].faction.warchief;
            break;
        default:
            alert('ошибка в выборе юнита');
            break;

    }
    if(checkLimit == true){
        if(players[gameSettings.turnOwner].gold >= unit[8]){
            if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
                if(cashCell.i-1>-1 && gameField[cashCell.i-1][cashCell.j].contains == undefined)
           {gameField[cashCell.i-1][cashCell.j].contains = new Unit(unit,gameSettings.turnOwner,false);
            gameField[cashCell.i][cashCell.j].contains.canAction = false;players[gameSettings.turnOwner].gold -= unit[8];}
           else if(cashCell.j+1<8 && gameField[cashCell.i][cashCell.j+1].contains == undefined)
           {gameField[cashCell.i][cashCell.j+1].contains = new Unit(unit,gameSettings.turnOwner,false);
            gameField[cashCell.i][cashCell.j].contains.canAction = false;players[gameSettings.turnOwner].gold -= unit[8];}
           else if(cashCell.i+1<8 && gameField[cashCell.i+1][cashCell.j].contains == undefined)
           {gameField[cashCell.i+1][cashCell.j].contains = new Unit(unit,gameSettings.turnOwner,false);
            gameField[cashCell.i][cashCell.j].contains.canAction = false;players[gameSettings.turnOwner].gold -= unit[8];}
           else if(cashCell.j-1>-1 && gameField[cashCell.i][cashCell.j-1].contains == undefined)
           {gameField[cashCell.i][cashCell.j-1].contains = new Unit(unit,gameSettings.turnOwner,false);
            gameField[cashCell.i][cashCell.j].contains.canAction = false;players[gameSettings.turnOwner].gold -= unit[8];}
           else {alert('Некуда разместить юнита'); gameField[cashCell.i][cashCell.j].canAction = true}
           cancel();
            }    
        }else alert('недостаточно денег, надо ' + unit[8])
    }else alert('лимит юнитов достигнут')
    
         
}
function heal(type){
    if (type == 'surgery'){
        gameField[cashCell.i][cashCell.j].contains.hp += 1;
        gameField[cashCell.i][cashCell.j].contains.canAction = false;
        cancel();
    }else alert('gavno))))');
}





function cancel(){
   // gameField[cashCell.i][cashCell.j].contains = cashUnit;
    cashCell.i = undefined;
    cashCell.j = undefined;
    cashUnit = undefined;
    turnFlag = 0;
    unlockAllCells();
    update();
    document.getElementById('btnCancel').style.display = 'none';
    document.getElementById('btnBuildTownhall').style.display = 'none';
    document.getElementById('btnBuildTower').style.display = 'none';
    document.getElementById('btnBuyT1').style.display = 'none';
    document.getElementById('btnBuyT2').style.display = 'none';
    document.getElementById('btnBuyT3').style.display = 'none';
    document.getElementById('btnBuyWarchief').style.display = 'none';
    document.getElementById('btnSurgeryHeal').style.display = 'none';
}

function endTurn(){
    let newRound = false;
    gameSettings.turnOwner++;
    while(players[gameSettings.turnOwner] == undefined){
        
        if(gameSettings.turnOwner>=players.length-1){
            gameSettings.turnOwner = 0;
            newRound = true;
        }
        gameSettings.turnOwner++;
    }
    for(let k = 1; k<players.length;k++){
        if(players[k] != undefined){
            players[k].counts = 0;
            if(newRound == true && players[k].skills.includes(gameSettings.skills[5],0)){
                players[k].gold +=1;
            }
        }
    }
    document.getElementById('btnEndTurn').style.backgroundColor = colorPlayers[gameSettings.turnOwner-1];
    for(let i = 0; i<gameField.length; i++){
        for(let j = 0; j<gameField[i].length; j++){
            let cell = gameField[i][j];
            if(cell.contains != undefined){
                players[cell.contains.owner].counts++;
                cell.contains.canMove = true;
                cell.contains.canAction = true;
                if(newRound == true && cell.resCount > 0){
                    cell.resCount--;
                    players[cell.contains.owner].gold++;
                }
            }
        }
    }
    for(let k = 1; k<players.length;k++){
        if(players[k] != undefined && players[k].counts==0){
            alert(`Игрок ${players[k].name} проиграл!`);
            delete players[k];
        }
    }
    
    cancel();
    
}



