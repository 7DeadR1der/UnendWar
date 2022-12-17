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
            if(gameField[i][j].contains.posMove == true || gameField[i][j].contains.posAttack == true){
                lockAllCells();
                unlockCells(gameField[i][j].contains.movePoint, i, j);
                turnFlag = 1;
                cashCell.i = gameField[i][j].row;
                cashCell.j = gameField[i][j].column;

                cashUnit = gameField[i][j].contains;
            }
            /*if(gameField[i][j].contains.posAttack == true){
                
            }*/
        }
    }
    else if(gameField[i][j].contains == undefined && gameField[i][j].availability == true && turnFlag == 1){
        //moving unit  
        if (gameField[cashCell.i][cashCell.j].contains.posMove == true){  
            
            gameField[cashCell.i][cashCell.j].contains = undefined;
            gameField[i][j].contains = cashUnit;
            gameField[i][j].contains.posMove = false;
            cashUnit = undefined;
            turnFlag = 0;
            unlockAllCells();
            update();
        }
    }
    else if (gameField[i][j].contains != undefined && gameField[i][j].contains.owner != gameSettings.turnOwner && gameField[i][j].availability == true && turnFlag == 1){
        if(gameField[cashCell.i][cashCell.j].contains.posAttack == true){
            let atkUnit = cashUnit;
            let defUnit = gameField[i][j].contains;
            if(defUnit.hp - atkUnit.attack > 0){
                defUnit.hp = defUnit.hp - atkUnit.attack;
            }else {
                gameField[i][j].contains = undefined;
            }
            atkUnit.posAttack = false;
            cashUnit = undefined;
            turnFlag = 0;
            unlockAllCells();
            update();
        }
    }
    
}
function cancel(){
   // gameField[cashCell.i][cashCell.j].contains = cashUnit;
    cashCell.i = undefined;
    cashCell.j = undefined;
    cashUnit = undefined;
    turnFlag = 0;
    unlockAllCells();
    update();

}

function endTurn(){
    cancel();
    if(gameSettings.turnOwner>=players.length-1){
        gameSettings.turnOwner = 0;
    }
    gameSettings.turnOwner++;
    for(let i = 0; i<gameField.length; i++){
        for(let j = 0; j<gameField[i].length; j++){
            let cell = gameField[i][j];
            if(cell.contains != undefined){
                cell.contains.posMove = true;
                cell.contains.posAttack = true;
                if(cell.resCount > 0){
                    cell.resCount--;
                    players[cell.contains.owner].gold++;
                }
            }
        }
    }
    
}



