<?php
session_start();

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
<script src="js/class.js"></script>
<script src="js/functionsFile.js"></script>
<script src="js/game.js"></script>
    <?php 
        switch($page){
            case "game":
                include("page/game.html");  
                break;
        
            case "conquest":
                
                break;

            case "settings":
                
                break;

            case "about":
                include("page/about.html");
                break;

            default:
                include("page/main.html");  
                break;
        }
    ?>


</body>
</html>