<?php
    /* LocalDataBase */
    $connect = mysqli_connect('localhost', 'root', '', 'uew_db');
    /* hostingDataBase */
    //$connect = mysqli_connect();
    
    if(!$connect){
        die('Error connect to DataBase');
    }
?>