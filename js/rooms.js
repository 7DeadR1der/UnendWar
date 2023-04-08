"use strict"

function dialogCreateRoom(a){
    let dialogCR = document.getElementsByClassName('dialog-block')[0];
    if (a == 1){
        dialogCR.style.opacity = '1';
        dialogCR.style.pointerEvents = 'auto';
    }else {
        dialogCR.style.opacity = '0';
        dialogCR.style.pointerEvents = 'none';
    }
}

function checkUser(type){
    document.getElementById('room-block').style.display = 'none';
    document.getElementById('game-block').style.display = 'none';
    let value;
    let xhr = new XMLHttpRequest();
    xhr.open('GET','/game.exe/includes/checkuser.php?type='+type);
    xhr.onload = function (){
        value = xhr.response;
        if(value == 'rooms'){
            document.getElementById('room-block').style.display = 'flex';
            loadRooms();
        }else {
            document.getElementById('game-block').style.display = 'flex';
            loadGame(value);
        }
    }
    xhr.send();
}

function loadRooms(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET','/game.exe/includes/game/loadrooms.php');
    xhr.onload = function (){
        document.getElementById('room-content').innerHTML = xhr.response;
    }
    xhr.send();
}

function loadGame(num){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/game.exe/includes/game/loadgame.php?id='+num);
    xhr.onload = function (){
        if(xhr.response[0] == '<'){
            document.getElementById('game-block').innerHTML = xhr.response;
            checkRoom(true);        
        }else if (xhr.response[0] == '{'){
            let jsonData = JSON.parse(xhr.response);
            console.log(jsonData);
            loadGameFile(jsonData);
            checkRoom(true);
        }else{
        //game end
            checkRoom(false);
            exitRoom();
        }
    }
    xhr.send();
}

function createRoom(){
    let formData = {
        name: document.getElementById('inputGameName').value,
        mode: document.getElementById('selectGameMode').value,
        type: document.querySelector('select[name="gameType"]').value,
        map: document.querySelector('select[name="gameMap"]').value,
        count: document.querySelector('input[name="gamePlayers"]').value
    }
    let xhr = new XMLHttpRequest();
    xhr.open('POST','/game.exe/includes/game/createroom.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function (){
        alert(xhr.response);
        checkUser('active_room');
    };
    xhr.onerror = function(){
        alert('ошибка при создании лобби');
    }
    xhr.send('gameName='+encodeURIComponent(formData.name)
    +'&gameMode='+encodeURIComponent(formData.mode)+'&gameType='+encodeURIComponent(formData.type)
    +'&gameMap='+encodeURIComponent(formData.map)+'&gamePlayers='+encodeURIComponent(formData.count));
    dialogCreateRoom();
}

function joinRoom(id){ //Вход в комнату
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/game.exe/includes/game/joinroom.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function (){
        checkUser('active_room');
    }
    xhr.send('idRoom='+encodeURIComponent(id));
}

function exitRoom(){
    if(confirm("Вы уверены что хотите покинуть комнату? если игра началась, в нее уже не получится вернутся")){
        let xhr = new XMLHttpRequest();
        xhr.open('GET','/game.exe/includes/game/exitroom.php');
        xhr.onload = function(){
            checkUser('active_room');
        }
        xhr.send();
        document.getElementById('game-block').style.display = 'none';
    }
}

function changePlayer(faction){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/game.exe/includes/game/changeplayer.php");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function(){
        loadGame(0);
    }
    xhr.send('faction='+encodeURIComponent(faction));
}

function checkRoom(flag){
    if(flag === true){
        let xhr = new XMLHttpRequest();
        xhr.open('GET','/game.exe/includes/game/checkroom.php');
        xhr.onload = function(){
            let rsp = xhr.response;
            if(rsp == 'success'){
                loadGame(0);
                setTimeout(checkRoom,2000,true);
            }else if(rsp == 'nothing'){
                setTimeout(checkRoom,2000,true);
            }

        }
        xhr.send();
    }
}

function startGame() {
    if(confirm('Вы уверены, что готовы начать?')){
        let xhr = new XMLHttpRequest();
        xhr.open('GET','/game.exe/includes/game/startgame.php');
        //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        xhr.onload = function(){
            if(xhr.response == 'success'){
                loadGame(0);
            }
        }
        xhr.send();
    }
}
function selectMapType(val){
    document.getElementById('countRangeDisplay').textContent=val[0];
    document.getElementById('inputRangePlayers').max = val[0];
    document.getElementById('inputRangePlayers').value = val[0];
}

//checkUser();

checkUser('active_room');