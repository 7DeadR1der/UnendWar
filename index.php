<?php
session_start();
require_once 'includes/connect.php';

if (isset($_GET['page'])) {
    $page=$_GET['page']; // Имя страницы
    } else $page='';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        include("template/head.html");
    ?>
</head>
<body>
<!-- <script src="js/class.js"></script>
<script src="js/functionsFile.js"></script>
<script src="js/gameMaps.js"></script> -->
<!-- <script src="js/game.js"></script> -->
    <?php
        if($page == 'profile' && !isset($_SESSION['user'])){
            include("page/login.html");
        }
        else if($page != ''){
            if(($page == 'login' || $page == 'reg') && isset($_SESSION['user'])){
                $page = 'profile';
            }
            include("page/".$page.".html");
        }
        else{
            include("page/main.html"); 
        }
    ?>


    <div id="profile-button" class="pointer" onclick="toggle_profile()">
        
    </div>
    <div id="profile-block">
        <div class="profile-header">
            <h4>Profile</h4>
            <div class="profile-close pointer" onclick="toggle_profile()">X</div>
        </div>
        <div id="profile-content">
            
        </div>
    </div>
<script src="js/profile.js"></script>
</body>
</html>