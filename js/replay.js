"use strict"
let historyBlock = document.getElementById("history");
let replayBlock = document.getElementById("replay");
replayBlock.style.display = "none";
let replayJson = false;
let replayStatistic;
let replayTurn = 0;
let isDown = false;
let startX;
let startY;
let scrollLeft;
let scrollTop;
let zoom = false;
//const colorPlayers = ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054'];
            //      gray        red         blue    orange   purple    yellow  dark-blue     green   brown
let colorPlayers = ['#bababa','#f59678','#6bccf7','#ffbd76','#8d87be','#fdf777','#cf8fd1','#f39aac','#7eca9c'];
const colorLands = ['#b1c37b','#f0fafa','#e8d479','#c7a787'];
//const colorLands = ['#b1c37b','#f0fafa','#efc279','#b1c37b'];'#fdf777','#cf8fd1',
document.addEventListener('keydown', function(event){
    if(replayJson!=false){
        switch(event.code){
            case 'ArrowRight':
                transit('+');
                break;
            case 'ArrowLeft':
                transit('-');
                break;
            default:
                break;
        }
    }
});

function loadReplay(id){
    if(!isNaN(id)){
        historyBlock.style.display = "none";
        replayBlock.style.display = "inline";
        let xhr = new XMLHttpRequest();
        xhr.open('GET', folder+'/includes/game/replay.php?id='+id);
        xhr.onload = function(){
            let x = JSON.parse(xhr.response);
            let res = x.data;
            replayJson = JSON.parse(res[0]);
            replayStatistic = JSON.parse(res[1]);
            document.querySelector(`input[name='timeLine']`).max = replayJson.length-1;

            gameSettings.land = replayStatistic.gameLand;
            //console.log(replayJson);
            let iMax = replayJson[0]['field'].length;
            let jMax = 0;
            for(let i=0;i<iMax;i++){
                jMax = replayJson[0]['field'][i].length;
                let stroke = document.createElement('div');
                stroke.className = 'gfRow';

                for(let j=0;j<jMax;j++){
                    let cellAdd = document.createElement('img');
                    cellAdd.className = 'gfCell';
                    cellAdd.id = `${i}-${j}`;
                    cellAdd.src = '';
                    cellAdd.onclick = function (){pressCell(i,j)};
                    stroke.appendChild(cellAdd);

                }
                document.getElementById('game-field').appendChild(stroke);
            }

            
            document.getElementById('game-field').addEventListener('mousedown', (e)=> {
                isDown = true;
                startX = e.pageX - document.getElementById('game-field').offsetLeft;
                startY = e.pageY - document.getElementById('game-field').offsetTop;
                scrollLeft = document.getElementById('game-field').scrollLeft;
                scrollTop = document.getElementById('game-field').scrollTop;
            })
            document.getElementById('game-field').addEventListener('mouseleave', (e)=>{
                isDown = false;
                document.getElementById('game-field').style.cursor = 'default';
            })
            document.getElementById('game-field').addEventListener('mouseup', (e)=>{
                isDown = false;
                document.getElementById('game-field').style.cursor = 'default';
            })
            document.getElementById('game-field').addEventListener('mousemove', (e)=>{
                if (!isDown) return;
                document.getElementById('game-field').style.cursor = 'all-scroll'//'grabbing';
                e.preventDefault();
                const x = e.pageX - document.getElementById('game-field').offsetLeft;
                const y = e.pageY - document.getElementById('game-field').offsetTop;
                const walkX = (x - startX) * 1; // Change this number to adjust the scroll speed
                const walkY = (y - startY) * 1; // Change this number to adjust the scroll speed
                document.getElementById('game-field').scrollLeft = scrollLeft - walkX;
                document.getElementById('game-field').scrollTop = scrollTop - walkY;
            })

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
    for(let i=0;i<gameField.length;i++){
        for(let j=0;j<gameField[i].length;j++){
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
    document.querySelector(`input[name='timeLineNum']`).value = replayTurn;
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
        let winnerFire = '';
        if(replayJson[replayJson.length-1].stats[cell.contains.owner].statistic.winner == 1){
            winnerFire = `&#128293;`;
        }
        winnerFire+=`${thisPlayer.name}`;
        document.getElementById('li_PlayerName').innerHTML = winnerFire;
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
                case"Undead Horde":
                    text = gameSettings.skills[10].description;
                    break;
                case"Undead Unholy":
                    text = gameSettings.skills[11].description;
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
        document.getElementById('li_leaderUp').textContent = `Лидеров нанято = ${thisPlayer.statistic.leaderUp}`;
        document.getElementById('li_leaderDown').textContent = `Лидеров убито = ${thisPlayer.statistic.leaderDown}`;
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
        document.getElementById('li_leaderUp').textContent = ``;
        document.getElementById('li_leaderDown').textContent = ``;
        document.getElementById('li_unitUp').textContent = ``;
        document.getElementById('li_unitDown').textContent = ``;
        document.getElementById('li_buildUp').textContent = ``;
        document.getElementById('li_buildDown').textContent = ``;
    }
}
function Zoom(){
    let px = "50px";
    if(zoom){
        zoom=false;
        px = "50px";
    }else{
        px = '36px';
        zoom=true;
    }
    for(let i=0;i<replayJson[0]['field'].length;i++){
        for(let j=0;j<replayJson[0]['field'][i].length;j++){
            document.getElementById(`${i}-${j}`).style.width = px;
            document.getElementById(`${i}-${j}`).style.height = px;
        }
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
    limit_leaders : 1,
    limit_townhalls : 2,
    limit_towers : 4,
    //skills:['Strength I','Strength II','Pathfinder','Surgery','Estates I', 'Estates'];
    skills:[
        {name:'Strength I', description:'Увеличивает силу атаки Лидера на 1'},
        {name:'Strength II', description:'Увеличивает здоровье Лидера на 2'},
        {name:'Pathfinder', description:'Увеличивает скорость Лидера на 1'},
        {name:'Surgery', description:'Позволяет Лидеру лечить себя или союзников'},
        {name:'Estates I', description:'Единовременно дает 4 золота'},
        {name:'Estates II', description:'Каждый ход дает 1 золото'},
        {name:'Engineering', description:'Все здания получают +1 к прочности'},
        {name:'Undead I', description:'Увеличивает здоровье Лича на 1 ед., зомби получают спсобность "infect"'},
        {name:'Undead II', description:'Увеличивает здоровье Лича на 1 ед., Лич получает способность "darkStorm"'},
        {name:"Scavengers", description:"Получаемый опыт увлечивается на 1 ед., орки (Т2) получают способность 'cannibal'"},// и Leader
        {name:'Undead Horde', description:"Лич получает способность 'teleport', zombie получают способность infect, улучшается способность 'darkArmy', теперь создает скелета воина вместо одного обычного скелета"},
        {name:'Undead Unholy', description:'Увеличивает здоровье Лича на 1 ед., Лич получает способность "darkBolt"'},
    ]
};

function loadHistory(){
        let xhr = new XMLHttpRequest();
        xhr.open('GET', folder+'/includes/game/replay.php?id=0');
        xhr.onload = function(){
            let x = JSON.parse(xhr.response);
            let res = x.data;
            let table = historyBlock.getElementsByTagName("table")[0];
            let tbody = table.getElementsByTagName("tbody")[0];
            //console.log(res);
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
                let cellDateCreate = row.insertCell(4);
                cellDateCreate.innerHTML = res[i]['date_create'];
                //cellDateCreate.innerHTML = new Date(res[i]['date_create']*1000);
                let cellDateEnd = row.insertCell(5);
                cellDateEnd.innerHTML = res[i]['date_end_game'];
                //cellDateEnd.innerHTML = new Date(res[i]['date_end_game']*1000);
                let cellBtn = row.insertCell(6);
                cellBtn.innerHTML = '<button onclick="loadReplay('+res[i]["id_room"]+')">Просмотр</button>'
            }
            //console.log(replayJson);
            //display();
        };
        xhr.send();
}

/*
*/
loadHistory();