"use strict"

//player colors
//red blue orange purple green yellow
//old
//const colorPlayers = ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054'];
                    // gray - red - blue - orange - dark-blue - yellow - purple - pink - green
const colorPlayers =['#bababa','#f59678','#6bccf7','#ffbd76','#8d87be','#fdf777','#cf8fd1','#f39aac','#7eca9c'];


const colorLands = ['#b1c37b','#f0fafa','#e8d479','#bc9565'];
//colors cursors? 
const colorCursor = ['2px solid white','2px solid blue', '2px solid red','2px solid #00ff00','2px solid #19ffff'];
let thisPlayer;
let players = [];
let gameTurn;
let hireUnit;
let turnFlag = 0;
let cashUnit = undefined;
let cashCell = {
    i: undefined,
    j: undefined
}
let gameField = [[],[],[],[],[],[],[],[]];


function pressCell(i,j){
    //alert(i+" "+j);
    if(thisPlayer.owner == gameSettings.turnOwner){
    if(turnFlag == 0 && gameField[i][j] != false && gameField[i][j].availability == true){
        if(gameField[i][j].contains.owner == thisPlayer.owner){
            if((gameField[i][j].contains.canMove == true && gameField[i][j].contains.movePoint!=0) || gameField[i][j].contains.canAction == true){
                lockAllCells();
                if(gameField[i][j].contains.canMove == true){
                    let speed = gameField[i][j].contains.movePoint;
                    if(gameField[i][j].contains.ability.includes('rush',0)){
                        speed += 1;
                    }
                    unlockCells(speed, i, j, 'move');
                }
                    
                if(gameField[i][j].contains.canAction == true){
                    unlockCells(gameField[i][j].contains.range, i, j, 'atk');
                    if(gameField[i][j].contains.ability.includes('surgery',0))
                        unlockCells(1,i,j,'heal');
                    if(gameField[i][j].contains.ability.includes('growUp',0))
                        document.getElementById('btnGrowUp').style.display = 'inline';
                    if(gameField[i][j].contains.ability.includes('darkArmy',0))
                        document.getElementById('btnDarkArmy').style.display = 'inline';
                    if(gameField[i][j].contains.ability.includes('smith',0))
                        document.getElementById('btnSmith').style.display = 'inline';
                    if(gameField[i][j].contains.type == 'building')
                        document.getElementById('btnDelete').style.display = 'inline';
                    if(gameField[i][j].contains.out!=''){
                        let unitArray = gameField[i][j].contains.out.split('-');
                        unitArray.forEach(type => {
                            switch(type){
                                case't1':
                                    document.getElementById('btnBuyT1').style.display = 'inline';
                                    break;
                                case't2':
                                    document.getElementById('btnBuyT2').style.display = 'inline';
                                    break;
                                case't3':
                                    document.getElementById('btnBuyT3').style.display = 'inline';
                                    break;
                                case'warchief':
                                    document.getElementById('btnBuyWarchief').style.display = 'inline';
                                    break;
                                default:
                                    console.log("ошибка");
                                    break;
                            }
                        });
                    }



                }
                if(gameField[i][j].contains.canAction == true && gameField[i][j].contains.canMove == true){
                    if(gameField[i][j].contains.ability.includes('darkStorm',0))
                        document.getElementById('btnDarkStorm').style.display = 'inline';
                }
                turnFlag = 1;
                document.getElementById('btnCancel').style.display = 'inline';
                cashCell.i = gameField[i][j].row;
                cashCell.j = gameField[i][j].column;
    
                cashUnit = gameField[i][j].contains;
    
            }
        }
    }
    else if(turnFlag == 1 && gameField[i][j].contains == false && gameField[i][j].availability == true){
        //moving
        if(gameField[cashCell.i][cashCell.j].contains.canMove == true){
            xhrAction(cashCell.i,cashCell.j,'',i,j,'');
            cancel();
        }
    }
    else if(gameField[i][j].contains!=false && gameField[i][j].contains.owner!=thisPlayer.owner 
    && gameField[i][j].availability == true && turnFlag == 1){
        //attack
        if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
            xhrAction(cashCell.i,cashCell.j,'',i,j,'');
            cancel();
        }
    }
    else if(turnFlag == 1 && gameField[i][j].contains!=false && gameField[i][j].contains.owner==thisPlayer.owner && gameField[i][j].availability == true){
        //heal
        xhrAction(cashCell.i,cashCell.j,'',i,j,'');
        cancel();
    }
    //hire units i dont know how this create =((((
    else if(turnFlag==2 && gameField[i][j].availability==true && gameField[i][j].contains==false){
        xhrAction(cashCell.i,cashCell.j,'hire',i,j,hireUnit);
        cancel();
    }
    else if(turnFlag==2 && gameField[i][j].contains!=false && gameField[i][j].contains.owner==thisPlayer.owner && gameField[i][j].availability == true){
        xhrAction(cashCell.i,cashCell.j,'smith',i,j,'');
        cancel();
    }
    else if((turnFlag == 1 || turnFlag == 2) && (gameField[i][j] == gameField[cashCell.i][cashCell.j] || gameField[i][j].contains==false || (gameField[i][j].availability == false && gameField[i][j].contains.owner==thisPlayer.owner))){
        cancel();
    }
    }   
    //view stats cell unit
    if(gameField[i][j].resCount>0){
        document.getElementById('li_goldCell').textContent = `Золото = ${gameField[i][j].resCount}`;
    }else{
        document.getElementById('li_goldCell').textContent = '';
    }
    if(gameField[i][j].contains != false){
        document.getElementById('li_nameUnit').textContent = `${gameField[i][j].contains.name}`;
        //document.getElementById('li_descriptionUnit').textContent = `${gameField[i][j].contains.description}`;
        document.getElementById('li_ownerUnit').textContent = `Владелец - ${players[gameField[i][j].contains.owner].name}`;
        document.getElementById('li_hpUnit').textContent = `Здоровье - ${gameField[i][j].contains.hp}/${gameField[i][j].contains.hpMax}`;
        document.getElementById('li_attackUnit').textContent = `Атака - ${gameField[i][j].contains.attack}`;
        document.getElementById('li_moveUnit').textContent = `Скорость - ${gameField[i][j].contains.movePoint}`;
        //document.getElementById('li_rangeUnit').textContent = `Дальность атаки - ${gameField[i][j].contains.range}`;
        document.getElementById('li_abilityUnit').textContent = 'Способности - ';
        gameField[i][j].contains.ability.forEach(ability => {
            document.getElementById('li_abilityUnit').textContent += `${ability}, `;
        });
        let actionSpan = (gameField[i][j].contains.canAction)?`green-text`:`red-text`;
        let moveSpan = (gameField[i][j].contains.canMove)?`green-text`:`red-text`;
        let action = `<span class="${moveSpan}">Move</span>\u00A0/\u00A0<span class="${actionSpan}">Action</span>`;
        //document.getElementById('li_canMoveUnit').textContent = (gameField[i][j].contains.canMove)?`Может ходить - Да`:`Может ходить - Нет`;
        document.getElementById('li_canActionUnit').innerHTML = action;
    }else{
        document.getElementById('li_nameUnit').textContent = '';
        //document.getElementById('li_descriptionUnit').textContent = '';
        document.getElementById('li_ownerUnit').textContent = '';
        document.getElementById('li_hpUnit').textContent = '';
        document.getElementById('li_attackUnit').textContent = '';
        document.getElementById('li_moveUnit').textContent = '';
        //document.getElementById('li_rangeUnit').textContent = '';
        document.getElementById('li_abilityUnit').textContent = '';
        //document.getElementById('li_canMoveUnit').textContent = '';
        document.getElementById('li_canActionUnit').innerHTML = '';
    }
}
function spell(type){
    switch(type){
        case"surgery":
        
            xhrAction(cashCell.i,cashCell.j,'heal','','','');
            break;
        case"growUp":
        
            xhrAction(cashCell.i,cashCell.j,'growUp','','','');
            break;
        case"darkArmy":
            xhrAction(cashCell.i,cashCell.j,'darkArmy','','','');
            break;
        case"darkStorm":
            xhrAction(cashCell.i,cashCell.j,'darkStorm','','','');
            break;
        case"smith":
            if(turnFlag==1){
                lockBtns();
                lockAllCells;
                unlockCells(1,cashCell.i,cashCell.j,'smith');
                turnFlag=2;
            }
            break;
        default:
            break;
    }
}

function build(typeBuilding){
    let building;
    let checkLimit = false;
    switch(typeBuilding){
        case 'townhall':
            if(thisPlayer.count_townhalls<gameSettings.limit_townhalls)
                checkLimit = true;
            building = thisPlayer.faction.townhall;
            break;
        case 'tower':
            if(thisPlayer.count_towers<gameSettings.limit_towers)
                checkLimit = true;
            building = thisPlayer.faction.tower;
            break;
        default:
            alert('ошибка в выборе юнита');
            break;
    }
    if(gameField[cashCell.i][cashCell.j].resCount <=0){
        if(checkLimit == true){
            if(thisPlayer.gold>=building[7]){
                xhrAction(cashCell.i,cashCell.j,'build','','',typeBuilding);
                cancel();
            }else alert('недостаточно денег, надо ' + building[7])
        }else alert('Лимит зданий достигнут')
    }else alert('нельзя строить, тут есть золото')

}
function buyUnit(typeUnit){
    if(turnFlag==1){
        let unit;
        let checkLimit=false;
        switch(typeUnit){
            case't1':
                if(thisPlayer.count_workers<gameSettings.limit_workers)
                    checkLimit = true;
                unit = thisPlayer.faction.t1;
                break;
            case 't2':
                if(thisPlayer.count_army<gameSettings.limit_army)
                    checkLimit = true;
                unit = thisPlayer.faction.t2;
                break;
            case 't3':
                if(thisPlayer.count_army<gameSettings.limit_army)
                    checkLimit = true;
                unit = thisPlayer.faction.t3;
                break;
            case 'warchief':
                if(thisPlayer.count_warchiefs<gameSettings.limit_warchiefs){
                    if(thisPlayer.faction.warchief[11]=='2t'){
                        if(thisPlayer.count_towers>=2){
                            checkLimit = true;
                            unit = thisPlayer.faction.warchief;
                        }else alert('Для Вождя нужно 2 башни');
                    }else{
                        checkLimit = true;
                        unit = thisPlayer.faction.warchief;
                    }
                }
                break;
            default:
                alert('ошибка в выборе юнита');
                break;
        }
        if(checkLimit == true){
            if(thisPlayer.gold >= unit[7]){
                if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
                    lockBtns();
                    lockAllCells();
                    unlockCells(1,cashCell.i,cashCell.j,'hire');
                    turnFlag=2;
                    hireUnit=unit[1];
                    //cancel();
                }    
            }else alert('недостаточно денег, надо ' + unit[7])
        }else alert('лимит юнитов достигнут')
    }
}

function levelUp(type,owner,choise){
    //console.log('lvlup start');
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/game/level.php?'+'type='+type+'&owner='+owner+'&choise='+choise);
    xhr.onload = function(){
        //console.log(xhr.response);
        let x = JSON.parse(xhr.response);
        let data = x.data.split('-');
        if(data[0]=='choice'){

            let skillsChoise = [];
            gameSettings.skills.forEach(skill => {
                if(skill.name==data[1] || skill.name==data[2]){
                    skillsChoise.push(skill);
                }
            });
            let result = null;
            while(result == null || (result != 1 && result != 2)){
                result = prompt(`У вас на выбор два навыка: 1)${skillsChoise[0].name} - ${skillsChoise[0].description} 2)${skillsChoise[1].name} - ${skillsChoise[1].description}, введите цифру`);
            }
            levelUp(2,thisPlayer.owner,skillsChoise[result-1].name);
        }
    };
    xhr.send();
    //console.log('lvlup sender');
}

function xhrAction(fi,fj,btn,si,sj,param){
    //console.log('start');
    //console.log(fi+' '+fj+' '+btn+' '+si+' '+sj+' '+param)
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/game/action.php?'+'fi='+fi+'&fj='+fj+'&btn='+btn+'&si='+si+'&sj='+sj+'&param='+param);
    xhr.onload = function(){
        //console.log('ok');
        cancel();
    };
    xhr.onerror=function(){
        console.log('error');
    };
    xhr.send();
}
function deleteUnit(){
    if(confirm("Вы уверены что хотите удалить здание?"))
    xhrAction(cashCell.i,cashCell.j,'delete','','','');
}
function cancel(){
    // gameField[cashCell.i][cashCell.j].contains = cashUnit;
    cashCell.i = undefined;
    cashCell.j = undefined;
    cashUnit = undefined;
    hireUnit = undefined;
    turnFlag = 0;
    unlockAllCells();
    for(let i=0;i<gameField.length;i++){
    for(let j=0;j<gameField[i].length;j++){
        document.getElementById(`${i}-${j}`).style.border = '';
    }
    }
    //update();
    document.getElementById('btnCancel').style.display = 'none';
    lockBtns();
    //document.getElementById('btnSpell').style.display = 'none';
 }
function lockBtns(){
    document.getElementById('btnBuildTownhall').style.display = 'none';
    document.getElementById('btnBuildTower').style.display = 'none';
    document.getElementById('btnBuyT1').style.display = 'none';
    document.getElementById('btnBuyT2').style.display = 'none';
    document.getElementById('btnBuyT3').style.display = 'none';
    document.getElementById('btnBuyWarchief').style.display = 'none';
    document.getElementById('btnGrowUp').style.display = 'none';
    document.getElementById('btnSurgeryHeal').style.display = 'none';
    document.getElementById('btnDarkArmy').style.display = 'none';
    document.getElementById('btnDarkStorm').style.display = 'none';
    document.getElementById('btnSmith').style.display = 'none';
    document.getElementById('btnDelete').style.display = 'none';
}

function endTurn(sur=0){
    if(gameSettings.turnOwner == thisPlayer.owner){
        if((sur==1 && confirm("Вы уверены что хотите сдаться?")) || (sur == 0 && (getCookie("checkEndTurn") == 0 || confirm("Вы уверены что хотите закончить ход?")))){
            let xhr = new XMLHttpRequest();
            let str = sur ? '?sur=1' : '';
            xhr.open('GET',folder+'/includes/game/endturn.php'+str);
            xhr.onload = function(){
            };
            xhr.send();
        }
    }else{
        alert("Сейчас не ваш ход");
    }
};
// -----functions-----
function lockAllCells(){
    for(let i = 0; i<gameField.length; i++ ){
        for(let j = 0; j<gameField[i].length; j++){
            gameField[i][j].availability = false;
            document.getElementById(`${i}-${j}`).style.border = '';
        }
    }
}
function unlockAllCells(){
    for(let i = 0; i<gameField.length; i++ ){
        for(let j = 0; j<gameField[i].length; j++){
            gameField[i][j].availability = true;
        }
    }
}
function unlockCells(count,i,j,type){
    let sMin = -1;
    let iMax = gameSettings.size[0]
    let jMax = gameSettings.size[1]
    let arrayCells = [];
    if (count>0){
        if(i+1<iMax)arrayCells.push(gameField[i+1][j]);
        if(j+1<jMax)arrayCells.push(gameField[i][j+1]);
        if(i-1>sMin)arrayCells.push(gameField[i-1][j]);
        if(j-1>sMin)arrayCells.push(gameField[i][j-1]);
        if(count>1){
            if(i+2<iMax) arrayCells.push(gameField[i+2][j]);
            if(j+2<jMax) arrayCells.push(gameField[i][j+2]);
            if(i-2>sMin)arrayCells.push(gameField[i-2][j]);
            if(j-2>sMin)arrayCells.push(gameField[i][j-2]);
            if(i+1<iMax&&j+1<jMax)arrayCells.push(gameField[i+1][j+1]);
            if(i+1<jMax&&j-1>sMin)arrayCells.push(gameField[i+1][j-1]);
            if(i-1>sMin&&j+1<jMax)arrayCells.push(gameField[i-1][j+1]);
            if(i-1>sMin&&j-1>sMin)arrayCells.push(gameField[i-1][j-1]);
            if(count>2){
                if(i+3<iMax) arrayCells.push(gameField[i+3][j]);
                if(j+3<jMax) arrayCells.push(gameField[i][j+3]);
                if(i-3>sMin)arrayCells.push(gameField[i-3][j]);
                if(j-3>sMin)arrayCells.push(gameField[i][j-3]);
                if(i+2<iMax&&j+1<jMax)arrayCells.push(gameField[i+2][j+1]);
                if(i+2<iMax&&j-1>sMin)arrayCells.push(gameField[i+2][j-1]);
                if(i-2>sMin&&j+1<jMax)arrayCells.push(gameField[i-2][j+1]);
                if(i-2>sMin&&j-1>sMin)arrayCells.push(gameField[i-2][j-1]);
                if(i+1<iMax&&j+2<jMax)arrayCells.push(gameField[i+1][j+2]);
                if(i+1<iMax&&j-2>sMin)arrayCells.push(gameField[i+1][j-2]);
                if(i-1>sMin&&j+2<jMax)arrayCells.push(gameField[i-1][j+2]);
                if(i-1>sMin&&j-2>sMin)arrayCells.push(gameField[i-1][j-2]);
            }
        }
    }
    document.getElementById(`${i}-${j}`).style.border = colorCursor[0];
    switch(type){
        case"move":
            arrayCells.forEach(cell => {
                if(cell.contains==false&&cell.obstacle==0){
                    cell.availability=true;
                    document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[1];
                }
            });
            break;
        case"atk":
            arrayCells.forEach(cell => {
                if(cell.contains!=false&&cell.contains.owner!=thisPlayer.owner){
                    cell.availability=true;
                    document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[2];
                }
            });
            if(gameField[i][j].contains.ability.includes('worker',0)){
                document.getElementById('btnBuildTownhall').style.display = 'inline';
                document.getElementById('btnBuildTower').style.display = 'inline';
            }
            break;
        case"heal":
            if(gameField[i][j].contains.ability.includes('surgery',0)){
                if(gameField[i][j].contains.canAction == true){
                    if(Math.ceil(gameField[i][j].contains.hpMax/2) > gameField[i][j].contains.hp){
                        document.getElementById('btnSurgeryHeal').style.display = 'inline';
                    }
                    arrayCells.forEach(cell => {
                        if(cell.contains!=false&&cell.contains.type!='building'&&cell.contains.owner==thisPlayer.owner&&cell.contains.hpMax>cell.contains.hp){
                            cell.availability=true;
                            document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[3];
                        }
                    });
                }
            }
            break;
        case"hire":
            arrayCells.forEach(cell => {
                if(cell.contains==false&&cell.obstacle==0){
                    cell.availability=true;
                    document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[4];
                }
            });
            break;
        case"smith":
                arrayCells.forEach(cell => {
                    if(cell.contains!=false&&cell.contains.type!='building'&&cell.contains.owner==thisPlayer.owner){
                        cell.availability=true;
                        document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[4];
                    }
                });
                document.getElementById('btnSmith').style.display = 'none';
            break;
        case"darkArmy":
            document.getElementById('btnDarkArmy').style.display = 'inline';
            break;
        case"darkStorm":
            document.getElementById('btnDarkStorm').style.display = 'inline';
            break;
        default:
            break;
    }

    // active BTNs   
    document.getElementById('btnCancel').style.display = 'inline';

}

function getBoleanToInt(c){
    if (c == true){
        return 1;
    }else if (c == false){
        return 0;
    }else return undefined;
}
function getIntToBolean(num){
    if (num == 0){
        return false;
    }else if (num == 1){
        return true;
    }else return undefined;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

// ---------------Game create and update---------------

function loadGameFile(json){
    if (document.getElementById('game-field') === null){
        document.getElementById('game-block').innerHTML = gamestring;
        //console.log(json);
        let row = json.gameSize[0];
        let col = json.gameSize[1];
        for(let i=0;i<row;i++){
            let stroke = document.createElement('div');
            stroke.className = 'gfRow';


            for(let j=0;j<col;j++){
                /*
                let cellAdd = document.createElement('img');
                cellAdd.className = 'gfCell';
                cellAdd.id = `${i}-${j}`;
                cellAdd.src = '';
                cellAdd.onclick = function (){pressCell(i,j)};
                document.getElementById('game-field').appendChild(cellAdd);
                */

                let cellAdd = document.createElement('div');
                cellAdd.className = 'gfCell';
                cellAdd.id = `${i}-${j}`;
                cellAdd.onclick = function (){pressCell(i,j)};
                let anim = document.createElement('span');
                anim.className = 'anim-filter';
                let img = document.createElement('img');
                cellAdd.appendChild(anim);
                cellAdd.appendChild(img);

                stroke.appendChild(cellAdd);

            }
            document.getElementById('game-field').appendChild(stroke);
        }
    }
    gameSettings.size[0] = json.gameSize[0];
    gameSettings.size[1] = json.gameSize[1];
    gameSettings.turnOwner = json.gameTurn;
    gameSettings.land = json.gameLand;
    update(json);
    
    /*
        <div class="game-nfo">
            <div class></div>
        </div>
    */
};

function update(json){
    json.gamePlayers.forEach(el =>{
        if(el!==null&&typeof(el)!=undefined && el.live != false){
            el.count_workers =0;
            el.count_army =0;
            el.count_warchiefs =0;
            el.count_townhalls =0;
            el.count_towers =0;
        }
    })
    getLogin(json);

};

function getLogin(json){
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/login/getloginuser.php');
    xhr.onload = function(){
        let login = xhr.response;
        //console.log(login);
        //let numOwner = 0;
        if(json.local == 1){
            thisPlayer = json.gamePlayers[gameSettings.turnOwner];
        }else{
            json.gamePlayers.forEach(pl => {
                if(login == pl.name){
                    // numOwner = pl.owner;
                    // if (pl.live == true){
                        thisPlayer = pl;
                    //console.log(numOwner);
                }
            });
        }
        if(!'color' in thisPlayer){
            thisPlayer.color = thisPlayer.owner;
        }
        if(thisPlayer.live == false){
            document.getElementById('game-header-player').textContent = 'you lose';        
        }else{
            if(json.gamePlayers[json.gameTurn] == thisPlayer){
                if(thisPlayer.level == 0 && thisPlayer.exp>=gameSettings.level1){
                    levelUp(1,thisPlayer.owner,'');
                }else if(thisPlayer.level == 1 && thisPlayer.exp>=gameSettings.level2){
                    levelUp(1,thisPlayer.owner,'');
                }else if(thisPlayer.faction.name == 'Orcs' && thisPlayer.level == 2 && thisPlayer.exp>=gameSettings.level3){
                    levelUp(1,thisPlayer.owner,'');
                }
            }
            //console.log(thisPlayer);
            loadField(json);
        }
    };
    xhr.send();

}

function loadField(json){
    gameField = json.gameField;
    for(let i=0;i<json.gamePlayers.length;i++){
        if(players[i]!=false){
            players[i] = {
                name: json.gamePlayers[i].name,
                owner: json.gamePlayers[i].owner,
                color: json.gamePlayers[i].color || json.gamePlayers[i].color ==0 ? json.gamePlayers[i].color : json.gamePlayers[i].owner,
                
            }
        }else{
            players[i] = {
            name: ' ',
            owner: 0,
            color: 0,

            }

        }
    }
    
    document.getElementById('game-header-player').textContent = json.gamePlayers[json.gameTurn].name + " (" + json.gamePlayers[json.gameTurn].statistic.score + ")";
    document.getElementById('game-header-player').title = "Игрок - "+json.gamePlayers[json.gameTurn].name+", Очки - "+json.gamePlayers[json.gameTurn].statistic.score+", Позиция - "+json.gamePlayers[json.gameTurn].owner;
    document.getElementById('game-header-player').style.backgroundColor = colorPlayers[players[json.gameTurn].color];
    for(let i=0;i<json.gameField.length;i++){
        for(let j=0;j<json.gameField[i].length;j++){
            let container = document.getElementById(`${i}-${j}`);
            if(gameField[i][j].view == true){
                let titleText = "";
                if(json.gameField[i][j].resCount>0)titleText=`Золото = ${json.gameField[i][j].resCount},`;
                if(json.gameField[i][j].contains != false){
                    container.getElementsByTagName('img')[0].style.backgroundColor = colorPlayers[players[json.gameField[i][j].contains.owner].color];
                    if(json.gameField[i][j].contains.owner == 0){
                        container.getElementsByTagName('img')[0].style.backgroundColor = colorLands[gameSettings.land];
                    }
                    
                    if(json.gameField[i][j].contains.canAction == false && 
                    (json.gameField[i][j].contains.canMove == false || json.gameField[i][j].contains.movePoint == 0)){
                        container.getElementsByTagName('img')[0].style.filter = 'brightness(70%)';
                    }else{
                        container.getElementsByTagName('img')[0].style.filter = 'brightness(100%)';
                    }
                    
                    container.getElementsByTagName('img')[0].setAttribute('src',json.gameField[i][j].contains.image);
                    titleText += `${json.gameField[i][j].contains.name} - hp = ${json.gameField[i][j].contains.hp}/${json.gameField[i][j].contains.hpMax}, atk = ${json.gameField[i][j].contains.attack}, move = ${json.gameField[i][j].contains.movePoint}`;
                    
                    if(json.gamePlayers[json.gameField[i][j].contains.owner].live != false){
                        switch(json.gameField[i][j].contains.class){
                            case't1':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_workers++;
                                break;
                            case't2':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_army++;
                                break;
                            case't3':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_army++;
                                break;
                            case'warchief':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_warchiefs++;
                                break;
                            case'townhall':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_townhalls++;
                                break;
                            case'tower':
                                json.gamePlayers[json.gameField[i][j].contains.owner].count_towers++;
                                break;
                            default:
                                break;
                        }
                    }
                    if(thisPlayer.live!=false){
                        document.getElementById('list_player').style.backgroundColor = colorPlayers[thisPlayer.color];
                        document.getElementById('li_PlayerName').textContent = `${thisPlayer.name}`;
                        let imgs = '';
                        thisPlayer.skills.forEach(skill => {
                            let text = "";
                            switch(skill){
                                case"Strength I":
                                    text = gameSettings.skills[0].description;
                                    break;
                                case"Strength II":
                                    text = gameSettings.skills[1].description;
                                    break;
                                case"Pathfinder":
                                    text = gameSettings.skills[2].description;
                                    break;
                                case"Surgery":
                                    text = gameSettings.skills[3].description;
                                    break;  
                                case"Estates I":
                                    text = gameSettings.skills[4].description;
                                    break;
                                case"Estates II":
                                    text = gameSettings.skills[5].description;
                                    break;
                                case"Engineering":
                                    text = gameSettings.skills[6].description;
                                    break;
                                case"Undead I":
                                    text = gameSettings.skills[7].description;
                                    break;
                                case"Undead II":
                                    text = gameSettings.skills[8].description;
                                    break;
                                case"Scavengers":
                                    text = gameSettings.skills[9].description;
                                    break;
                                default:
                                    text = "ошибка описания";
                                    break;
                            }
                            imgs += `\u00A0\u00A0<img alt="${skill}" src="img/icons/skills/16${skill}.png" title="${text}">`;
                        });
                        if(imgs!=''){
                            document.getElementById('li_PlayerName').insertAdjacentHTML('beforeend', imgs);
                        }
                        document.getElementById('li_PlayerGold').textContent = `Золото = ${thisPlayer.gold}`;
                        document.getElementById('li_PlayerLevel').textContent = `Уровень = ${thisPlayer.level}`;
                        document.getElementById('li_PlayerExp').textContent = `Опыт = ${thisPlayer.exp}`;
                        document.getElementById('li_limit_workers').textContent = `Рабочие - ${thisPlayer.count_workers}/${gameSettings.limit_workers}`;
                        document.getElementById('li_limit_army').textContent = `Армия - ${thisPlayer.count_army}/${gameSettings.limit_army}`;
                        document.getElementById('li_limit_warchiefs').textContent = `Вожди - ${thisPlayer.count_warchiefs}/${gameSettings.limit_warchiefs}`;
                        document.getElementById('li_limit_townhalls').textContent = `Ратуши - ${thisPlayer.count_townhalls}/${gameSettings.limit_townhalls}`;
                        document.getElementById('li_limit_towers').textContent = `Башни - ${thisPlayer.count_towers}/${gameSettings.limit_towers}`;    
                    
                        //name btn
                        document.getElementById('btnBuyT1').textContent = 'Нанять ' + thisPlayer.faction.t1[2];
                        document.getElementById('btnBuyT2').textContent = 'Нанять ' + thisPlayer.faction.t2[2];
                        document.getElementById('btnBuyT3').textContent = 'Нанять ' + thisPlayer.faction.t3[2];
                        document.getElementById('btnBuyWarchief').textContent = 'Нанять ' + thisPlayer.faction.warchief[2];
                        document.getElementById('btnBuildTownhall').textContent = 'Построить ' + thisPlayer.faction.townhall[2];
                        document.getElementById('btnBuildTower').textContent = 'Построить ' + thisPlayer.faction.tower[2];
                    
                    }
                }else{
                    container.getElementsByTagName('img')[0].style.filter = 'brightness(100%)';
                    container.getElementsByTagName('img')[0].style.backgroundColor = colorLands[gameSettings.land];
                    if(json.gameField[i][j].obstacle != 0){
                        container.getElementsByTagName('img')[0].setAttribute('src',"img/obstacle/"+gameSettings.land+json.gameField[i][j].obstacle+".png");
                        titleText = 'Непроходимая клетка';
                    }else{
                        container.getElementsByTagName('img')[0].setAttribute('src',"img/null.png");
                        if (json.gameField[i][j].resCount > 0){
                            container.getElementsByTagName('img')[0].setAttribute('src',"img/goldOre.png");
                        }
                    }
                }
                container.title = titleText;
                container.style.border = '';
            }
            else{
                container.getElementsByTagName('img')[0].style.backgroundColor = '#000000';
                container.getElementsByTagName('img')[0].setAttribute('src',"img/null.png");
                container.title = 'Туман войны';
                container.style.border = '';
            }
        }
    }
    //score
    let menuScore = document.getElementById('menu-score').getElementsByTagName('table')[0].getElementsByTagName('tbody')[0];
    menuScore.innerHTML = '';
    for(let i=0;i<json.gamePlayers.length-1;i++){
        let k = i+1;
        let row = menuScore.insertRow(i);
        if(json.gamePlayers[k].live != false){
            row.style.backgroundColor = colorPlayers[players[k].color];
        }
        let cellId = row.insertCell(0);
        cellId.innerHTML = k;
        let cellName = row.insertCell(1);
        cellName.innerHTML = json.gamePlayers[k].name;
        let cellScore = row.insertCell(2);
        cellScore.innerHTML = json.gamePlayers[k].statistic.score;

    }


    cancel();
    if(getCookie("enableAnimation") == 1){
        checkAnimation(json);
    }
    
}

function checkAnimation(json){
    let colorAnimation =  { // here not ')' since it is added to the 'animation' function
        "white" : "rgba(255, 255, 255,",
        "red" : "rgba(200, 0, 0,",
        "green" : "rgba(0, 255, 0,",
        "blue" : "rgba(0, 0, 255,",
        "aqua" : "rgba(0, 150, 255,",
        "light" : "rgba(255, 255, 180,",
        "gold" : "rgba(255, 255, 50,",
        "black" : "rgba(1, 1, 1,",
        "darkPurple" : "rgba(100, 0, 100,",
        "darkGreen" : "rgba(0, 150, 0,",
    };
    let fi = json.animation[0];
    let fj = json.animation[1];
    let type = json.animation[2];
    let si = json.animation[3];
    let sj = json.animation[4];
    let variant = json.animation[5];
    let fContainer = undefined;
    let sContainer = undefined;
    let n;
    let m;
    let sMin = -1;
    let iMax = gameSettings.size[0];
    let jMax = gameSettings.size[1];
    if(fi !== '' && fj !== ''){
        if(json.gameField[fi][fj].view == true && document.getElementById(`${fi}-${fj}`) !== null){
            fContainer = document.getElementById(`${fi}-${fj}`);
        }
    }
    if(si !== '' && sj !== ''){
        if(json.gameField[si][sj].view == true && document.getElementById(`${si}-${sj}`) !== null){
            sContainer = document.getElementById(`${si}-${sj}`);
        }
    }
    let value = [0.40,0.40,0.40,0.40,0.40,];
    switch(type){
        case 'move':
            animation(fContainer,colorAnimation['white']);
            animation(sContainer,colorAnimation['white']); 
            break;
        case 'attack':
            animation(fContainer,colorAnimation['white']);
            animation(sContainer,colorAnimation['red']);
            break;
        case 'heal':
            if(variant == 'self'){
                animation(fContainer,colorAnimation['green']);
            }else if(variant == 'other'){
                animation(fContainer,colorAnimation['white']);
                animation(sContainer,colorAnimation['green']);
            }
            break;
        case 'magic':
            animation(fContainer,colorAnimation['white']);
            animation(sContainer,colorAnimation['aqua']);
            break;
        case 'chainLight':
            // aqua
            animation(fContainer,colorAnimation['white']);
            animation(sContainer,colorAnimation['aqua']);
            n = Number(si);
            m = Number(sj);
            switch(variant){
                case "up":
                    if(n-1>sMin){
                        if(json.gameField[n-1][m].view == true && document.getElementById(`${n-1}-${m}`) !== null){
                            animation(document.getElementById(`${n-1}-${m}`),colorAnimation['aqua']);
                        }
                    }
                    break;
                case "right":
                    if(m+1<jMax){
                        if(json.gameField[n][m+1].view == true && document.getElementById(`${n}-${m+1}`) !== null){
                            animation(document.getElementById(`${n}-${m+1}`),colorAnimation['aqua']);
                        }
                    }
                    break;
                case "down":
                    if(n+1<Imax){
                        if(json.gameField[n+1][m].view == true && document.getElementById(`${n+1}-${m}`) !== null){
                            animation(document.getElementById(`${n+1}-${m}`),colorAnimation['aqua']);
                        }
                    }
                    break;
                case "left":
                    if(m-1>sMin){
                        if(json.gameField[n][m-1].view == true && document.getElementById(`${n}-${m-1}`) !== null){
                            animation(document.getElementById(`${n}-${m-1}`),colorAnimation['aqua']);
                        }
                    }
                    break;
                default:
                    break;
                        
            }
            break;
        case 'smith':
            animation(fContainer,colorAnimation['white']);
            animation(sContainer,colorAnimation['gold']);
            break;
        case 'darkArmy':
            animation(fContainer,colorAnimation['darkPurple']);
            break;
        case 'darkStorm':
            animation(fContainer,colorAnimation['darkPurple']);
            let array = [undefined,undefined,undefined,undefined];
            n = Number(fi);
            m = Number(fj);
            if(n+1<iMax) if(json.gameField[n+1][m].view == true && document.getElementById(`${n+1}-${m}`) !== null) 
            array[0] = document.getElementById(`${n+1}-${m}`)
            if(m+1<jMax) if(json.gameField[n][m+1].view == true && document.getElementById(`${n}-${m+1}`) !== null) 
            array[1] = document.getElementById(`${n}-${m+1}`)
            if(n-1>sMin)if(json.gameField[n-1][m].view == true && document.getElementById(`${n-1}-${m}`) !== null) 
            array[2] = document.getElementById(`${n-1}-${m}`)
            if(m-1>sMin)if(json.gameField[n][m-1].view == true && document.getElementById(`${n}-${m-1}`) !== null) 
            array[3] = document.getElementById(`${n}-${m-1}`)
            
            for(let i=0;i<array.length;i++){
                if(array[i]!=undefined){
                    animation(array[i],colorAnimation['black']);
                }
            }
            break;
        
        default:
            break;
    }


}
function animation(container,color){
    let value = 0.50;
    if(container != undefined){
        let anime = setInterval(function () { 
            value = value - 0.05; 
            container.getElementsByClassName('anim-filter')[0].style.backgroundColor = color + value + ")";//белый почти все 
            if(value < 0.01){
                //console.log('end');
                clearInterval(anime);
            }
        }, 100); 
        //setTimeout(() => clearInterval(anime) ,5000);
    } 

}



//'<h5 id="game-header-h5"></h5>'+
const gamestring = '<div id="game-header">'+
'<button id="btnEndTurn" onclick="endTurn()">Закончить ход</button>'+
'<h4 id="game-header-player"></h4>'+
'<button id="btnDialogMenu" onclick="dialogMenu(1)">Меню</button>'+
'</div>'+
'<div id="game-field">   </div>'+
'<div id="game-btns">'+
'<button id="btnCancel" onclick="cancel()" style="display: none">Отмена</button>'+
'<button id="btnBuildTownhall" onclick="build(`townhall`)" style="display: none">Построить Townhall</button>'+
'<button id="btnBuildTower" onclick="build(`tower`)" style="display: none">Построить Tower</button>'+
'<button id="btnBuyT1" onclick="buyUnit(`t1`)" style="display: none">Нанять T1</button>'+
'<button id="btnBuyT2" onclick="buyUnit(`t2`)" style="display: none">Нанять T2</button>'+
'<button id="btnBuyT3" onclick="buyUnit(`t3`)" style="display: none">Нанять T3</button>'+
'<button id="btnBuyWarchief" onclick="buyUnit(`warchief`)" style="display: none">Нанять Warchief</button>'+
'<button id="btnGrowUp" onclick="spell(`growUp`)" style="display: none">Стать Энтом</button>'+
'<button id="btnSurgeryHeal" onclick="spell(`surgery`)" style="display: none">Лечение</button>'+
'<button id="btnDarkArmy" onclick="spell(`darkArmy`)" style="display: none">Армия Тьмы</button>'+
'<button id="btnDarkStorm" onclick="spell(`darkStorm`)" style="display: none">Темная буря</button>'+
'<button id="btnSmith" onclick="spell(`smith`)" style="display: none">Ковка</button>'+
'<button id="btnDelete" onclick="deleteUnit()" style="display: none">Удалить</button>'+
'</div>'+
'<div id="game-info">'+
'<ul id="list_player">'+
    '<li id="li_PlayerName"></li>'+
    '<li id="li_PlayerGold"></li>'+
    '<li id="li_PlayerLevel"></li>'+
    '<li id="li_PlayerExp"></li>'+
    '<li id="li_limit_workers"></li>'+
    '<li id="li_limit_army"></li>'+
    '<li id="li_limit_warchiefs"></li>'+
    '<li id="li_limit_townhalls"></li>'+
    '<li id="li_limit_towers"></li>'+
'</ul>'+
'<ul id="list_unit">'+
    '<li id="li_goldCell"></li>'+
    '<li id="li_nameUnit"></li>'+
    '<li id="li_ownerUnit"></li>'+
    '<li id="li_hpUnit"></li>'+
    '<li id="li_attackUnit"></li>'+
    '<li id="li_moveUnit"></li>'+
    '<li id="li_rangeUnit"></li>'+
    '<li id="li_abilityUnit"></li>'+
    '<li id="li_canActionUnit"></li>'+
'</ul>'+
'</div> ';

const gameSettings = {
    turnOwner: 0,
    size:[8,8],
    land: 0,
    level1: 5,
    level2: 15,
    level3: 30,
    limit_workers : 6,
    limit_army : 4,
    limit_warchiefs : 1,
    limit_townhalls : 2,
    limit_towers : 4,
    //skills:['Strength I','Strength II','Pathfinder','Surgery','Estates I', 'Estates'];
    skills:[
        {name:'Strength I', description:'Увеличивает силу атаки Вождя на 1'},
        {name:'Endurance', description:'Увеличивает здоровье Вождя на 2'},
        {name:'Pathfinder', description:'Увеличивает скорость Вождя на 1'},
        {name:'Surgery', description:'Позволяет вождю лечить себя или союзников'},
        {name:'Estates I', description:'Единовременно дает 5 золота'},
        {name:'Estates II', description:'Каждый ход дает 1 золото'},
        {name:'Engineering', description:'Все здания получают +1 к прочности'},
        {name:'Undead I', description:'Увеличивает здоровье Лича на 1 ед., зомби получают спсобность "infect"'},
        {name:'Undead II', description:'Увеличивает здоровье Лича на 1 ед., Лич получает способность "darkStorm"'},
        {name:"Scavengers", description:"Warchief получает спсобность 'scavenger', Гоблины при создании имеют способность 'rush'"},// и Warchief
    ]
};