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
    <?php 
        switch($page){
            case "game":
                include("page/game.html");  
            break;

            case "conquest":
                
            break;

            case "settings":
                
            break;

            default:
                include("page/main.html");  
            break;
        }
    ?>

    <script src="js/game.js"></script>
</body>
</html>