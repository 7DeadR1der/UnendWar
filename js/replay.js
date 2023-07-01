"use strict"
let historyBlock = document.getElementById("history");
let replayBlock = document.getElementById("replay");
replayBlock.style.display = "none";
let replayJson;
let replayStatistic;
let replayTurn = 0;
const colorPlayers = ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054'];

const colorLands = ['#b1c37b','#f0fafa','#e8d479','#b1c37b'];
//const colorLands = ['#b1c37b','#f0fafa','#efc279','#b1c37b'];
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


function loadReplay(id){
    if(!isNaN(id)){
        historyBlock.style.display = "none";
        replayBlock.style.display = "inline";
        let xhr = new XMLHttpRequest();
        xhr.open('GET', folder+'/includes/game/replay.php?id='+id);
        xhr.onload = function(){
            let res = JSON.parse(xhr.response);
            replayJson = JSON.parse(res[0]);
            replayStatistic = JSON.parse(res[1]);
            document.querySelector(`input[name='timeLine']`).max = replayJson.length-1;

            gameSettings.land = replayStatistic.gameLand;
            //console.log(replayJson);
            display();
        };
        xhr.send();

    }else{
        alert('Введите корректный номер');
    }
}

function transit(type){
    switch(type){
        case '+':
            if(replayTurn<replayJson.length-1){
                replayTurn +=1;
            }
            break;
        case '-':
            if(replayTurn>0){
                replayTurn -=1;
            }
            break;
        case 'home':
            replayTurn = 0;
            break;
        case 'end':
            replayTurn=replayJson.length-1; 
            break;
        default:
            type = Number(type);
            replayTurn = type;
            break;
    }
    display();
}

function display(){
let gameField = replayJson[replayTurn]["field"];

    for(let i=0;i<8;i++){
        for(let j=0;j<8;j++){
                let titleText = "";
                if(gameField[i][j].resCount>0)
                    {titleText=`Золото = ${gameField[i][j].resCount},`;}
                if(gameField[i][j].contains != false){
                    document.getElementById(`${i}-${j}`).style.backgroundColor = colorPlayers[gameField[i][j].contains.owner];
                    if(gameField[i][j].contains.owner == 0){
                        document.getElementById(`${i}-${j}`).style.backgroundColor = colorLands[gameSettings.land];
                    }
                    
                    if(gameField[i][j].contains.canAction == false && 
                    (gameField[i][j].contains.canMove == false || gameField[i][j].contains.movePoint == 0)){
                        document.getElementById(`${i}-${j}`).style.filter = 'brightness(70%)';
                    }else{
                        document.getElementById(`${i}-${j}`).style.filter = 'brightness(100%)';
                    }
                    
                    document.getElementById(`${i}-${j}`).setAttribute('src',gameField[i][j].contains.image);
                    titleText += `${gameField[i][j].contains.name} - hp = ${gameField[i][j].contains.hp}/${gameField[i][j].contains.hpMax}, atk = ${gameField[i][j].contains.attack}, move = ${gameField[i][j].contains.movePoint}`;
                    
                }else{
                    document.getElementById(`${i}-${j}`).style.filter = 'brightness(100%)';
                    if(gameField[i][j].obstacle != 0){
                        document.getElementById(`${i}-${j}`).setAttribute('src',"img/obstacle/"+gameSettings.land+gameField[i][j].obstacle+".png");
                        document.getElementById(`${i}-${j}`).style.backgroundColor = colorLands[gameSettings.land];
                        titleText = 'Непроходимая клетка';
                    }else{
                        document.getElementById(`${i}-${j}`).setAttribute('src',"img/null.png");
                        if (gameField[i][j].resCount > 0){
                            document.getElementById(`${i}-${j}`).setAttribute('src',"img/goldOre.png");
                        }
                        document.getElementById(`${i}-${j}`).style.backgroundColor = colorLands[gameSettings.land];
                    }
                }
                document.getElementById(`${i}-${j}`).title = titleText;
                document.getElementById(`${i}-${j}`).style.border = '';
            
        }
    }
    
    document.querySelector(`input[name='timeLine']`).value = replayTurn;
}

function pressCell(i,j){
    let cell  = replayJson[replayTurn]["field"][i][j];
    if(cell.resCount>0){
        document.getElementById('li_goldCell').textContent = `Золото = ${cell.resCount}`;
    }else{
        document.getElementById('li_goldCell').textContent = '';
    }
    if(cell.contains != false){
        let thisPlayer = replayJson[replayTurn]["stats"][cell.contains.owner];
        document.getElementById('li_nameUnit').textContent = `${cell.contains.name}`;
        //document.getElementById('li_descriptionUnit').textContent = `${gameField[i][j].contains.description}`;
        //document.getElementById('li_ownerUnit').textContent = `Владелец - ${replayStatistic.gamePlayers[cell.contains.owner].name}`;
        document.getElementById('li_hpUnit').textContent = `Здоровье - ${cell.contains.hp}/${cell.contains.hpMax}`;
        document.getElementById('li_attackUnit').textContent = `Атака - ${cell.contains.attack}`;
        document.getElementById('li_moveUnit').textContent = `Скорость - ${cell.contains.movePoint}`;
        //document.getElementById('li_rangeUnit').textContent = `Дальность атаки - ${gameField[i][j].contains.range}`;
        document.getElementById('li_abilityUnit').textContent = 'Способности - ';
        cell.contains.ability.forEach(ability => {
            document.getElementById('li_abilityUnit').textContent += `${ability}, `;
        });
        let actionSpan = (cell.contains.canAction)?`green-text`:`red-text`;
        let moveSpan = (cell.contains.canMove)?`green-text`:`red-text`;
        let action = `<span class="${moveSpan}">Move</span>\u00A0/\u00A0<span class="${actionSpan}">Action</span>`;
        //document.getElementById('li_canMoveUnit').textContent = (gameField[i][j].contains.canMove)?`Может ходить - Да`:`Может ходить - Нет`;
        document.getElementById('li_canActionUnit').innerHTML = action;


        
        document.getElementById('list_player').style.backgroundColor = colorPlayers[thisPlayer.owner];
        document.getElementById('li_PlayerName').textContent = `${thisPlayer.name}`;
        let imgs = '';
        thisPlayer.skills.forEach(skill => {
            let text = "";
            switch(skill){
                case"Strength I":
                    text = gameSettings.skills[0].description;
                    break;
                case"Strength II":
                    text = gameSettings.skills[1].description;
                    break;
                case"Pathfinder":
                    text = gameSettings.skills[2].description;
                    break;
                case"Surgery":
                    text = gameSettings.skills[3].description;
                    break;  
                case"Estates I":
                    text = gameSettings.skills[4].description;
                    break;
                case"Estates II":
                    text = gameSettings.skills[5].description;
                    break;
                case"Engineering":
                    text = gameSettings.skills[6].description;
                    break;
                case"Undead I":
                    text = gameSettings.skills[7].description;
                    break;
                case"Undead II":
                    text = gameSettings.skills[8].description;
                    break;
                case"Scavengers":
                    text = gameSettings.skills[9].description;
                    break;
                default:
                    text = "ошибка описания";
                    break;
            }
            imgs += `\u00A0\u00A0<img alt="${skill}" src="img/icons/skills/16${skill}.png" title="${text}">`;
        });
        if(imgs!=''){
            document.getElementById('li_PlayerName').insertAdjacentHTML('beforeend', imgs);
        }
        document.getElementById('li_PlayerGold').textContent = `Золото = ${thisPlayer.gold}`;
        document.getElementById('li_PlayerLevel').textContent = `Уровень = ${thisPlayer.level}`;
        document.getElementById('li_PlayerExp').textContent = `Опыт = ${thisPlayer.exp}`;
        document.getElementById('li_PlayerScore').textContent = `Очки = ${thisPlayer.statistic.score}`;
        document.getElementById('li_goldUp').textContent = `Золота заработано = ${thisPlayer.statistic.goldUp}`;
        document.getElementById('li_goldDown').textContent = `Золота потрачено = ${thisPlayer.statistic.goldDown}`;
        document.getElementById('li_warchiefUp').textContent = `Вождей нанято = ${thisPlayer.statistic.warchiefUp}`;
        document.getElementById('li_warchiefDown').textContent = `Вождей убито = ${thisPlayer.statistic.warchiefDown}`;
        if(typeof(thisPlayer.statistic.workerUp) != undefined && thisPlayer.statistic.workerUp !== null){
            document.getElementById('li_workerUp').textContent = `Рабочих нанято = ${thisPlayer.statistic.workerUp}`;
            document.getElementById('li_workerDown').textContent = `Рабочих убито = ${thisPlayer.statistic.workerDown}`;
        }
        document.getElementById('li_unitUp').textContent = `Юнитов нанято = ${thisPlayer.statistic.unitUp}`;
        document.getElementById('li_unitDown').textContent = `Юнитов убито = ${thisPlayer.statistic.unitDown}`;
        document.getElementById('li_buildUp').textContent = `Зданий построено = ${thisPlayer.statistic.buildUp}`;
        document.getElementById('li_buildDown').textContent = `Зданий уничтожено = ${thisPlayer.statistic.buildDown}`;
    }else{
        document.getElementById('li_nameUnit').textContent = '';
        //document.getElementById('li_descriptionUnit').textContent = '';
        document.getElementById('li_ownerUnit').textContent = '';
        document.getElementById('li_hpUnit').textContent = '';
        document.getElementById('li_attackUnit').textContent = '';
        document.getElementById('li_moveUnit').textContent = '';
        //document.getElementById('li_rangeUnit').textContent = '';
        document.getElementById('li_abilityUnit').textContent = '';
        //document.getElementById('li_canMoveUnit').textContent = '';
        document.getElementById('li_canActionUnit').innerHTML = '';


        document.getElementById('li_PlayerScore').textContent = ``;
        document.getElementById('li_PlayerGold').textContent = ``;
        document.getElementById('li_PlayerLevel').textContent = ``;
        document.getElementById('li_PlayerExp').textContent = ``;
        document.getElementById('li_goldUp').textContent = ``;
        document.getElementById('li_goldDown').textContent = ``;
        document.getElementById('li_workerUp').textContent = ``;
        document.getElementById('li_workerDown').textContent = ``;
        document.getElementById('li_warchiefUp').textContent = ``;
        document.getElementById('li_warchiefDown').textContent = ``;
        document.getElementById('li_unitUp').textContent = ``;
        document.getElementById('li_unitDown').textContent = ``;
        document.getElementById('li_buildUp').textContent = ``;
        document.getElementById('li_buildDown').textContent = ``;
    }
}

const gameSettings = {
    turnOwner: 0,
    land: 0,
    level1: 5,
    level2: 15,
    level3: 30,
    limit_workers : 6,
    limit_army : 4,
    limit_warchiefs : 1,
    limit_townhalls : 2,
    limit_towers : 4,
    //skills:['Strength I','Strength II','Pathfinder','Surgery','Estates I', 'Estates'];
    skills:[
        {name:'Strength I', description:'Увеличивает силу атаки Вождя на 1'},
        {name:'Strength II', description:'Увеличивает здоровье Вождя на 2'},
        {name:'Pathfinder', description:'Увеличивает скорость Вождя на 1'},
        {name:'Surgery', description:'Позволяет вождю лечить себя или союзников'},
        {name:'Estates I', description:'Единовременно дает 4 золота'},
        {name:'Estates II', description:'Каждый ход дает 1 золото'},
        {name:'Engineering', description:'Все здания получают +1 к прочности'},
        {name:'Undead I', description:'Увеличивает здоровье Лича на 1 ед., зомби получают спсобность "infect"'},
        {name:'Undead II', description:'Увеличивает здоровье Лича на 1 ед., Лич получает способность "darkStorm"'},
        {name:"Scavengers", description:"T2 при убийстве восстанавливают себе здоровье"},// и Warchief
    ]
};

function loadHistory(){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', folder+'/includes/game/replay.php?id=0');
        xhr.onload = function(){
            let res = JSON.parse(xhr.response);
            let table = historyBlock.getElementsByTagName("table")[0];
            let tbody = table.getElementsByTagName("tbody")[0];
            //let tbody = document.getElementById("history").getElementsByTagName("table").getElementsByTagName("tbody");
            for(let i=0;i<res.length;i++){
                let row = tbody.insertRow(i);
                let cellId = row.insertCell(0);
                cellId.innerHTML = res[i]['id_room'];
                let cellName = row.insertCell(1);
                cellName.innerHTML = res[i]['name'];
                let cellMap = row.insertCell(2);
                cellMap.innerHTML = res[i]['game_map'];
                let cellLocal = row.insertCell(3);   
                if(res[i]['local'] == 1){
                    cellLocal.innerHTML = 'Да';
                }else{
                    cellLocal.innerHTML = 'Нет';
                }       
                let cellBtn = row.insertCell(4);
                cellBtn.innerHTML = '<button onclick="loadReplay('+res[i]["id_room"]+')">Просмотр</button>'
            }
            //console.log(replayJson);
            //display();
        };
        xhr.send();
}

loadHistory();