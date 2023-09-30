"use strict"
//for local
const folder = "/game.exe";
//for server
//const folder = "";

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


