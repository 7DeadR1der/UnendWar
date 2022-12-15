"use strict"
let gameField = new Array(8);
/*async*/ function newGameGenerate(){ 
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
                    case 'Townhall':
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
            document.getElementById(`${i}-${j}`).removeAttribute('style');
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
    switch(count){
        case 1:
            if(i+1<8)if(gameField[i+1][j].contains == undefined){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = '1px solid blue';}
            if(j+1<8)if(gameField[i][j+1].contains == undefined){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = '1px solid blue';}
            if(i-1>-1)if(gameField[i-1][j].contains == undefined){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = '1px solid blue';}
            if(j-1>-1)if(gameField[i][j-1].contains == undefined){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = '1px solid blue';}
            break;
        case 2:
            if(i+1<8){gameField[i+1][j].availability = true;document.getElementById(`${i+1}-${j}`).style.border = 'green';}
            if(i+2<8){gameField[i+2][j].availability = true;document.getElementById(`${i+2}-${j}`).style.border = 'green';}
            if(j+1<8){gameField[i][j+1].availability = true;document.getElementById(`${i}-${j+1}`).style.border = 'green';}
            if(j+2<8){gameField[i][j+2].availability = true;document.getElementById(`${i}-${j+2}`).style.border = 'green';}
            if(i-1>-1){gameField[i-1][j].availability = true;document.getElementById(`${i-1}-${j}`).style.border = 'green';}
            if(i-2>-1){gameField[i-2][j].availability = true;document.getElementById(`${i-2}-${j}`).style.border = 'green';}
            if(j-1>-1){gameField[i][j-1].availability = true;document.getElementById(`${i}-${j-1}`).style.border = 'green';}
            if(j-2>-1){gameField[i][j-2].availability = true;document.getElementById(`${i}-${j-2}`).style.border = 'green';}
            if(i+1<8&&j+1<8){gameField[i+1][j+1].availability = true;document.getElementById(`${i+1}-${j+1}`).style.border = 'green';}
            if(i+1<8&&j-1>-1){gameField[i+1][j-1].availability = true;document.getElementById(`${i+1}-${j-1}`).style.border = 'green';}
            if(i-1>-1&&j+1<8){gameField[i-1][j+1].availability = true;document.getElementById(`${i-1}-${j+1}`).style.border = 'green';}
            if(i-1>-1&&j-1>-1){gameField[i-1][j-1].availability = true;document.getElementById(`${i-1}-${j-1}`).style.border = 'green';}
            break;
        default:
            break;
    }
}

function cf (){
    alert("checked!");
}