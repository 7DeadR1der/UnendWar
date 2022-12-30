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
<script src="js/gameMaps.js"></script>
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

            case "about_game":
                include("page/about_game.html");
                break;
            case "authors":
                include("page/authors.html");
                break;

            default:
                include("page/main.html");  
                break;
        }
    ?>


</body>
</html>