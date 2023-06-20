"use strict"
//let chatFlag = true;
let chatBlock = document.getElementById('chat-block');
chatBlock.style.display = "none"; 

function toggle_chat(){
    if(chatBlock.style.display == "none"){
        chatBlock.style.display = "block";
    }else{
        chatBlock.style.display = "none";
    }
}

function loadChat(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/chat/loadchat.php');
    xhr.onload = function (){
        document.getElementById('chat-content').innerHTML = xhr.response;
        document.getElementById('chat-content').scrollTop = document.getElementById('chat-content').scrollHeight;
        //alert(xhr.response);
    }
    xhr.send();
}

function checkChat(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET',folder+'/includes/chat/checkchat.php');
    xhr.onload = function(){
        let rsp = xhr.response;
        if(rsp == 'success'){
            loadChat();
        }
        //alert(xhr.response);

    }
    xhr.send();
    setTimeout(checkChat,2000);
}

function sendMessage(){
    let xhr = new XMLHttpRequest();
    let msg = document.querySelector('input[name="textMessage"]').value;
    xhr.open('POST',folder+'/includes/chat/sendmsg.php',true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhr.onload = function(){
        loadChat();
        //alert(xhr.response);
        document.querySelector('input[name="textMessage"]').value = '';
    }
    xhr.send('msg='+ encodeURIComponent(msg));
}
loadChat();
checkChat();