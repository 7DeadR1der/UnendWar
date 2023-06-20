"use strict"

function saveProfile(){
    let formData = {
        //login: document.querySelector('input[name="login"]').value,
        oldPass: document.querySelector('input[name="oldPass"]').value,
        newPass: document.querySelector('input[name="newPass"]').value,
        confirmPass: document.querySelector('input[name="confirmPass"]').value,
        name: document.querySelector('input[name="name"]').value,
        email: document.querySelector('input[name="email"]').value
    }
    if(document.querySelector('input[name="oldPass"]').value == '' || document.querySelector('input[name="newPass"]').value == '' ){
        formData.oldPass = 0;
        formData.newPass = 0;
        formData.confirmPass = 0;
    }
    let request = new XMLHttpRequest();
    request.open('POST', folder+'/includes/login/change.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

    request.onreadystatechange = function () {
        if(request.readyState === 4 && request.status === 200){
            if(request.response == "success"){
                alert("Данные сохранены!");
                location.reload();

            }else{
                alert(request.response);
            }
        }
    }

    request.send('oldPass=' + encodeURIComponent(formData.oldPass) +
    '&newPass=' + encodeURIComponent(formData.newPass) +
    '&confirmPass=' + encodeURIComponent(formData.confirmPass) +
    '&name=' + encodeURIComponent(formData.name) +
    '&email=' + encodeURIComponent(formData.email));
}