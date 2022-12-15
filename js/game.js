"use strict";
let turnOwner = 1;
//Variables
let unitTest = {
    type: 'unit',
    class: 'T1',
    name: 'Peasant',
    description: 'T1 unit test',
    posMove: true,
    posAttack: true,
    hp: 1,
    attack: 1,
    movePoint: 1,
    owner:1
}
let gameField = new Array(8);
//
/*async*/ function newGameGenerate(){
    
    //Generate Game Field
    for(let i=0;i<gameField.length;i++){
        let gameRows = new Array(8);
        for (let j=0;j<gameRows.length;j++){ 
            gameRows[j] = {
                contains: undefined,
                //resCount: 0,
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
    }
    //console.log(gameField);
    //
    
   gameField[4][4].contains = unitTest;
}
function update(){
    let string = "";
    for(let i=0;i<8;i++){
        for (let j=0;j<8;j++){
            if(gameField[i][j].contains != undefined){
                let test1 = gameField[i][j];
                switch(test1.contains.name){
                    case 'Peasant':
                        string = "img/units/peasant.png";
                        break;
                    case 'Citadel':
                        string = "img/units/citadel.png";
                        break;
                    default:
                        string = "";
                        break;
                }
            
                document.getElementById(`${i}-${j}`).setAttribute('src',string);
            }else{
                document.getElementById(`${i}-${j}`).removeAttribute('src');
            }
            string = "";
        }
    }
}

function pressCell(i,j){
    
    if (gameField[i][j].contains != undefined && gameField[i][j].availability == true){
        alert(`${i}-${j}`);
        if(gameField[i][j].contains.owner == turnOwner){
            if(gameField[i][j].contains.posMove == true){
                lockAllCells();
                gameField[i+1][j].availability = true;
                gameField[i][j+1].availability = true;
                gameField[i-1][j].availability = true;
                gameField[i][j-1].availability = true;
            }
            if(gameField[i][j].contains.posAttack == true){
                
            }
        }
    }
}

function cf (){
    alert("checked!");
}

