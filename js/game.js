"use strict";
let turnOwner = 1;
let turnFlag = 0;
let cashUnit = {};
//Variables
let player = {

}

function createUnits(){
    gameField[2][3].resCount = 3;
    gameField[4][4].contains = townhall;
    gameField[3][3].contains = peasant;
}
function pressCell(i,j){
    
    if (gameField[i][j].contains != undefined && gameField[i][j].availability == true && turnFlag == 0){
        //alert(`${i}-${j}`);
        if(gameField[i][j].contains.owner == turnOwner){
            if(gameField[i][j].contains.posMove == true){
                lockAllCells();
                unlockCells(gameField[i][j].contains.movePoint, i, j);
                turnFlag = 1;
                cashUnit = gameField[i][j].contains;
                gameField[i][j].contains = undefined;
            }
            /*if(gameField[i][j].contains.posAttack == true){
                
            }*/
        }
    }
    else if(gameField[i][j].contains == undefined && gameField[i][j].availability == true && turnFlag == 1){
        gameField[i][j].contains = cashUnit;
        //gameField[i][j].contains.posMove = false;
        cashUnit = {};
        turnFlag = 0;
        unlockAllCells();
        update();
    }
    
}

function endTurn(){

}



