"use strict"

function mapMaker(type,map,count){
    let startPosition = [];
    switch (map){
        case '2lr':
            gameField[7][0].resCount = 10;
            gameField[0][7].resCount = 10;
            gameField[0][3].resCount = 10;
            gameField[2][0].resCount = 10;
            gameField[7][4].resCount = 10;
            gameField[5][7].resCount = 10;
            gameField[0][0].mountains = true;
            gameField[0][1].mountains = true;
            gameField[1][0].mountains = true;
            gameField[2][4].mountains = true;
            gameField[3][7].mountains = true;
            gameField[4][0].mountains = true;
            gameField[5][3].mountains = true;
            gameField[6][7].mountains = true;
            gameField[7][6].mountains = true;
            gameField[7][7].mountains = true;
            startPosition.push(gameField[6][1]);
            startPosition.push(gameField[1][6]);
            break;
        case '2gm':
            gameField[0][2].resCount = 10;
            gameField[4][0].resCount = 10;
            gameField[0][7].resCount = 10;
            gameField[5][7].resCount = 10;
            gameField[7][3].resCount = 10;
            gameField[7][0].mountains = true;
            gameField[6][0].mountains = true;
            gameField[7][1].mountains = true;
            gameField[0][3].mountains = true;
            gameField[0][4].mountains = true;
            gameField[0][5].mountains = true;
            gameField[1][3].mountains = true;
            gameField[1][4].mountains = true;
            gameField[2][7].mountains = true;
            gameField[3][6].mountains = true;
            gameField[3][7].mountains = true;
            gameField[4][6].mountains = true;
            gameField[4][7].mountains = true;
            gameField[3][1].mountains = true;
            gameField[4][1].mountains = true;
            gameField[4][2].mountains = true;
            gameField[5][2].mountains = true;
            gameField[5][3].mountains = true;
            gameField[6][3].mountains = true;
            gameField[6][4].mountains = true;
            startPosition.push(gameField[1][0]);
            startPosition.push(gameField[7][6]);
            break;
        case '2lm':
            gameField[0][0].resCount = 10;
            gameField[1][7].resCount = 10;
            gameField[6][0].resCount = 10;
            gameField[7][7].resCount = 10;
            gameField[0][5].mountains = true;
            gameField[0][6].mountains = true;
            gameField[0][7].mountains = true;
            gameField[1][1].mountains = true;
            gameField[3][3].mountains = true;
            gameField[3][4].mountains = true;
            gameField[4][3].mountains = true;
            gameField[4][4].mountains = true;
            gameField[6][6].mountains = true;
            gameField[7][0].mountains = true;
            gameField[7][1].mountains = true;
            gameField[7][2].mountains = true;
            startPosition.push(gameField[6][2]);
            startPosition.push(gameField[1][5]);
            break;
        case '2mc':
            gameField[0].forEach(cell => {
                cell.mountains = true;
            });
            gameField[1].forEach(cell => {
                cell.mountains = true;
            });
            gameField[7].forEach(cell => {
                cell.mountains = true;
            });
            gameField[1][3].resCount = 10;
            gameField[1][3].mountains = false;
            gameField[2][0].resCount = 10;
            gameField[6][0].resCount = 10;
            gameField[2][6].resCount = 10;
            gameField[6][6].resCount = 10;
            gameField[3][3].mountains = true;
            gameField[2][7].mountains = true;
            gameField[3][7].mountains = true;
            gameField[4][7].mountains = true;
            gameField[5][7].mountains = true;
            gameField[6][7].mountains = true;
            startPosition.push(gameField[3][0]);
            startPosition.push(gameField[3][6]);
            break;
        case '2or':
            gameField[0][0].resCount = 10;
            gameField[7][7].resCount = 10;
            gameField[3][3].resCount = 10;
            gameField[4][4].resCount = 10;
            gameField[3][4].mountains = true;
            gameField[4][3].mountains = true;
            gameField[0][5].mountains = true;
            gameField[0][6].mountains = true;
            gameField[0][7].mountains = true;
            gameField[3][0].mountains = true;
            gameField[4][0].mountains = true;
            gameField[5][0].mountains = true;
            gameField[6][0].mountains = true;
            gameField[7][0].mountains = true;
            gameField[6][1].mountains = true;
            gameField[7][1].mountains = true;
            gameField[7][2].mountains = true;
            gameField[1][6].mountains = true;
            gameField[1][7].mountains = true;
            gameField[2][7].mountains = true;
            gameField[3][7].mountains = true;
            gameField[4][7].mountains = true;
            startPosition.push(gameField[0][1]);
            startPosition.push(gameField[7][6]);
            break;
        case '2wp':
            gameField[7][0].resCount = 6;
            gameField[0][7].resCount = 6;
            gameField[0][1].resCount = 10;
            gameField[1][0].resCount = 10;
            gameField[7][6].resCount = 10;
            gameField[6][7].resCount = 10;
            gameField[0][0].mountains = true;
            gameField[0][4].mountains = true;
            gameField[2][2].mountains = true;
            gameField[2][7].mountains = true;
            gameField[3][4].mountains = true;
            gameField[4][3].mountains = true;
            gameField[5][0].mountains = true;
            gameField[5][5].mountains = true;
            gameField[7][3].mountains = true;
            gameField[7][7].mountains = true;
            startPosition.push(gameField[7][1]);
            startPosition.push(gameField[0][6]);
            break;
        case '4s':
            gameField[0].forEach(cell => {
                cell.mountains = true;
            });
            gameField[7].forEach(cell => {
                cell.mountains = true;
            });
            gameField[1][3].resCount = 10;
            gameField[3][6].resCount = 10;
            gameField[3][3].resCount = 10;
            gameField[6][4].resCount = 10;
            gameField[3][4].resCount = 6;
            gameField[4][3].resCount = 6;
            gameField[4][4].resCount = 6;
            gameField[4][1].resCount = 6;
            gameField[1][0].mountains = true;
            gameField[1][1].mountains = true;
            gameField[2][0].mountains = true;
            gameField[3][0].mountains = true;
            gameField[4][0].mountains = true;
            gameField[5][0].mountains = true;
            gameField[6][0].mountains = true;
            gameField[6][1].mountains = true;
            gameField[1][6].mountains = true;
            gameField[1][7].mountains = true;
            gameField[2][7].mountains = true;
            gameField[3][7].mountains = true;
            gameField[4][7].mountains = true;
            gameField[5][7].mountains = true;
            gameField[6][7].mountains = true;
            gameField[6][6].mountains = true;
            startPosition.push(gameField[1][5]);
            startPosition.push(gameField[2][1]);
            startPosition.push(gameField[6][2]);
            startPosition.push(gameField[5][6]);
            break;
        case '4lt':
            gameField[0][0].resCount = 10;
            gameField[0][7].resCount = 10;
            gameField[7][0].resCount = 10;
            gameField[7][7].resCount = 10;
            gameField[0][4].resCount = 10;
            gameField[3][0].resCount = 10;
            gameField[4][7].resCount = 10;
            gameField[7][3].resCount = 10;
            gameField[2][4].mountains = true;
            gameField[3][2].mountains = true;
            gameField[4][5].mountains = true;
            gameField[5][3].mountains = true;
            startPosition.push(gameField[0][2]);
            startPosition.push(gameField[2][7]);
            startPosition.push(gameField[7][5]);
            startPosition.push(gameField[5][0]);
            break;
        
        case '4cg':
            gameField[0][0].resCount = 15;
            gameField[0][7].resCount = 15;
            gameField[7][0].resCount = 15;
            gameField[7][7].resCount = 15;
            gameField[3][3].resCount = 15;
            gameField[4][4].resCount = 15;
            gameField[0][2].mountains = true;
            gameField[1][6].mountains = true;
            gameField[2][0].mountains = true;
            gameField[3][4].mountains = true;
            gameField[4][3].mountains = true;
            gameField[5][7].mountains = true;
            gameField[6][1].mountains = true;
            gameField[7][5].mountains = true;
            startPosition.push(gameField[3][0]);
            startPosition.push(gameField[0][3]);
            startPosition.push(gameField[4][7]);
            startPosition.push(gameField[7][4]);
            break;
        case '6gn':
            gameField.forEach(row => {
                row.forEach(cell => {
                    cell.resCount = 3;
                });
            });
            startPosition.push(gameField[0][0]);
            startPosition.push(gameField[0][4]);
            startPosition.push(gameField[2][7]);
            startPosition.push(gameField[5][0]);
            startPosition.push(gameField[7][3]);
            startPosition.push(gameField[7][7]);
            break;
        case '6gw':
            gameField[0][3].resCount = 10;
            gameField[0][7].resCount = 10;
            gameField[1][0].resCount = 10;
            gameField[2][5].resCount = 10;
            gameField[3][2].resCount = 10;
            gameField[5][4].resCount = 10;
            gameField[6][0].resCount = 10;
            gameField[6][7].resCount = 10;
            gameField[7][4].resCount = 10;
            gameField[0][0].mountains = true;
            gameField[0][1].mountains = true;
            gameField[0][5].mountains = true;
            gameField[3][3].mountains = true;
            gameField[3][5].mountains = true;
            gameField[3][7].mountains = true;
            gameField[5][3].mountains = true;
            gameField[7][0].mountains = true;
            gameField[7][1].mountains = true;
            gameField[7][6].mountains = true;
            gameField[7][7].mountains = true;
            startPosition.push(gameField[0][4]);
            startPosition.push(gameField[1][7]);
            startPosition.push(gameField[2][0]);
            startPosition.push(gameField[5][0]);
            startPosition.push(gameField[5][7]);
            startPosition.push(gameField[7][3]);
            break;
        case 'rnd':
            break;
        default:
            break;
    }
    //console.log(startPosition);
    playerMaker(type,startPosition,count);
}