"use strict"
//player colors
//red blue orange purple
const colorPlayers = ['#fc9393', '#9393fc', '#fcb64d', '#b64dfc'];
//colors cursors?
const colorCursor = ['2px solid white','2px solid blue', '2px solid red','2px solid #00ff00'];

//document.getElementById('btnCancel').style.opacity = '0';
let gameField = new Array(8);
/*async*/ function newGameGenerate(type){
    if(confirm('Вы уверены, что хотите начать новую игру?')){
        gameSettings.turnOwner = 1;
        //new players
        //let countPlayers = prompt('1 или 2 игрока? вводить число', '')
        //let countPlayers = 2;
        let factionType = 'Kingdom';
        let result=null;
        while(result==null || (result!=1 && result!=2 && result!=3 && result!=4))
        result = prompt('Выберите карту (Введите цифру): 1)LastRefuge(1х1) 2)Classic(1x1) 3)LostTemple(4 ffa) 4)CentourGrove(4 ffa)');
        let countPlayers = (result == 3 || result == 4) ? 4 : 2;
        for (let k = 1; k<=countPlayers; k++){
            let name = prompt('Введите имя игрока '+ k, '');
            players[k] = new Player(name, k,factionType);
            players[k].faction.start(k);
        }
        
        //Generate Game Field
        generateGameField();
        //create townhalls and peasants
        mapMaker(result);
        update();
    }
}
function generateGameField(){
    document.querySelector('div.gridGameField').innerHTML = '';
    for(let i=0;i<gameField.length;i++){
        let gameRows = new Array(8);
        for (let j=0;j<gameRows.length;j++){ 
            gameRows[j] = {
                contains: undefined,
                resCount: 0,
                availability: true,
                mountains: false,
                row: i,
                column: j
            };
            let cellAdd = document.createElement('img');
            cellAdd.className = 'gfCell';
            cellAdd.id = `${i}-${j}`;
            cellAdd.src = '';
            cellAdd.onclick = function () {pressCell(i,j)};
            document.querySelector('div.gridGameField').appendChild(cellAdd);
        }
        gameField[i] = gameRows;
    }
}


function update(){
    players.forEach(el => {
        if(el != undefined){
            el.count_workers =0;
            el.count_army =0;
            el.count_warchiefs =0;
            el.count_townhalls =0;
            el.count_towers =0;
            
            
        }
    });
    for(let i=0;i<8;i++){
        for (let j=0;j<8;j++){
            let titleText = "";
            if (gameField[i][j].resCount>0)titleText = `Золото = ${gameField[i][j].resCount}, `;
            if(gameField[i][j].contains != undefined){
                document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[gameField[i][j].contains.owner-1];
                switch(gameField[i][j].contains.class){
                    case't1':
                        players[gameField[i][j].contains.owner].count_workers++;
                        break;
                    case't2':
                        players[gameField[i][j].contains.owner].count_army++;
                        break;
                    case't3':
                        players[gameField[i][j].contains.owner].count_army++;
                        break;
                    case'warchief':
                        players[gameField[i][j].contains.owner].count_warchiefs++;
                        break;
                    case'townhall':
                        players[gameField[i][j].contains.owner].count_townhalls++;
                        break;
                    case'tower':
                        players[gameField[i][j].contains.owner].count_towers++;
                        break;
                    default:
                        break;
                }
                document.getElementById(`${i}-${j}`).setAttribute('src',gameField[i][j].contains.image);
                titleText += `${gameField[i][j].contains.name} - hp = ${gameField[i][j].contains.hp}/${gameField[i][j].contains.hpMax}, atk = ${gameField[i][j].contains.attack}, move = ${gameField[i][j].contains.movePoint}`
                
            }else{
                if(gameField[i][j].mountains == true){
                    document.getElementById(`${i}-${j}`).setAttribute('src',"img/mountains.png");
                    document.getElementById(`${i}-${j}`).style.backgroundColor = '#aacd95';
                    titleText = 'Горы, непроходимая клетка';
                }else{
                    document.getElementById(`${i}-${j}`).setAttribute('src',"img/null.png");
                    if (gameField[i][j].resCount > 0){
                        document.getElementById(`${i}-${j}`).style.backgroundColor = 'yellow';
                    }else document.getElementById(`${i}-${j}`).style.backgroundColor = '#aacd95';
                }
            }
            //document.getElementById(`${i}-${j}`).removeAttribute('style');
            document.getElementById('list_player').style.backgroundColor = colorPlayers[gameSettings.turnOwner-1];
            document.getElementById(`${i}-${j}`).title = titleText;
            document.getElementById(`${i}-${j}`).style.border = '';
            document.getElementById('li_PlayerName').textContent = `${players[gameSettings.turnOwner].name}`;
            document.getElementById('li_PlayerGold').textContent = `Золото = ${players[gameSettings.turnOwner].gold}`;
            document.getElementById('li_PlayerLevel').textContent = `Уровень = ${players[gameSettings.turnOwner].level}`;
            document.getElementById('li_PlayerExp').textContent = `Опыт = ${players[gameSettings.turnOwner].exp}`;
            document.getElementById('li_limit_workers').textContent = `Рабочие - ${players[gameSettings.turnOwner].count_workers}/${gameSettings.limit_workers}`;
            document.getElementById('li_limit_army').textContent = `Армия - ${players[gameSettings.turnOwner].count_army}/${gameSettings.limit_army}`;
            document.getElementById('li_limit_warchiefs').textContent = `Вожди - ${players[gameSettings.turnOwner].count_warchiefs}/${gameSettings.limit_warchiefs}`;
            document.getElementById('li_limit_townhalls').textContent = `Ратуши - ${players[gameSettings.turnOwner].count_townhalls}/${gameSettings.limit_townhalls}`;
            document.getElementById('li_limit_towers').textContent = `Башни - ${players[gameSettings.turnOwner].count_towers}/${gameSettings.limit_towers}`;
        }
    }
}


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
function unlockCells(count,i,j, type){
    let k;
    document.getElementById(`${i}-${j}`).style.border = colorCursor[0];
    if(type == 'move'){
        if (count>0){
            if(i+1<8) {k = gameField[i+1][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+1}-${j}`).style.border = colorCursor[1];}}
            if(j+1<8) {k = gameField[i][j+1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j+1}`).style.border = colorCursor[1];}}
            if(i-1>-1){k = gameField[i-1][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-1}-${j}`).style.border = colorCursor[1];}}
            if(j-1>-1){k = gameField[i][j-1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j-1}`).style.border = colorCursor[1];}}
            if(count>1){
                if(i+2<8) {k = gameField[i+2][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+2}-${j}`).style.border = colorCursor[1];}}
                if(j+2<8) {k = gameField[i][j+2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j+2}`).style.border = colorCursor[1];}}
                if(i-2>-1){k = gameField[i-2][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-2}-${j}`).style.border = colorCursor[1];}}
                if(j-2>-1){k = gameField[i][j-2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j-2}`).style.border = colorCursor[1];}}
                if(i+1<8&&j+1<8)  {k = gameField[i+1][j+1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+1}-${j+1}`).style.border = colorCursor[1];}}
                if(i+1<8&&j-1>-1) {k = gameField[i+1][j-1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+1}-${j-1}`).style.border = colorCursor[1];}}
                if(i-1>-1&&j+1<8) {k = gameField[i-1][j+1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-1}-${j+1}`).style.border = colorCursor[1];}}
                if(i-1>-1&&j-1>-1){k = gameField[i-1][j-1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-1}-${j-1}`).style.border = colorCursor[1];}}
                if(count>2){
                    if(i+3<8) {k = gameField[i+3][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+3}-${j}`).style.border = colorCursor[1];}}
                    if(j+3<8) {k = gameField[i][j+3];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j+3}`).style.border = colorCursor[1];}}
                    if(i-3>-1){k = gameField[i-3][j];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-3}-${j}`).style.border = colorCursor[1];}}
                    if(j-3>-1){k = gameField[i][j-3];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i}-${j-3}`).style.border = colorCursor[1];}}
                    if(i+2<8&&j+1<8)  {k = gameField[i+2][j+1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+2}-${j+1}`).style.border = colorCursor[1];}}
                    if(i+2<8&&j-1>-1) {k = gameField[i+2][j-1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+2}-${j-1}`).style.border = colorCursor[1];}}
                    if(i-2>-1&&j+1<8) {k = gameField[i-2][j+1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-2}-${j+1}`).style.border = colorCursor[1];}}
                    if(i-2>-1&&j-1>-1){k = gameField[i-2][j-1];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-2}-${j-1}`).style.border = colorCursor[1];}}
                    if(i+1<8&&j+2<8)  {k = gameField[i+1][j+2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+1}-${j+2}`).style.border = colorCursor[1];}}
                    if(i+1<8&&j-2>-1) {k = gameField[i+1][j-2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i+1}-${j-2}`).style.border = colorCursor[1];}}
                    if(i-1>-1&&j+2<8) {k = gameField[i-1][j+2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-1}-${j+2}`).style.border = colorCursor[1];}}
                    if(i-1>-1&&j-2>-1){k = gameField[i-1][j-2];if(k.contains == undefined && k.mountains == false){k.availability = true;document.getElementById(`${i-1}-${j-2}`).style.border = colorCursor[1];}}
                
                }
            }     
        }
    }else if (type == 'atk'){
        if (count>0){
            if(i+1<8)if (gameField[i+1][j].contains != undefined && gameField[i+1][j].contains.owner != gameSettings.turnOwner){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = colorCursor[2];}
            if(j+1<8)if (gameField[i][j+1].contains != undefined && gameField[i][j+1].contains.owner != gameSettings.turnOwner){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = colorCursor[2];}
            if(i-1>-1)if (gameField[i-1][j].contains != undefined && gameField[i-1][j].contains.owner != gameSettings.turnOwner){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = colorCursor[2];}
            if(j-1>-1)if (gameField[i][j-1].contains != undefined && gameField[i][j-1].contains.owner != gameSettings.turnOwner){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = colorCursor[2];}
            if(count>1){
                if(i+2<8)if(gameField[i+2][j].contains != undefined && gameField[i+2][j].contains.owner != gameSettings.turnOwner){gameField[i+2][j].availability = true;document.getElementById(`${i+2}-${j}`).style.border = colorCursor[2];}
                if(j+2<8)if(gameField[i][j+2].contains != undefined && gameField[i][j+2].contains.owner != gameSettings.turnOwner){gameField[i][j+2].availability = true;document.getElementById(`${i}-${j+2}`).style.border = colorCursor[2];}
                if(i-2>-1)if(gameField[i-2][j].contains != undefined && gameField[i-2][j].contains.owner != gameSettings.turnOwner){gameField[i-2][j].availability = true;document.getElementById(`${i-2}-${j}`).style.border = colorCursor[2];}
                if(j-2>-1)if(gameField[i][j-2].contains != undefined && gameField[i][j-2].contains.owner != gameSettings.turnOwner){gameField[i][j-2].availability = true;document.getElementById(`${i}-${j-2}`).style.border = colorCursor[2];}
                if(i+1<8&&j+1<8)if(gameField[i+1][j+1].contains != undefined && gameField[i+1][j+1].contains.owner != gameSettings.turnOwner){gameField[i+1][j+1].availability = true;document.getElementById(`${i+1}-${j+1}`).style.border = colorCursor[2];}
                if(i+1<8&&j-1>-1)if(gameField[i+1][j-1].contains != undefined && gameField[i+1][j-1].contains.owner != gameSettings.turnOwner){gameField[i+1][j-1].availability = true;document.getElementById(`${i+1}-${j-1}`).style.border = colorCursor[2];}
                if(i-1>-1&&j+1<8)if(gameField[i-1][j+1].contains != undefined && gameField[i-1][j+1].contains.owner != gameSettings.turnOwner){gameField[i-1][j+1].availability = true;document.getElementById(`${i-1}-${j+1}`).style.border = colorCursor[2];}
                if(i-1>-1&&j-1>-1)if(gameField[i-1][j-1].contains != undefined && gameField[i-1][j-1].contains.owner != gameSettings.turnOwner){gameField[i-1][j-1].availability = true;document.getElementById(`${i-1}-${j-1}`).style.border = colorCursor[2];}
            }  
        
        }   
        if(gameField[i][j].contains.ability.includes('worker',0)){
            document.getElementById('btnBuildTownhall').style.display = 'inline';
            document.getElementById('btnBuildTower').style.display = 'inline';
        }
        if(gameField[i][j].contains.ability.includes('hire',0)){
            if(gameField[i][j].contains.class == 'townhall'){
                document.getElementById('btnBuyT1').style.display = 'inline';
                document.getElementById('btnBuyT2').style.display = 'inline';
            }else if(gameField[i][j].contains.class == 'tower'){
                document.getElementById('btnBuyWarchief').style.display = 'inline';
                document.getElementById('btnBuyT3').style.display = 'inline';
            }
        }
        if(gameField[i][j].contains.ability.includes('surgery',0)){
            if(gameField[i][j].contains.hpMax > gameField[i][j].contains.hp)document.getElementById('btnSurgeryHeal').style.display = 'inline';
            if(i+1<8) if (gameField[i+1][j].contains != undefined && gameField[i+1][j].contains.type != 'building' && gameField[i+1][j].contains.owner == gameSettings.turnOwner && gameField[i+1][j].contains.hpMax > gameField[i+1][j].contains.hp){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = colorCursor[3];}
            if(j+1<8) if (gameField[i][j+1].contains != undefined && gameField[i][j+1].contains.type != 'building' && gameField[i][j+1].contains.owner == gameSettings.turnOwner && gameField[i][j+1].contains.hpMax > gameField[i][j+1].contains.hp){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = colorCursor[3];}
            if(i-1>-1)if (gameField[i-1][j].contains != undefined && gameField[i-1][j].contains.type != 'building' && gameField[i-1][j].contains.owner == gameSettings.turnOwner && gameField[i-1][j].contains.hpMax > gameField[i-1][j].contains.hp){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = colorCursor[3];}
            if(j-1>-1)if (gameField[i][j-1].contains != undefined && gameField[i][j-1].contains.type != 'building' && gameField[i][j-1].contains.owner == gameSettings.turnOwner && gameField[i][j-1].contains.hpMax > gameField[i][j-1].contains.hp){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = colorCursor[3];}
            
        }
    }
    
}


function getRandomCell(i,j){
        //Проверка на зону игрового поля
    if(i<4){
        if(j<4){//Зона 1 (Верхняя левая)
             if(j+1<8  && gameField[i][j+1].contains == undefined && gameField[i][j+1].mountains == false) return gameField[i][j+1];
        else if(i+1<8  && gameField[i+1][j].contains == undefined && gameField[i+1][j].mountains == false) return gameField[i+1][j];
        else if(j-1>-1 && gameField[i][j-1].contains == undefined && gameField[i][j-1].mountains == false) return gameField[i][j-1]; 
        else if(i-1>-1 && gameField[i-1][j].contains == undefined && gameField[i-1][j].mountains == false) return gameField[i-1][j];
        else return false;
        }else if(j>3){//Зона 2 (Верхняя правая)
             if(i+1<8  && gameField[i+1][j].contains == undefined && gameField[i+1][j].mountains == false) return gameField[i+1][j];
        else if(j-1>-1 && gameField[i][j-1].contains == undefined && gameField[i][j-1].mountains == false) return gameField[i][j-1]; 
        else if(i-1>-1 && gameField[i-1][j].contains == undefined && gameField[i-1][j].mountains == false) return gameField[i-1][j];
        else if(j+1<8  && gameField[i][j+1].contains == undefined && gameField[i][j+1].mountains == false) return gameField[i][j+1];
        else return false;
        }
    }else if(i>3){
        if(j<4){//Зона 3 (Нижняя левая)
             if(i-1>-1 && gameField[i-1][j].contains == undefined && gameField[i-1][j].mountains == false) return gameField[i-1][j];
        else if(j+1<8  && gameField[i][j+1].contains == undefined && gameField[i][j+1].mountains == false) return gameField[i][j+1];
        else if(i+1<8  && gameField[i+1][j].contains == undefined && gameField[i+1][j].mountains == false) return gameField[i+1][j];
        else if(j-1>-1 && gameField[i][j-1].contains == undefined && gameField[i][j-1].mountains == false) return gameField[i][j-1]; 
        else return false;
        }else if(j>3){//Зона 4 (Нижняя правая)
             if(j-1>-1 && gameField[i][j-1].contains == undefined && gameField[i][j-1].mountains == false) return gameField[i][j-1]; 
        else if(i-1>-1 && gameField[i-1][j].contains == undefined && gameField[i-1][j].mountains == false) return gameField[i-1][j];
        else if(j+1<8  && gameField[i][j+1].contains == undefined && gameField[i][j+1].mountains == false) return gameField[i][j+1];
        else if(i+1<8  && gameField[i+1][j].contains == undefined && gameField[i+1][j].mountains == false) return gameField[i+1][j];
        else return false;
        }
    }else return false;
}

function clipboardCopy(string){
    let copyTextarea = document.createElement("textarea");
    copyTextarea.style.position = "fixed";
    copyTextarea.style.opacity = "0";
    //copyTextarea.setAttribute('value',string);
    copyTextarea.textContent = string;
 
    document.body.appendChild(copyTextarea);
    copyTextarea.select();
    document.execCommand("copy");
    document.body.removeChild(copyTextarea);
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

function cf (){
    alert("checked!");
}



