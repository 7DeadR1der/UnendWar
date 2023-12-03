<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    require_once '../general.php';
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
            $string = '';
            $kd='';$sm='';$ud=''; $orc=''; $rnd=''; 
            $c = ['','','','','','','','',''];
            /*$clrArr = [
                // gray - red - blue - orange - dark-blue - yellow - purple - pink - green
                '#bababa','#f59678','#6bccf7','#fec689','#8781bd','#fdf799','#bd8dbf','#f39aac','#7eca9c'
            ];*/
            //old
            //$clrArr = ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054'];
            //new
            //$clrArr = ['#bababa','#f59678','#6bccf7','#fec689','#8781bd','#fdf799','#bd8dbf','#f39aac','#7eca9c'];
            $clrArr = ['#bababa','#f59678','#6bccf7','#ffbd76','#8d87be','#fdf777','#cf8fd1','#f39aac','#7eca9c'];
            $string .= "<h4>".$game['name']." ".$game['id_room']."</h4>
            <p>Карта - ".$game['game_map']."</p>
            <p>Старт игры - ".$game['game_mode']."</p>
            <p>Тип игры - ".$game['game_type']."</p>
            <p>".$game['count_players']."/".$game['max_players']." игроков</p>";
            for($i = 0; $i<$game['max_players'];$i++){
                if($game['local']==1){
                    $c[$i] = 'selected';
                    $string .= "<div>player ".$i."<select name='player".$i."'>
                        <option value='player'>player</option>
                        <option value='none'>None</option>
                    </select>
                    <select name='faction".$i."'>
                        <option value='Kingdom'>Kingdom</option>
                        <option value='SeaMercs'>Seamercs</option>
                        <option value='Undead'>Undead</option>
                        <option value='Orcs'>Orcs</option>
                        <option selected value='Random'>Random</option>
                    </select>
                    </div>";
                    //<option value='seamercs'>Seamercs</option>
                    /*
                    <select name='color".$i."'>
                        <option ".$c[1]." style='background-color:".$clrArr[1]."' value=1>Red</option>
                        <option ".$c[2]." style='background-color:".$clrArr[2]."' value=2>Blue</option>
                        <option ".$c[3]." style='background-color:".$clrArr[3]."' value=3>Orange</option>
                        <option ".$c[4]." style='background-color:".$clrArr[4]."' value=4>Purple</option>
                        <option ".$c[5]." style='background-color:".$clrArr[5]."' value=5>Green</option>
                        <option ".$c[6]." style='background-color:".$clrArr[6]."' value=6>Yellow</option>
                        <option ".$c[0]." style='background-color:".$clrArr[0]."' value=0>Gray</option>
                    </select>

                    <select name='color' onchange='changePlayer(this.value,1)'>
                        <option ".$c[1]." style='background-color:".$clrArr[1]."' value=1>Red</option>
                        <option ".$c[2]." style='background-color:".$clrArr[2]."' value=2>Blue</option>
                        <option ".$c[3]." style='background-color:".$clrArr[3]."' value=3>Orange</option>
                        <option ".$c[4]." style='background-color:".$clrArr[4]."' value=4>Dark-blue</option>
                        <option ".$c[5]." style='background-color:".$clrArr[5]."' value=5>Yellow</option>
                        <option ".$c[6]." style='background-color:".$clrArr[6]."' value=6>Purple</option>
                        <option ".$c[7]." style='background-color:".$clrArr[7]."' value=6>Pink</option>
                        <option ".$c[8]." style='background-color:".$clrArr[8]."' value=6>Green</option>
                        <option ".$c[0]." style='background-color:".$clrArr[0]."' value=9>Gray</option>
                    </select>

                     */
                }else if(array_key_exists($i,$array)){
                    
                    switch ($array[$i]["faction"]){
                        case 'Kingdom':
                            $kd = 'selected';
                            break;
                        case 'SeaMercs':
                            $sm = 'selected';
                            break;
                        case 'Undead':
                            $ud = 'selected';
                            break;
                        case 'Orcs':
                            $orc = 'selected';
                            break;
                        default:
                            $rnd = 'selected';
                            break;
                    }
                    $c[$array[$i]["color"]] = "selected";

                    if($array[$i]["id"] == $_SESSION['user']['id']){
                        $string .= "<div>".$array[$i]["name"]." 
                        <select name='faction' onchange='changePlayer(this.value,0)'>
                            <option ".$kd." value='Kingdom'>Kingdom</option>
                            <option ".$sm." value='SeaMercs'>Seamercs</option>
                            <option ".$ud." value='Undead'>Undead</option>
                            <option ".$orc." value='Orcs'>Orcs</option>
                            <option ".$rnd." value='Random'>Random</option>
                        </select>
                        <select name='color' onchange='changePlayer(this.value,1)'>
                            <option ".$c[1]." style='background-color:".$clrArr[1]."' value=1>Red</option>
                            <option ".$c[2]." style='background-color:".$clrArr[2]."' value=2>Blue</option>
                            <option ".$c[3]." style='background-color:".$clrArr[3]."' value=3>Orange</option>
                            <option ".$c[4]." style='background-color:".$clrArr[4]."' value=4>Dark-blue</option>
                            <option ".$c[5]." style='background-color:".$clrArr[5]."' value=5>Yellow</option>
                            <option ".$c[6]." style='background-color:".$clrArr[6]."' value=6>Purple</option>
                            <option ".$c[7]." style='background-color:".$clrArr[7]."' value=6>Pink</option>
                            <option ".$c[8]." style='background-color:".$clrArr[8]."' value=6>Green</option>
                            <option ".$c[0]." style='background-color:".$clrArr[0]."' value=9>Gray</option>
                        </select>   
                        </div>";
                        //after insert into up code
                        //<option ".$sm." value='seamercs'>Seamercs</option>
                        //<option ".$orc." value='orcs'>Orcs</option>
                        //<option ".$rnd." value='random'>Random</option>
                        //<option ".$ud." value='undead'>Undead</option>
                    }else{
                        //$getLoginUsers = mysqli_query($connect, "SELECT `login` FROM `users` where `id_user` = '$players[$i]'");
                        $string .= "<div>".$array[$i]["name"]." 
                        <select disabled name='faction'>
                            <option ".$kd." value='Kingdom'>Kingdom</option>
                            <option ".$sm." value='SeaMercs'>Seamercs</option>
                            <option ".$ud." value='Undead'>Undead</option>
                            <option ".$orc." value='Orcs'>Orcs</option>
                            <option ".$rnd." value='Random'>Random</option>
                        </select>
                        
                        <select name='color' disabled>
                        <option ".$c[1]." style='background-color:".$clrArr[1]."' value=1>Red</option>
                        <option ".$c[2]." style='background-color:".$clrArr[2]."' value=2>Blue</option>
                        <option ".$c[3]." style='background-color:".$clrArr[3]."' value=3>Orange</option>
                        <option ".$c[4]." style='background-color:".$clrArr[4]."' value=4>Dark-blue</option>
                        <option ".$c[5]." style='background-color:".$clrArr[5]."' value=5>Yellow</option>
                        <option ".$c[6]." style='background-color:".$clrArr[6]."' value=6>Purple</option>
                        <option ".$c[7]." style='background-color:".$clrArr[7]."' value=6>Pink</option>
                        <option ".$c[8]." style='background-color:".$clrArr[8]."' value=6>Green</option>
                        <option ".$c[0]." style='background-color:".$clrArr[0]."' value=9>Gray</option>
                        
                        </select>
                        </div>";

                    }
                    
            $kd='';$sm='';$ud=''; $orc=''; $rnd=''; 
            $c = ['','','','','','','','',''];
                }
            }
            if($array[0]["id"] == $_SESSION['user']['id'] && $game['local'] == 0){
                $string .= "<button onclick='startGame()'>Начать игру</button>";
            }else if ($game['local'] == 1){
                $string .= "<button onclick='startGame()'>Начать игру</button>";
            }
            $string .= "<button onclick='exitRoom()'>Выйти из лобби</button>";
            //echo $string;
            echo response(1,'',$string);
        }else if ($game['game_state']==1){ //game started, load gamefield and game too
            //echo "Еще не сделано(((";
            
            //header('Content-type: application/json');
            $json = json_decode($game['game_json']);
            $flag = false;
            
            while(!isset($json->gamePlayers[$json->gameTurn]) || $json->gamePlayers[$json->gameTurn]->live==false || checkPlayerInRoom())
            {
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

            echo response(2,'',$json);
        }else { //game end, kick user from room
            $json = json_decode($game['game_json']);
            echo response(3,'',$json->gamePlayers);
        }
    }
    include("../update.php");


    function view($type){
        global $json;
        $sMin = -1;
        $iMax = $json->gameSize[0];
        $jMax = $json->gameSize[1];
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
                    if($count<3 && in_array('farSight',$cell->contains->ability)){
                        $count += 1;
                    }
                    $cell->view = true;
                    if($count>0){
                        if($i+1<$iMax)$json->gameField[$i+1][$j]->view = true;
                        if($j+1<$jMax)$json->gameField[$i][$j+1]->view = true;
                        if($i-1>$sMin)$json->gameField[$i-1][$j]->view = true;
                        if($j-1>$sMin)$json->gameField[$i][$j-1]->view = true;
                        if($count>1){
                            if($i+2<$iMax)$json->gameField[$i+2][$j]->view = true;
                            if($j+2<$jMax)$json->gameField[$i][$j+2]->view = true;
                            if($i-2>$sMin)$json->gameField[$i-2][$j]->view = true;
                            if($j-2>$sMin)$json->gameField[$i][$j-2]->view = true;
                            if($i+1<$iMax&&$j+1<$jMax)$json->gameField[$i+1][$j+1]->view = true;
                            if($i+1<$iMax&&$j-1>$sMin)$json->gameField[$i+1][$j-1]->view = true;
                            if($i-1>$sMin&&$j+1<$jMax)$json->gameField[$i-1][$j+1]->view = true;
                            if($i-1>$sMin&&$j-1>$sMin)$json->gameField[$i-1][$j-1]->view = true;
                            if($count>2){
                                if($i+3<$iMax)$json->gameField[$i+3][$j]->view = true;
                                if($j+3<$jMax)$json->gameField[$i][$j+3]->view = true;
                                if($i-3>$sMin)$json->gameField[$i-3][$j]->view = true;
                                if($j-3>$sMin)$json->gameField[$i][$j-3]->view = true;
                                if($i+2<$iMax&&$j+1<$jMax)$json->gameField[$i+2][$j+1]->view = true;
                                if($i+2<$iMax&&$j-1>$sMin)$json->gameField[$i+2][$j-1]->view = true;
                                if($i-2>$sMin&&$j+1<$jMax)$json->gameField[$i-2][$j+1]->view = true;
                                if($i-2>$sMin&&$j-1>$sMin)$json->gameField[$i-2][$j-1]->view = true;
                                if($i+1<$iMax&&$j+2<$jMax)$json->gameField[$i+1][$j+2]->view = true;
                                if($i+1<$iMax&&$j-2>$sMin)$json->gameField[$i+1][$j-2]->view = true;
                                if($i-1>$sMin&&$j+2<$jMax)$json->gameField[$i-1][$j+2]->view = true;
                                if($i-1>$sMin&&$j-2>$sMin)$json->gameField[$i-1][$j-2]->view = true;
    
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