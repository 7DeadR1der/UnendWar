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
            let atk = atkUnit.attack;
            if(atkUnit.ability.includes('cavalryStrike',0) && atkUnit.canMove == false){atk = Math.round(atk*1.5)}
            if(defUnit.hp - atk > 0){
                defUnit.hp = defUnit.hp - atk;
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
    //cell desc
    if(gameField[i][j].resCount>0)document.getElementById('li_goldCell').textContent = `Золото = ${gameField[i][j].resCount}`;
    else document.getElementById('li_goldCell').textContent = ``;
    if(gameField[i][j].contains != undefined){
        document.getElementById('li_nameUnit').textContent = `${gameField[i][j].contains.name}`;
        document.getElementById('li_descriptionUnit').textContent = `${gameField[i][j].contains.description}`;
        document.getElementById('li_ownerUnit').textContent = `Владелец - ${players[gameField[i][j].contains.owner].name}`;
        document.getElementById('li_hpUnit').textContent = `Здоровье - ${gameField[i][j].contains.hp}/${gameField[i][j].contains.hpMax}`;
        document.getElementById('li_attackUnit').textContent = `Атака - ${gameField[i][j].contains.attack}`;
        document.getElementById('li_moveUnit').textContent = `Скорость - ${gameField[i][j].contains.movePoint}`;
    }else{
        document.getElementById('li_nameUnit').textContent = '';
        document.getElementById('li_descriptionUnit').textContent = '';
        document.getElementById('li_ownerUnit').textContent = '';
        document.getElementById('li_hpUnit').textContent = '';
        document.getElementById('li_attackUnit').textContent = '';
        document.getElementById('li_moveUnit').textContent = '';
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
    skillAdd(skillsChoise[result-1],playerLvlUp);
}
function skillAdd(skill, owner){
    switch(skill){
        case gameSettings.skills[0]: //Strength I - +1 attack to Warchief
            players[owner].faction.warchief[5] += 1;
            if(obj)obj.attack += 1;
            break;
        case gameSettings.skills[1]: //Strength II - +2 hp to Warchief
            players[owner].faction.warchief[4] += 2;
            if(obj){obj.hpMax += 2;obj.hp += 2;}
            break;
        case gameSettings.skills[2]: //Pathfinder - +1 move to Warchief
            players[owner].faction.warchief[6] += 1;
            if(obj)obj.movePoint += 1;
            break;
        case gameSettings.skills[3]: //Surgery - add skill "Heal" to Warchief
            players[owner].faction.warchief[9].push('surgery');
            if(obj)obj.ability.push('surgery');
            break;
        case gameSettings.skills[4]: //Estates I - +4 gold 
            players[owner].gold += 4;
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
        if(checkLimit == true){
            if(players[gameSettings.turnOwner].gold>=building[8]){
                players[gameSettings.turnOwner].gold -= building[8];
                gameField[cashCell.i][cashCell.j].contains = new Building(building,gameSettings.turnOwner,false);
                cancel();
            }else alert('недостаточно денег, надо ' + building[8])
        }else alert('Лимит зданий достигнут')
    }else alert('нельзя строить, тут есть золото')
}
function buyUnit(typeUnit){ // Покупка юнитов
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
            if(players[gameSettings.turnOwner].count_warchiefs<gameSettings.limit_warchiefs){
                if(players[gameSettings.turnOwner].count_towers>=2){
                    checkLimit = true;
                    unit = players[gameSettings.turnOwner].faction.warchief;
                }else alert('Для Вождя нужно 2 башни');
            }
            break;
        default:
            alert('ошибка в выборе юнита');
            break;

    }
    if(checkLimit == true){
        if(players[gameSettings.turnOwner].gold >= unit[8]){
            if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
                /*let arrayCells = new Array();
                if(cashCell.i-1>-1 && gameField[cashCell.i-1][cashCell.j].contains == undefined && gameField[cashCell.i-1][cashCell.j].mountains == false) arrayCells.push(gameField[cashCell.i-1][cashCell.j]);
                if(cashCell.j+1<8 && gameField[cashCell.i][cashCell.j+1].contains == undefined && gameField[cashCell.i][cashCell.j+1].mountains == false) arrayCells.push(gameField[cashCell.i][cashCell.j+1]);
                if(cashCell.i+1<8 && gameField[cashCell.i+1][cashCell.j].contains == undefined && gameField[cashCell.i+1][cashCell.j].mountains == false) arrayCells.push(gameField[cashCell.i+1][cashCell.j]);
                if(cashCell.j-1>-1 && gameField[cashCell.i][cashCell.j-1].contains == undefined && gameField[cashCell.i][cashCell.j-1].mountains == false) arrayCells.push(gameField[cashCell.i][cashCell.j-1]);
                if(arrayCells.length>0){
                    //let cell = arrayCells[getRandomInt(0,arrayCells.length)]; */
                let cell = getRandomCell(cashCell.i,cashCell.j);
                if (cell != false){
                    cell.contains = new Unit(unit,gameSettings.turnOwner,false);
                    gameField[cashCell.i][cashCell.j].contains.canAction = false;
                    players[gameSettings.turnOwner].gold -= unit[8];
                }else{alert('Некуда разместить юнита'); gameField[cashCell.i][cashCell.j].canAction = true;}
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
    for(let i = 0; i<gameField.length; i++){
        for(let j = 0; j<gameField[i].length; j++){
            let cell = gameField[i][j];
            if(cell.contains != undefined){
                players[cell.contains.owner].counts++;
                cell.contains.canMove = true;
                cell.contains.canAction = true;
                if(newRound == true && cell.resCount > 0 && cell.contains.ability.includes('worker',0)){
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



function Save(){
    cancel();
    let saveString = '';
    let cellsString = '';
    let locSep = '-';
    let globSep = '_';
    let cellSep = '*';
    //gameSettings
    saveString += gameField.length + locSep; //Количество строк
    saveString += gameField[0].length + locSep; // Количество ячеек
    saveString += gameSettings.turnOwner/* + locSep*/; //чей ход
    saveString += globSep;
    players.forEach(player => {
        if(player != undefined){
            saveString += `${player.name}` + locSep;
            saveString += `${player.owner}` + locSep;
            saveString += `${player.faction.name}` + locSep;
            saveString += `${player.gold}` + locSep;
            saveString += `${player.level}` + locSep;
            saveString += `${player.exp}` + locSep;
            player.skills.forEach(skill => {
                saveString += `${skill}` + ',';
            });
        }else saveString += 'null';
        saveString += '*';
    });
    saveString += globSep;
    gameField.forEach(str => {
        str.forEach(cell => {
            cellsString += `${cell.row}` + locSep; //i ячейки
            cellsString += `${cell.column}` + locSep; //j ячейки
            cellsString += `${getBoleanToInt(cell.mountains)}` + locSep; //горы
            cellsString += `${cell.resCount}` + locSep; //золото
            if(cell.contains != undefined){
                cellsString += `${cell.contains.type}` + locSep; //тип юнита
                cellsString += `${cell.contains.owner}` + locSep; //владелец юнита
                cellsString += `${cell.contains.class}` + locSep; // класс юнита
                cellsString += `${cell.contains.hp}` + locSep; //здоровье юнита
                cellsString += `${getBoleanToInt(cell.contains.canMove)}` + locSep;
                cellsString += `${getBoleanToInt(cell.contains.canAction)}`;
            }else cellsString += `null`;
            cellsString += cellSep;
        });
    });
    saveString += cellsString;
    alert (saveString);
}
function Load(){
    let loadString = prompt('вставьте сохранение',undefined);
    if(loadString!=undefined){
        generateGameField();
        let saveArr = loadString.split('_');
        saveArr[0] = saveArr[0].split('-');
        saveArr[1] = saveArr[1].split('*');
        for (let k = 0;k<saveArr[1].length-1;k++){
            saveArr[1][k] = saveArr[1][k].split('-');
        }
        saveArr[2] = saveArr[2].split('*');
        for (let k = 0;k<saveArr[2].length-1;k++){
            saveArr[2][k] = saveArr[2][k].split('-');
        }
        saveArr[1].pop();
        saveArr[2].pop();
        gameSettings.turnOwner = saveArr[0][2];
        players.splice(0,players.length);
        for(let n = 0;n<saveArr[1].length;n++){
            players[n+1] = new Player (saveArr[1][n][0],saveArr[1][n][1],saveArr[1][n][2]);
            players[n+1].gold = saveArr[1][n][3];
            players[n+1].level = saveArr[1][n][4];
            players[n+1].exp = saveArr[1][n][5];
            if(saveArr[1][n][6] != ''){
                saveArr[1][n][6] = saveArr[1][n][6].split(',');
                saveArr[1][n][6].forEach(skill => {
                    players[n+1].skills.push(skill);
                    skillAdd(skill,saveArr[1][n][1]);
                });
            }
        }
        for(let m = 0;m<saveArr[2].length;m++){
            let unit;
            let i = saveArr[2][m][0];
            let j = saveArr[2][m][1];
            gameField[i][j].mountains = getBoleanToInt(saveArr[2][m][2]);
            gameField[i][j].resCount = Number(saveArr[2][m][3]);
            if(saveArr[2][m][4] != 'null'){
                if(saveArr[2][m][4] == 'unit'){
                    switch (saveArr[2][m][6]){
                        case 't1':
                            unit = players[saveArr[2][m][5]].faction.t1;
                            break;
                        case 't2':
                            unit = players[saveArr[2][m][5]].faction.t2;
                            break;
                        case 't3':
                            unit = players[saveArr[2][m][5]].faction.t3;
                            break;
                        case 'warchief':
                            unit = players[saveArr[2][m][5]].faction.warchief;
                            break;
                        default:
                            break;
                    }
                    gameField[i][j].contains = new Unit(unit,Number(saveArr[2][m][5]),false);
                }else if (saveArr[2][m][4] == 'building'){
                    switch (saveArr[2][m][6]){
                        case 'townhall':
                            unit = players[saveArr[2][m][5]].faction.townhall;
                            break;
                        case 'tower':
                            unit = players[saveArr[2][m][5]].faction.tower;
                            break;
                        default:
                            break;
                    }
                    gameField[i][j].contains = new Building(unit,Number(saveArr[2][m][5]),false);
                }
                gameField[i][j].contains.hp = Number(saveArr[2][m][7]);
                gameField[i][j].contains.canMove = getIntToBolean(saveArr[2][m][8]);
                gameField[i][j].contains.canAction = getIntToBolean(saveArr[2][m][9]);
            }
        }
        update();
    }else alert('Не введенно сохранение');
}