"use strict"
// PROFILE
const regNode = 
    //'<form name="reg" id="formReg" action="includes/signup.php" method="post">'+
        '<label>Login *</label>'+
            '<input name="login" type="text">'+
        '<label>Password *</label>'+
            '<input name="pass" type="password">'+
        '<label>Confirm password *</label>'+
            '<input name="passConfirm" type="password">'+
        '<label>Your name</label>'+
            '<input name="name" type="text">'+
        '<label>Email</label>'+
            '<input name="email" type="text">'+
        '<button onclick="regFunction()">Зарегистрироваться</button>'+
        '<p>Есть аккаунт? <a class="pointer" onclick="profileContent.innerHTML = loginNode;">Авторизируйтесь</a></p>';//+
    //'</form>';
const loginNode = 
    //'<form action="includes/sugnin.php" method="post">'+
        '<label>Login</label>'+
            '<input name="login" type="text">'+
        '<label>Password</label>'+
            '<input name="password" type="password">'+
        '<button onclick="loginFunction()">Войти</button>'+
        '<p>Нет аккаунта? <a class="pointer" onclick="profileContent.innerHTML = regNode;">Зарегистрироваться</a></p>'+
        '<p>Утеряли доступ к аккаунту? <a href="index.php?page=support">Тех. Поддержка</a></p>';//+
   // '</form>';
const profileNode = 'ok';


let profileBlock = document.getElementById('profile-block');
profileBlock.style.display = "none"; 
function toggle_profile(){
    if(profileBlock.style.display == "none"){
        profileBlock.style.display = "block";
    }else{
        profileBlock.style.display = "none";
    }
}
let profileContent = document.getElementById('profile-content');

profileContent.innerHTML = loginNode;

// ----------REG----------
function regFunction(){
    let formData = {
        login: document.querySelector('input[name="login"]').value,
        pass: document.querySelector('input[name="pass"]').value,
        passConfirm: document.querySelector('input[name="passConfirm"]').value,
        name: document.querySelector('input[name="name"]').value,
        email: document.querySelector('input[name="email"]').value
    }
    let request = new XMLHttpRequest();
    request.open('POST', '/game.exe/includes/login/signup.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200){
            if(request.response == "success"){
                profileContent.innerHTML = loginNode;
                alert("Вы успешно зарегистрировались!");

            }else{
                alert(request.response);
            }
        }
    }

    request.send('login=' + encodeURIComponent(formData.login) +
    '&pass=' + encodeURIComponent(formData.pass) +
    '&passConfirm=' + encodeURIComponent(formData.passConfirm) +
    '&name=' + encodeURIComponent(formData.name) +
    '&email=' + encodeURIComponent(formData.email));
}

// ----------LOGIN----------

function loginFunction () {
    let formData = {
        login: document.querySelector('input[name="login"]').value,
        pass: document.querySelector('input[name="password"]').value,
    }
    let request = new XMLHttpRequest();
    request.open('POST', '/game.exe/includes/login/signin.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200){
                if (request.response == 'success'){
                    loadProfile();
                }
                else{
                    alert(request.response);
                    profileContent.innerHTML = loginNode;
                }
        }
    };
    request.send('login=' + encodeURIComponent(formData.login) +
    '&password=' + encodeURIComponent(formData.pass));
}
// ----------LOGOUT----------
function logout(){
    let request = new XMLHttpRequest();
    request.open('POST', '/game.exe/includes/login/logout.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200){
            profileContent.innerHTML = loginNode;
            location.reload();
        }
    }
    request.send();
}
/*
function checkUser(type){
    let value;
    let request = new XMLHttpRequest();
    request.open('GET','/game.exe/includes/login/checkuser.php?type='+type);
    request.onload = function (){
        value = request.response;
    }
    request.send();
    return value;
}
*/
function loadProfile(){
    let request = new XMLHttpRequest();
    request.open('GET','/game.exe/page/profile.php');
    request.onload = function (){
        if(request.response != ''){
            profileContent.innerHTML = request.response;
        }
    }
    request.send();
}
//onload function
loadProfile();