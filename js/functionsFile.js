"use strict"
//player colors
//red blue orange purple
const colorPlayers = ['#fc9393', '#9393fc', '#fcb64d', '#b64dfc'];
//colors cursors?
const colorCursor = ['2px solid blue', '2px solid red', ];


let gameField = new Array(8);
/*async*/ function newGameGenerate(){ 
    gameSettings.turnOwner = 1;
    //new players
    //let countPlayers = prompt('1 или 2 игрока? вводить число', '')
    let countPlayers = 2;
    for (let k = 1; k<=countPlayers; k++){
        let name = prompt('Введите имя игрока '+ k, '');
        players[k] = new Player(name, k);
    }
    
    //Generate Game Field
    for(let i=0;i<gameField.length;i++){
        let gameRows = new Array(8);
        for (let j=0;j<gameRows.length;j++){ 
            gameRows[j] = {
                contains: undefined,
                resCount: 0,
                availability: true,
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

        //delete it
    }
    //create townhalls and peasants
    gameField[2][3].resCount = 3;
    gameField[5][6].resCount = 3;
    gameField[3][4].resCount = 3;
    gameField[5][7].resCount = 3;
    gameField[6][6].contains = townhall;
    gameField[5][6].contains = new Unit(peasant,1,`5-6`);
    //gameField[1][1].contains = townhall;
    gameField[2][1].contains = new Unit(peasant,2,`5-6`);


}
function update(){
    let string = "";
    for(let i=0;i<8;i++){
        for (let j=0;j<8;j++){
            let titleText = "";
            if (gameField[i][j].resCount>0)titleText = `Золото = ${gameField[i][j].resCount}, `;
            if(gameField[i][j].contains != undefined){
                switch(gameField[i][j].contains.name){
                    case 'Peasant':
                        string = "img/units/peasant.png";
                        break;
                    case 'Townhall':
                        string = "img/units/citadel.png";
                        break;
                    default:
                        string = "";
                        break;
                }
                if(gameField[i][j].contains.owner == 1)document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[0];
                switch(gameField[i][j].contains.owner){
                    case 1:
                        document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[0];
                        break;
                    case 2:
                        document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[1];
                        break;
                    default:
                        break;
                }
                document.getElementById(`${i}-${j}`).setAttribute('src',string);
                titleText += `${gameField[i][j].contains.name} - hp = ${gameField[i][j].contains.hp}/${gameField[i][j].contains.hpMax}, atk = ${gameField[i][j].contains.attack}, move = ${gameField[i][j].contains.movePoint}`
                
            }else{
                document.getElementById(`${i}-${j}`).setAttribute('src',"img/null.png");
                if (gameField[i][j].resCount > 0){
                    document.getElementById(`${i}-${j}`).style.backgroundColor = 'yellow';
                }else document.getElementById(`${i}-${j}`).style.backgroundColor = '#aacd95';
            }
            //document.getElementById(`${i}-${j}`).removeAttribute('style');
            document.getElementById(`${i}-${j}`).title = titleText;
            document.getElementById(`${i}-${j}`).style.border = '';
            string = "";
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
function unlockCells(count,i,j){
    if (count>0){
        if(i+1<8)if(gameField[i+1][j].contains == undefined){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = colorCursor[0];}
            else if (gameField[i+1][j].contains != undefined && gameField[i+1][j].contains.owner != gameSettings.turnOwner){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = colorCursor[1];}
        if(j+1<8)if(gameField[i][j+1].contains == undefined){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = colorCursor[0];}
            else if (gameField[i][j+1].contains != undefined && gameField[i][j+1].contains.owner != gameSettings.turnOwner){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = colorCursor[1];}
        if(i-1>-1)if(gameField[i-1][j].contains == undefined){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = colorCursor[0];}
            else if (gameField[i-1][j].contains != undefined && gameField[i-1][j].contains.owner != gameSettings.turnOwner){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = colorCursor[1];}
        if(j-1>-1)if(gameField[i][j-1].contains == undefined){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = colorCursor[0];}
            else if (gameField[i][j-1].contains != undefined && gameField[i][j-1].contains.owner != gameSettings.turnOwner){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = colorCursor[1];}
        if(count>1){
            if(i+2<8){gameField[i+2][j].availability = true;document.getElementById(`${i+2}-${j}`).style.border = colorCursor[0];}
            if(j+2<8){gameField[i][j+2].availability = true;document.getElementById(`${i}-${j+2}`).style.border = colorCursor[0];}
            if(i-2>-1){gameField[i-2][j].availability = true;document.getElementById(`${i-2}-${j}`).style.border = colorCursor[0];}
            if(j-2>-1){gameField[i][j-2].availability = true;document.getElementById(`${i}-${j-2}`).style.border = colorCursor[0];}
            if(i+1<8&&j+1<8){gameField[i+1][j+1].availability = true;document.getElementById(`${i+1}-${j+1}`).style.border = colorCursor[0];}
            if(i+1<8&&j-1>-1){gameField[i+1][j-1].availability = true;document.getElementById(`${i+1}-${j-1}`).style.border = colorCursor[0];}
            if(i-1>-1&&j+1<8){gameField[i-1][j+1].availability = true;document.getElementById(`${i-1}-${j+1}`).style.border = colorCursor[0];}
            if(i-1>-1&&j-1>-1){gameField[i-1][j-1].availability = true;document.getElementById(`${i-1}-${j-1}`).style.border = colorCursor[0];}
        }     
    }
}

function cf (){
    alert("checked!");
}