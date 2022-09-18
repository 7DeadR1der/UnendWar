"use strict";

//Variables
let gameField = [,];
let unitTest = {
    type: 'unit',
    class: 'T1',
    name: 'Peasant',
    description: 'T1 unit test',
    hp: 1,
    attack: 1,
    movePoint: 1,

}
//
function newGameGenerate(height,players){
    //Generate Game Field
    for(let i=0;i<height;i++){
        for (let j=0;j<8;j++){
            gameField[i,j] = {
                contains: undefined,
                resCount: 0,
                availability: true
            }
            let cellAdd = document.createElement('div');
            cellAdd.className = 'gfCell';
            cellAdd.id = `${i}-${j}`;
            cellAdd.onclick = function () {pressCell(i,j)};
            document.querySelector('div.gridGameField').appendChild(cellAdd);
        }
    }
    //
    gameField[4,4].contains = unitTest;
}
function update(){
    
}

function pressCell(i,j){
    alert(`${i}-${j}`);
    if (gameField[i,j].contains != undefined){

    }
}

function cf (){
    alert("checked!");
}