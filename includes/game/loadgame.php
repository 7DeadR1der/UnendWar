<?php 
    require_once '../connect.php';
    session_start();
    if($_GET['id'] == 0){
        $id = $_SESSION['user']['active_room'];
    }else {
        $id = $_GET['id'];
    }
    $query = mysqli_query($connect,"SELECT * FROM `rooms` WHERE `id_room` = '$id'");
    if(mysqli_num_rows($query)>0){
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        //if(isset($_COOKIE['lm']))
        
        if($game['game_state']==0){ //load game room
            $kd='';$sm='';$ud='';  $rnd=''; 
            $players = explode('-',$game['players_id']);
            $factions = explode('-',$game['players_faction']);
            echo "<h4>".$game['name']."</h4>
            <p>Карта - ".$game['game_map']."</p>
            <p>Старт игры - ".$game['game_mode']."</p>
            <p>Тип игры - ".$game['game_type']."</p>
            <p>".$game['count_players']."/".$game['max_players']." игроков</p>";
            for($i = 0; $i<$game['max_players'];$i++){
                if(isset($players[$i])){
                    
                    switch ($factions[$i]){
                        case 'kingdom':
                            $kd = 'selected';
                            break;
                        case 'seamercs':
                            $sm = 'selected';
                            break;
                        case 'undead':
                            $ud = 'selected';
                            break;
                        default:
                            $rnd = 'selected';
                            break;
                    }
                    if($players[$i] == $_SESSION['user']['id']){
                        echo "<div>".$_SESSION['user']['login']." 
                        <select name='faction' onchange='changePlayer(this.value)'>
                            <option ".$kd." value='kingdom'>Kingdom</option>
                            <option ".$rnd." value='random'>Random</option>
                        </select>
                        </div>";
                        //after insert into up code
                        //<option ".$rnd." value='random'>Random</option>
                        //<option ".$ud." value='undead'>Undead</option>
                    }else{
                        $getLoginUsers = mysqli_query($connect, "SELECT `login` FROM `users` where `id_user` = '$players[$i]'");
                        echo "<div>".mysqli_fetch_assoc($getLoginUsers)['login']." 
                        <select disabled name='faction'>
                            <option ".$kd." value='kingdom'>Kingdom</option>
                            <option ".$ud." value='undead'>Undead</option>
                            <option ".$rnd." value='random'>Random</option>
                        </select>
                        </div>";

                    }
                    
                $kd='';$sm='';$ud='';  $rnd=''; 
                }
            }
            if($players[0] == $_SESSION['user']['id']){
                echo "<button onclick='startGame()'>Начать игру</button>";
            }
            echo "<button onclick='exitRoom()'>Выйти из лобби</button>";
        }else if ($game['game_state']==1){ //game started, load gamefield and game too
            //echo "Еще не сделано(((";
            
            header('Content-type: application/json');
            $json = json_decode($game['game_json']);
            $flag = false;
            
            while(!isset($json->gamePlayers[$json->gameTurn]) || $json->gamePlayers[$json->gameTurn]==false){
                if($json->gameTurn>=count($json->gamePlayers)){
                    $json->gameTurn=0;
                    //code for bot and etc
                    //$newRound=true;
                }
                $json->gameTurn++;
                $flag = true;
            }
            if($flag == true){
                $ts = time();
                $jsonData = json_encode($json);
                $updateRoom = mysqli_query($connect, "UPDATE `rooms` SET `game_json` = '$jsonData' ,`last_mod` = '$ts'  WHERE `id_room` = '$id'");
            }
            echo json_encode($json);
        }else { //game end, kick user from room
            echo "end";
        }
    }
    include("../updatedatauser.php");

?>