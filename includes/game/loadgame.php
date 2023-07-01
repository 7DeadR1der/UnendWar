<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../connect.php';
    if($_GET['id'] == 0){
        $id = $_SESSION['user']['active_room'];
    }else {
        $id = $_GET['id'];
    }
    $query = mysqli_query($connect,"SELECT `id_room`,`name`,`game_map`,`game_mode`,`game_type`,`id_creator`,`count_players`,`max_players`,`players_id`,`game_state`,`game_json`,`local`,`last_mod` FROM `rooms` WHERE `id_room` = '$id'");
    if(mysqli_num_rows($query)>0){
        $game = mysqli_fetch_assoc($query);
        setcookie("lm",$game['last_mod']);
        //if(isset($_COOKIE['lm']))
        $array = json_decode($game['players_id'],true);
        
        if($game['game_state']==0){ //load game room
            $kd='';$sm='';$ud=''; $orc=''; $rnd=''; 
            echo "<h4>".$game['name']." ".$game['id_room']."</h4>
            <p>Карта - ".$game['game_map']."</p>
            <p>Старт игры - ".$game['game_mode']."</p>
            <p>Тип игры - ".$game['game_type']."</p>
            <p>".$game['count_players']."/".$game['max_players']." игроков</p>";
            for($i = 0; $i<$game['max_players'];$i++){
                if($game['local']==1){
                    echo "<div>player ".$i."<select name='player".$i."'>
                    <option value='player'>player</option>
                    <option value='none'>None</option>
                    </select>
                    <select name='faction".$i."'>
                    <option value='kingdom'>Kingdom</option>
                    <option value='seamercs'>Seamercs</option>
                    <option value='undead'>Undead</option>
                    <option value='orcs'>Orcs</option>
                    <option selected value='random'>Random</option>
                    </select></div>";
                    //<option value='seamercs'>Seamercs</option>
                }else if(array_key_exists($i,$array)){
                    
                    switch ($array[$i]["faction"]){
                        case 'kingdom':
                            $kd = 'selected';
                            break;
                        case 'seamercs':
                            $sm = 'selected';
                            break;
                        case 'undead':
                            $ud = 'selected';
                            break;
                        case 'orcs':
                            $orc = 'selected';
                            break;
                        default:
                            $rnd = 'selected';
                            break;
                    }
                    if($array[$i]["id"] == $_SESSION['user']['id']){
                        echo "<div>".$array[$i]["name"]." 
                        <select name='faction' onchange='changePlayer(this.value)'>
                            <option ".$kd." value='kingdom'>Kingdom</option>
                            <option ".$sm." value='seamercs'>Seamercs</option>
                            <option ".$ud." value='undead'>Undead</option>
                            <option ".$orc." value='orcs'>Orcs</option>
                            <option ".$rnd." value='random'>Random</option>
                        </select>
                        </div>";
                        //after insert into up code
                        //<option ".$sm." value='seamercs'>Seamercs</option>
                        //<option ".$orc." value='orcs'>Orcs</option>
                        //<option ".$rnd." value='random'>Random</option>
                        //<option ".$ud." value='undead'>Undead</option>
                    }else{
                        //$getLoginUsers = mysqli_query($connect, "SELECT `login` FROM `users` where `id_user` = '$players[$i]'");
                        echo "<div>".$array[$i]["name"]." 
                        <select disabled name='faction'>
                            <option ".$kd." value='kingdom'>Kingdom</option>
                            <option ".$sm." value='seamercs'>Seamercs</option>
                            <option ".$ud." value='undead'>Undead</option>
                            <option ".$orc." value='orcs'>Orcs</option>
                            <option ".$rnd." value='random'>Random</option>
                        </select>
                        </div>";

                    }
                    
            $kd='';$sm='';$ud=''; $orc=''; $rnd=''; 
                }
            }
            if($array[0]["id"] == $_SESSION['user']['id'] && $game['local'] == 0){
                echo "<button onclick='startGame()'>Начать игру</button>";
            }else if ($game['local'] == 1){
                echo "<button onclick='startGame()'>Начать игру</button>";
            }
            echo "<button onclick='exitRoom()'>Выйти из лобби</button>";
        }else if ($game['game_state']==1){ //game started, load gamefield and game too
            //echo "Еще не сделано(((";
            
            //header('Content-type: application/json');
            $json = json_decode($game['game_json']);
            $flag = false;
            
            while(!isset($json->gamePlayers[$json->gameTurn]) || $json->gamePlayers[$json->gameTurn]->live==false || checkPlayerInRoom()){
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
            if($game["local"]==1){
                $json = view($json->gameTurn);
            }else{
                $numOwner;
                for($i=1;$i<count($json->gamePlayers);$i++){
                    if($json->gamePlayers[$i]->name == $_SESSION["user"]["login"]){
                        $numOwner = $i;
                    }
                }
                $json = view($numOwner);
            }

            echo json_encode($json);
        }else { //game end, kick user from room
            $json = json_decode($game['game_json']);
            echo json_encode($json->gamePlayers);
        }
    }
    include("../update.php");


    function view($type){
        global $json;
        $min = -1;
        $max = 8;
        foreach ($json->gameField as $row){
            foreach($row as $cell){
                if($cell->contains!=false && $cell->contains->owner == $type){
                    $i = $cell->row;
                    $j = $cell->column;
                    $array = [];
                    $speed = $cell->contains->movePoint;
                        if(in_array('rush',$cell->contains->ability)){
                            $speed += 1;
                        }
                    $count = 0;
                    if($speed>=$cell->contains->range){
                        $count = $speed;
                    }else{
                        $count = $cell->contains->range;
                    }if($count == 0){
                        $count = 1;
                    }
                    $cell->view = true;
                    if($count>0){
                        if($i+1<$max)$json->gameField[$i+1][$j]->view = true;
                        if($j+1<$max)$json->gameField[$i][$j+1]->view = true;
                        if($i-1>$min)$json->gameField[$i-1][$j]->view = true;
                        if($j-1>$min)$json->gameField[$i][$j-1]->view = true;
                        if($count>1){
                            if($i+2<$max)$json->gameField[$i+2][$j]->view = true;
                            if($j+2<$max)$json->gameField[$i][$j+2]->view = true;
                            if($i-2>$min)$json->gameField[$i-2][$j]->view = true;
                            if($j-2>$min)$json->gameField[$i][$j-2]->view = true;
                            if($i+1<$max&&$j+1<$max)$json->gameField[$i+1][$j+1]->view = true;
                            if($i+1<$max&&$j-1>$min)$json->gameField[$i+1][$j-1]->view = true;
                            if($i-1>$min&&$j+1<$max)$json->gameField[$i-1][$j+1]->view = true;
                            if($i-1>$min&&$j-1>$min)$json->gameField[$i-1][$j-1]->view = true;
                            if($count>2){
                                if($i+3<$max)$json->gameField[$i+3][$j]->view = true;
                                if($j+3<$max)$json->gameField[$i][$j+3]->view = true;
                                if($i-3>$min)$json->gameField[$i-3][$j]->view = true;
                                if($j-3>$min)$json->gameField[$i][$j-3]->view = true;
                                if($i+2<$max&&$j+1<$max)$json->gameField[$i+2][$j+1]->view = true;
                                if($i+2<$max&&$j-1>$min)$json->gameField[$i+2][$j-1]->view = true;
                                if($i-2>$min&&$j+1<$max)$json->gameField[$i-2][$j+1]->view = true;
                                if($i-2>$min&&$j-1>$min)$json->gameField[$i-2][$j-1]->view = true;
                                if($i+1<$max&&$j+2<$max)$json->gameField[$i+1][$j+2]->view = true;
                                if($i+1<$max&&$j-2>$min)$json->gameField[$i+1][$j-2]->view = true;
                                if($i-1>$min&&$j+2<$max)$json->gameField[$i-1][$j+2]->view = true;
                                if($i-1>$min&&$j-2>$min)$json->gameField[$i-1][$j-2]->view = true;
    
                            }
                        }
                    }
                }

            }
            
        }
        foreach ($json->gameField as $row){
            foreach($row as $cell){
                if($cell->view == false){
                    $cell->resCount =0;
                    $cell->contains = false;
                    $cell->obstacle = 0;
                }
            }
        }


        return $json;
    }

    function checkPlayerInRoom(){
        global $flag;
        global $array;
        global $json;
        if($json->local == 0){
            foreach ($array as $key => $value) {
                if($value["name"] == $json->gamePlayers[$json->gameTurn]->name){
                    return false;
                }
            }
            $json->gamePlayers[$json->gameTurn]->live = false;
            return true;
        }else{
            return false;
        }
    }
?>