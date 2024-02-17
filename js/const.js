"use strict"
const version = '1.2.6';
//for local
//const folder = "/game.exe";
//for server
const folder = "";

let metaVer = document.querySelector('meta[name="version"]').content;
if(metaVer != version){
    console.log('true reload for update cache');
    location.reload(true);
}


//
const localSettings = {
    checkEndTurn: getCookie("checkEndTurn"),
    enableAnimation: getCookie("enableAnimation"),
};

function getCookie(name){
    if(document.cookie.length > 0){
        let start = document.cookie.indexOf(name + "=");
        if (start != -1){
            start = start + name.length + 1;
            let end = document.cookie.indexOf(";",start);
            if (end == -1){
                end = document.cookie.length;
            }
            return decodeURIComponent(document.cookie.substring(start,end));
        }
    }
    return "";
    /*
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1')+"=([^;]*"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;*/
}
function changeTab(button,num){
    let container = button.parentElement.parentElement;
    let items = container.querySelectorAll(".tab-item");
    items.forEach(item => {
        item.style.display = 'none';
    });
    items[num].style.display = 'flex';
}

function readJSON(file, callback){
    let xhr = new XMLHttpRequest();
    let value;
    //xhr.overrideMimeType("application/json");
    xhr.open("GET", folder + file, true);
    xhr.onload = function(){
        //console.log(xhr.response);
        //value = JSON.parse(xhr.response);
        //console.log(x);
        
    }
    xhr.send();
    return value;
}

//let fff = readJSON("/includes/game/game_settings.JSON");
//console.log(fff);


