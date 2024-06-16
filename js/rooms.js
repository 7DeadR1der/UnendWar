"use strict"
let checkRoomFlag = false;
function dialogCreateRoom(a){
    dialogBack(a);
    let c = document.getElementsByClassName('dialog-create')[0];
    if(a == 1){
        c.style.display = 'block';
    }else{
        c.style.display = 'none';
    }
}
function dialogEndGame(a){
    dialogBack(a);
    let c = document.getElementsByClassName('dialog-end')[0];
    if(a == 1){
        c.style.display = 'flex';
    }else{
        c.style.display = 'none';
    }

}
function dialogMenu(a){
    dialogBack(a);
    let c = document.getElementsByClassName('dialog-menu')[0];
    if(a == 1){
        c.style.display = 'flex';
    }else{
        c.style.display = 'none';
    }
}
function dialogFaction(a){
    dialogBack(a);
    let c = document.getElementsByClassName('dialog-faction')[0];
    if(a == 1){
        c.style.display = 'flex';
    }else{
        c.style.display = 'none';
    }
}
function dialogBack(a){
    let dialog = document.getElementsByClassName('dialog-block')[0];
    if (a == 1){
        dialog.style.opacity = '1';
        dialog.style.pointerEvents = 'auto';
    }else {
        dialog.style.opacity = '0';
        dialog.style.pointerEvents = 'none';
    }

}


//
function checkUser(type){
    document.getElementById('room-block').style.display = 'none';
    document.getElementById('game-block').style.display = 'none';
    let value;
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/checkuser.php?type='+type);
    xhr.onload = function (){
        let x = JSON.parse(xhr.response);
        switch(x.status){
            case 3:
                document.getElementById('room-block').style.display = 'flex';
                loadRooms();
                break;
            case 2:
                document.getElementById('game-block').style.display = 'flex';
                loadGame(x.data);
                break;
            default:
                x.message = x.message ? x.message : 'Unknown error';
                alert(x.message);
                break;
        }
        // value = xhr.response;
        // if(value == 'rooms'){
        //     document.getElementById('room-block').style.display = 'flex';
        //     loadRooms();
        // }else {
        //     document.getElementById('game-block').style.display = 'flex';
        //     loadGame(value);
        // }
    }
    xhr.send();
}

function loadRooms(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/game/loadrooms.php');
    xhr.onload = function (){
        let x = JSON.parse(xhr.response);
        switch(x.status){
            case 1:
                document.getElementById('room-content').innerHTML = x.data;
                break;
            default:
                x.message = x.message ? x.message : 'Unknown error';
                alert(x.message);
                break;
        }
    }
    xhr.send();
}

function loadGame(num){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', folder+'/includes/game/loadgame.php?id='+num);
    xhr.onload = function (){
        //console.log(xhr.response);
        let x = JSON.parse(xhr.response);
        switch(x.status){
            case 1:
                document.getElementById('game-block').innerHTML = x.data;
                checkRoomFlag = true;  
                break;
            case 2:
                let jsonData = x.data;//JSON.parse(x.data);
                if(jsonData.gameVictoryCond.winner == false){
                    //console.log(jsonData);
                    loadGameFile(jsonData);
                    checkRoomFlag = true;
                }else{
                //game end
                }
                break;
            case 3:
                //game end
                    checkRoomFlag = false;
                    let doc = document.getElementById('gameStatistic');
                    let array = x.data;//JSON.parse(x.data);
                    //let array = jsonData.gamePlayers;
                    let ine = '';
                    for(let i=1;i<array.length;i++){
                        ine += '<div>';
                        ine += '<h4>'+array[i]['name']+'</h4>';
                        //if(array[i]['live'] == true){
                        if(array[i]['statistic']['winner']==1){
                            ine += '<h4>Победитель!</h4>';
                        }
                        ine += '<ul>';
                        ine += '<li>Фракция - '+array[i]['faction']['name']+'</li>';
                        ine += '<li>Количество очков - '+array[i]['statistic']['score']+'</li>';
                        ine += '<li>Золота получено - '+array[i]['statistic']['goldUp']+'</li>';
                        ine += '<li>Золота потрачено - '+array[i]['statistic']['goldDown']+'</li>';
                        ine += '<li>Лидеров создано - '+array[i]['statistic']['leaderUp']+'</li>';
                        ine += '<li>Лидеров убито - '+array[i]['statistic']['leaderDown']+'</li>';
                        if(typeof(array[i]['statistic']['workerUp'] != undefined && array[i]['statistic']['workerUp'] !== null)){
                            ine += '<li>Рабочих создано - '+array[i]['statistic']['workerUp']+'</li>';
                            ine += '<li>Рабочих убито - '+array[i]['statistic']['workerDown']+'</li>';
                        }
                        ine += '<li>Юнитов создано - '+array[i]['statistic']['unitUp']+'</li>';
                        ine += '<li>Юнитов убито - '+array[i]['statistic']['unitDown']+'</li>';
                        ine += '<li>Зданий построено - '+array[i]['statistic']['buildUp']+'</li>';
                        ine += '<li>Зданий разрушено - '+array[i]['statistic']['buildDown']+'</li>';
                        ine += '<li>Опыта получено - '+array[i]['exp']+'</li>';
                        let skillStr = '';
                        array[i]['skills'].forEach(skill => {
                            skillStr += skill+', ';
                        });
                        ine += '<li>Навыки - '+skillStr+'</li>';
                        ine += '</ul>';
                        ine += '</div>';
                    }
                    doc.innerHTML = ine;
                    dialogEndGame(1);
    
                    //alert(`Игра окончена! Победитель игрок - ${xhr.response}`)
                    exitRoom(true);
                break;
            default:
                x.message = x.message ? x.message : 'Unknown error';
                alert(x.message);
                break;
        }
    }
    xhr.send();
}

function createRoom(){
    let formData = {
        name: document.getElementById('inputGameName').value,
        mode: document.getElementById('selectGameMode').value,
        type: document.getElementById('selectGameType').value,
        map: document.querySelector('select[name="gameMap"]').value,
        count: document.querySelector('input[name="gamePlayers"]').value,
        cwc: 1,
        local: 0
    }
    if(document.getElementById('selectLocalGame').checked){
        formData.local = 1;
    }
    if(document.getElementById('classicWinCheck').checked){
        formData.cwc = 1;
    }
    let xhr = new XMLHttpRequest();
    xhr.open('POST',folder+'/includes/game/createroom.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function (){
        
        let x = JSON.parse(xhr.response);
        switch(x.status){
            case 1:
                //alert(x.data);
                checkUser('active_room');
                break;
            default:
                x.message = x.message ? x.message : 'Unknown error';
                alert(x.message);
                break;
        }
        //alert(xhr.response);
        //checkUser('active_room');
    };
    xhr.onerror = function(){
        alert('ошибка при создании лобби');
    }
    xhr.send('gameName='+encodeURIComponent(formData.name)
    +'&gameType='+encodeURIComponent(formData.type)
    +'&gameMode='+encodeURIComponent(formData.mode)//+'&gameType='+encodeURIComponent(formData.type)
    +'&cwc='+encodeURIComponent(formData.cwc)
    +'&gameMap='+encodeURIComponent(formData.map)
    +'&gamePlayers='+encodeURIComponent(formData.count)
    +'&gameLocal='+encodeURIComponent(formData.local));
    dialogCreateRoom();
}

function joinRoom(id){ //Вход в комнату
    let xhr = new XMLHttpRequest();
    xhr.open('POST', folder+'/includes/game/joinroom.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function (){
        checkUser('active_room');
    }
    xhr.send('idRoom='+encodeURIComponent(id));
}

function exitRoom(flag=false){
    if(flag==true || confirm("Вы уверены что хотите покинуть комнату? если игра началась, в нее уже не получится вернутся")){
        let xhr = new XMLHttpRequest();
        xhr.open('GET',folder+'/includes/game/exitroom.php');
        xhr.onload = function(){
            checkUser('active_room');
        }
        xhr.send();
        document.getElementById('game-block').style.display = 'none';
    }
}

function changePlayer(value,type=false){
    if(type!==false){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", folder+"/includes/game/changeplayer.php");
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.onload = function(){
            loadGame(0);
        }
        let string = '';
        if(type==0){
            string = 'faction='+encodeURIComponent(value);
        }else if(type==1){
            string = 'color='+encodeURIComponent(value);
        }
        xhr.send(string);
    }
}

function checkRoom(){
    if(checkRoomFlag){
        let xhr = new XMLHttpRequest();
        xhr.open('GET',folder+'/includes/game/checkroom.php');
        xhr.onload = function(){
            let x = JSON.parse(xhr.response);
            if(x.status == 1){
                loadGame(0);
            }

        }
        xhr.send();
    }
    setTimeout(checkRoom,1500);

}

function startGame() {
    if(confirm('Вы уверены, что готовы начать?')){
        let xhr = new XMLHttpRequest();
        let str = '';
        if(document.querySelector('select[name="player0"]')){
            str = '?';
            for(let i=0;i<6;i++){
                
                if(document.querySelector('select[name="player'+i+'"]') && document.querySelector('select[name="player'+i+'"]').value == 'player'){
                    if(i!=0) str+='&';
                    str += 'player'+i+'='+document.querySelector('select[name="faction'+i+'"]').value;
                }
            }
        }
        alert(str);
        xhr.open('GET',folder+'/includes/game/startgame.php'+str);
        //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
        xhr.onload = function(){
            //console.log(xhr.response);
            let x = JSON.parse(xhr.response);
            switch(x.status){
                case 1:
                    loadGame(0);
                    break;
                default:
                    x.message = x.message ? x.message : 'Unknown error';
                    alert(x.message);
                    break;
            }
        }
        xhr.send();
    }
}


function selectMapType(val){
    document.getElementById('countRangeDisplay').textContent=val[0];
    document.getElementById('inputRangePlayers').max = val[0];
    document.getElementById('inputRangePlayers').value = val[0];
    /*
    switch(val[1]){
        case '0':
            document.getElementById('classicWinCheck').disabled = true;
            document.getElementById('classicWinCheck').checked = true;
            document.getElementById('gameType').textContent = "Classic";
            break;
        case '1':
            document.getElementById('classicWinCheck').disabled = false;
            document.getElementById('classicWinCheck').checked = true;
            document.getElementById('gameType').textContent = "Hunt";
            break;
        case '2':
            document.getElementById('classicWinCheck').disabled = false;
            document.getElementById('classicWinCheck').checked = true;
            document.getElementById('gameType').textContent = "Collect";
            break;
        default:
            break;
    }*/
}

//checkUser();
checkRoom();
checkUser('active_room');