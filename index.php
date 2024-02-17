<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/general.php';

if (isset($_GET['page'])) {
    $page=$_GET['page']; // Имя страницы
    } else $page='';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="version" content="1.2.6">
    <script src="js/const.js"></script>
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
        else if($page == 'game' && !isset($_SESSION['user'])){
            include("page/main.html");
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
        Profile
    </div>
    <div id="profile-block">
        <div class="profile-header">
            <h4>Profile</h4>
            <div class="profile-close pointer" onclick="toggle_profile()">X</div>
        </div>
        <div id="profile-content">
            
        </div>
    </div>
    <?php
        if(isset($_SESSION['user'])){
            include("template/chat.html");
        }
    ?>
    <script src="js/profile.js"></script>
</body>
</html>