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
            cashUnit = undefined;
            turnFlag = 0;
            unlockAllCells();
            update();
            document.getElementById('btnCancel').style.display = 'none';
        }
    }
    else if (gameField[i][j].contains != undefined && gameField[i][j].contains.owner != gameSettings.turnOwner && gameField[i][j].availability == true && turnFlag == 1){
        if(gameField[cashCell.i][cashCell.j].contains.canAction == true){
            let atkUnit = cashUnit;
            let defUnit = gameField[i][j].contains;
            if(defUnit.hp - atkUnit.attack > 0){
                defUnit.hp = defUnit.hp - atkUnit.attack;
            }else {
                gameField[i][j].contains = undefined;
            }
            atkUnit.canAction = false;
            cashUnit = undefined;
            turnFlag = 0;
            unlockAllCells();
            update();
            document.getElementById('btnCancel').style.display = 'none';
        }
    }else if(turnFlag == 1 && gameField[i][j] == gameField[cashCell.i][cashCell.j]){
        cancel();
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
    document.getElementById('btnCancel').style.display = 'none';
}

function endTurn(){
    for(let k = 1; k<players.length;k++){
        if(players[k] != undefined){
            players[k].counts = 0;
        }
    }
    cancel();
    if(gameSettings.turnOwner>=players.length-1){
        gameSettings.turnOwner = 0;
    }
    gameSettings.turnOwner++;
    for(let i = 0; i<gameField.length; i++){
        for(let j = 0; j<gameField[i].length; j++){
            let cell = gameField[i][j];
            if(cell.contains != undefined){
                players[cell.contains.owner].counts++;
                cell.contains.canMove = true;
                cell.contains.canAction = true;
                if(cell.resCount > 0){
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
    
}



