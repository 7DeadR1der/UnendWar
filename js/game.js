"use strict"

//player colors
//red blue orange purple green yellow
const colorPlayers = ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054'];
//colors cursors?
const colorCursor = ['2px solid white','2px solid blue', '2px solid red','2px solid #00ff00','2px solid #30d5c8'];
let thisPlayer;
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
            if(gameField[i][j].contains.canMove == true || gameField[i][j].contains.canAction == true){
                lockAllCells();
                if(gameField[i][j].contains.canMove == true)
                    unlockCells(gameField[i][j].contains.movePoint, i, j, 'move');
                    
                if(gameField[i][j].contains.canAction == true)
                    unlockCells(gameField[i][j].contains.range, i, j, 'atk');
                if(gameField[i][j].contains.ability.includes('surgery',0))
                    unlockCells(1,i,j,'heal');
                if(gameField[i][j].contains.out!='' && gameField[i][j].contains.canAction == true){
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
    else if((turnFlag == 1 || turnFlag == 2) && (gameField[i][j] == gameField[cashCell.i][cashCell.j] || gameField[i][j].contains==false || (gameField[i][j].availability == false && gameField[i][j].contains.owner==thisPlayer.owner))){
        cancel();
    }
    }   
    //view stats cell unit
    if(gameField[i][j].contains != false){
        document.getElementById('li_nameUnit').textContent = `${gameField[i][j].contains.name}`;
        document.getElementById('li_descriptionUnit').textContent = `${gameField[i][j].contains.description}`;
        document.getElementById('li_ownerUnit').textContent = `Владелец - ${thisPlayer.name}`;
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
function heal(type){
    xhrAction(cashCell.i,cashCell.j,'heal','','','');
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
            if(thisPlayer.gold>=building[8]){
                xhrAction(cashCell.i,cashCell.j,'build','','',typeBuilding);
                cancel();
            }else alert('недостаточно денег, надо ' + building[8])
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
                    if(thisPlayer.faction.warchief[12]=='2t'){
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
            if(thisPlayer.gold >= unit[8]){
                if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
                    lockBtns();
                    unlockCells(1,cashCell.i,cashCell.j,'hire');
                    turnFlag=2;
                    hireUnit=unit[1];
                    //cancel();
                }    
            }else alert('недостаточно денег, надо ' + unit[8])
        }else alert('лимит юнитов достигнут')
    }
}

function xhrAction(fi,fj,btn,si,sj,param){
    //console.log('start');
    //console.log(fi+' '+fj+' '+btn+' '+si+' '+sj+' '+param)
    let xhr = new XMLHttpRequest();
    xhr.open('GET','/game.exe/includes/game/action.php?'+'fi='+fi+'&fj='+fj+'&btn='+btn+'&si='+si+'&sj='+sj+'&param='+param);
    xhr.onload = function(){
        //console.log('ok');
        cancel();
    };
    xhr.onerror=function(){
        //console.log('error');
    };
    xhr.send();
}
function cancel(){
    // gameField[cashCell.i][cashCell.j].contains = cashUnit;
    cashCell.i = undefined;
    cashCell.j = undefined;
    cashUnit = undefined;
    hireUnit = undefined;
    turnFlag = 0;
    unlockAllCells();
    for(let i=0;i<8;i++){
    for(let j=0;j<8;j++){
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
    document.getElementById('btnSurgeryHeal').style.display = 'none';
}

function endTurn(){
    if(gameSettings.turnOwner == thisPlayer.owner){
        if(confirm("Вы уверены что хотите закончить ход?")){
            let xhr = new XMLHttpRequest();
            xhr.open('GET','/game.exe/includes/game/endturn.php');
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
    let arrayCells = [];
    if (count>0){
        if(i+1<8) arrayCells.push(gameField[i+1][j]);
        if(j+1<8) arrayCells.push(gameField[i][j+1]);
        if(i-1>-1)arrayCells.push(gameField[i-1][j]);
        if(j-1>-1)arrayCells.push(gameField[i][j-1]);
        if(count>1){
            if(i+2<8) arrayCells.push(gameField[i+2][j]);
            if(j+2<8) arrayCells.push(gameField[i][j+2]);
            if(i-2>-1)arrayCells.push(gameField[i-2][j]);
            if(j-2>-1)arrayCells.push(gameField[i][j-2]);
            if(i+1<8&&j+1<8)  arrayCells.push(gameField[i+1][j+1]);
            if(i+1<8&&j-1>-1) arrayCells.push(gameField[i+1][j-1]);
            if(i-1>-1&&j+1<8) arrayCells.push(gameField[i-1][j+1]);
            if(i-1>-1&&j-1>-1)arrayCells.push(gameField[i-1][j-1]);
            if(count>2){
                if(i+3<8) arrayCells.push(gameField[i+3][j]);
                if(j+3<8) arrayCells.push(gameField[i][j+3]);
                if(i-3>-1)arrayCells.push(gameField[i-3][j]);
                if(j-3>-1)arrayCells.push(gameField[i][j-3]);
                if(i+2<8&&j+1<8)  arrayCells.push(gameField[i+2][j+1]);
                if(i+2<8&&j-1>-1) arrayCells.push(gameField[i+2][j-1]);
                if(i-2>-1&&j+1<8) arrayCells.push(gameField[i-2][j+1]);
                if(i-2>-1&&j-1>-1)arrayCells.push(gameField[i-2][j-1]);
                if(i+1<8&&j+2<8)  arrayCells.push(gameField[i+1][j+2]);
                if(i+1<8&&j-2>-1) arrayCells.push(gameField[i+1][j-2]);
                if(i-1>-1&&j+2<8) arrayCells.push(gameField[i-1][j+2]);
                if(i-1>-1&&j-2>-1)arrayCells.push(gameField[i-1][j-2]);
            }
        }
    }

    switch(type){
        case"move":
            arrayCells.forEach(cell => {
                if(cell.contains==false&&cell.mountains==false){
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
            break;
        case"heal":
            if(gameField[i][j].contains.ability.includes('surgery',0)){
                if(gameField[i][j].contains.hpMax>gameField[i][j].contains.hp){
                    document.getElementById('btnSurgeryHeal').style.display = 'inline';
                }
                arrayCells.forEach(cell => {
                    if(cell.contains!=false&&cell.contains.type!='building'&&cell.contains.owner==thisPlayer.owner&&cell.contains.hpMax>cell.contains.hp){
                        cell.availability=true;
                        document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[3];
                    }
                });
            }
            break;
        case"hire":
            arrayCells.forEach(cell => {
                if(cell.contains==false&&cell.mountains==false){
                    cell.availability=true;
                    document.getElementById(`${cell.row}-${cell.column}`).style.border = colorCursor[4];
                }
            });
            break;
        case"view":
            gameField[i][j].view = true;
            arrayCells.forEach(cell => {
                cell.view = true;
            });
            break;
        default:
            break;
    }

    // active BTNs   
    if(gameField[i][j].contains.ability.includes('worker',0)){
        document.getElementById('btnBuildTownhall').style.display = 'inline';
        document.getElementById('btnBuildTower').style.display = 'inline';
    }
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

// -----Game create and update-----

function loadGameFile(json){
    if (document.getElementById('game-field') === null){
        document.getElementById('game-block').innerHTML = gamestring;
        //console.log(json);
        for(let i=0;i<8;i++){
            for(let j=0;j<8;j++){
                let cellAdd = document.createElement('img');
                cellAdd.className = 'gfCell';
                cellAdd.id = `${i}-${j}`;
                cellAdd.src = '';
                cellAdd.onclick = function (){pressCell(i,j)};
                document.getElementById('game-field').appendChild(cellAdd);
    
            }
        }
    }
    gameSettings.turnOwner = json.gameTurn;
    update(json);
    
    /*
        <div class="game-nfo">
            <div class></div>
        </div>
    */
};

function update(json){
    json.gamePlayers.forEach(el =>{
        if(el!==null&&typeof(el)!=undefined && el != false){
            el.count_workers =0;
            el.count_army =0;
            el.count_warchiefs =0;
            el.count_townhalls =0;
            el.count_towers =0;
        }
    })
    getLogin(json);

};
function loadField(json){
    document.getElementById('game-header-player').textContent = json.gamePlayers[json.gameTurn].name;
    document.getElementById('game-header-player').style.backgroundColor = colorPlayers[json.gameTurn];
    gameField = json.gameField;
    gameField.forEach(row => {
        row.forEach(cell => {
            if(cell.contains!=false&&cell.contains.owner==thisPlayer.owner){
                let count;
                if(cell.contains.movePoint>cell.contains.range)count=cell.contains.movePoint;
                else count=cell.contains.range;
                if(count==0)count=1;
                unlockCells(count,cell.row,cell.column,"view");
            }
        })
    });
    for(let i=0;i<8;i++){
        for(let j=0;j<8;j++){
            if(gameField[i][j].view == true){
                let titleText = "";
                if(json.gameField[i][j].resCount>0)titleText=`Золото = ${json.gameField[i][j].resCount},`;
                if(json.gameField[i][j].contains != false){
                    //gameField fill
                    //gameField[i][j] = json.gameField[i][j];
                    /*
                    if(thisPlayer.owner = json.gameField[i][j].contains.owner){
                        gameField[i][j] = json.gameField[i][j];
                    }else{
                        gameField[i][j].contains=undefined;
                    }
                    */
                    document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[json.gameField[i][j].contains.owner];
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
                            json.gamePlayers[json.gameField[i][j].contains.owner].count_warcheifs++;
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
                    document.getElementById('list_player').style.backgroundColor = colorPlayers[thisPlayer.owner];
                    document.getElementById('li_PlayerName').textContent = `${thisPlayer.name}`;
                    document.getElementById('li_PlayerGold').textContent = `Золото = ${thisPlayer.gold}`;
                    document.getElementById('li_PlayerLevel').textContent = `Уровень = ${thisPlayer.level}`;
                    document.getElementById('li_PlayerExp').textContent = `Опыт = ${thisPlayer.exp}`;
                    document.getElementById('li_limit_workers').textContent = `Рабочие - ${thisPlayer.count_workers}/${gameSettings.limit_workers}`;
                    document.getElementById('li_limit_army').textContent = `Армия - ${thisPlayer.count_army}/${gameSettings.limit_army}`;
                    document.getElementById('li_limit_warchiefs').textContent = `Вожди - ${thisPlayer.count_warchiefs}/${gameSettings.limit_warchiefs}`;
                    document.getElementById('li_limit_townhalls').textContent = `Ратуши - ${thisPlayer.count_townhalls}/${gameSettings.limit_townhalls}`;
                    document.getElementById('li_limit_towers').textContent = `Башни - ${thisPlayer.count_towers}/${gameSettings.limit_towers}`;
                    
                    document.getElementById(`${i}-${j}`).setAttribute('src',json.gameField[i][j].contains.image);
                    titleText += `${json.gameField[i][j].contains.name} - hp = ${json.gameField[i][j].contains.hp}/${json.gameField[i][j].contains.hpMax}, atk = ${json.gameField[i][j].contains.attack}, move = ${json.gameField[i][j].contains.movePoint}`;
                    
                }else{
                    if(json.gameField[i][j].mountains == true){
                        document.getElementById(`${i}-${j}`).setAttribute('src',"img/mountains.png");
                        titleText = 'Горы, непроходимая клетка';
                    }else{
                        document.getElementById(`${i}-${j}`).setAttribute('src',"img/null.png");
                        if (json.gameField[i][j].resCount > 0){
                            document.getElementById(`${i}-${j}`).setAttribute('src',"img/goldOre.png");
                        }
                        document.getElementById(`${i}-${j}`).style.backgroundColor = '#b1c37b';
                    }
                }
                document.getElementById(`${i}-${j}`).title = titleText;
                document.getElementById(`${i}-${j}`).style.border = '';
            }
            else{
                gameField[i][j].resCount=0;
                gameField[i][j].contains=false;
                document.getElementById(`${i}-${j}`).style.backgroundColor = '#000000';
                document.getElementById(`${i}-${j}`).setAttribute('src',"img/null.png");
                document.getElementById(`${i}-${j}`).title = 'Туман войны';
                document.getElementById(`${i}-${j}`).style.border = '';
            }
        }
    }
    cancel();
}


function getLogin(json){
    let xhr = new XMLHttpRequest();
    xhr.open('GET','/game.exe/includes/login/getloginuser.php');
    xhr.onload = function(){
        let login = xhr.response;
        //console.log(login);
        let numOwner = 0;
        json.gamePlayers.forEach(pl => {
            if(login == pl.name){
                numOwner = pl.owner;
                //console.log(numOwner);
            }
        });
        if(numOwner==0){
            thisPlayer = false
        }else{
            thisPlayer = json.gamePlayers[numOwner];
        }
        if(thisPlayer == false){
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

function levelUp(type,owner,choise){
    //console.log('lvlup start');
    let xhr = new XMLHttpRequest();
    xhr.open('GET','/game.exe/includes/game/level.php?'+'type='+type+'&owner='+owner+'&choise='+choise);
    xhr.onload = function(){
        //console.log(xhr.response);
        let data = xhr.response.split('-');
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


const gamestring = '<div id="game-header">'+
//'<h5 id="game-header-h5"></h5>'+
'<button id="btnEndTurn" onclick="endTurn()">Закончить ход</button>'+
'<h4 id="game-header-player"></h4>'+
'<button id="btnExitGame" onclick="exitRoom()">Выйти из Игры</button>'+
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
'<button id="btnSurgeryHeal" onclick="heal(`surgery`)" style="display: none">Лечение</button>'+
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
'<ul>'+
    '<li id="li_goldCell"></li>'+
    '<li id="li_nameUnit"></li>'+
    '<li id="li_descriptionUnit"></li>'+
    '<li id="li_ownerUnit"></li>'+
    '<li id="li_hpUnit"></li>'+
    '<li id="li_attackUnit"></li>'+
    '<li id="li_moveUnit"></li>'+
'</ul>'+
'</div>;';

const gameSettings = {
    turnOwner: 0,
    level1: 5,
    level2: 15,
    level3: 30,
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
        {name:'Estates II', description:'Каждый ход дает 1 золото'},
        {name:'Engineering', description:'Все здания получают +1 к прочности'},
        {name:'Undead I', description:'Позволяет Личу призывать скелетов в количестве равному количеству Некрополисов'},
        {name:'Undead II', description:'Увеличивает здоровье Лича на 5'},
    
    ]
};