<?php 

    require_once '../general.php';
    $rooms = mysqli_query($connect,'SELECT `id_room`,`name`,`game_map`,`game_type`,`game_mode`,`count_players`,`max_players` FROM `rooms` WHERE `count_players` < `max_players` AND `game_state` = 0 AND `local` = 0 ORDER BY `id_room` DESC');
    if(mysqli_num_rows($rooms)>0){
        //$listRooms = mysqli_fetch_array($rooms);
        /*foreach ($listRooms as $row) {*/
        while($row = mysqli_fetch_array($rooms)){
            
            echo "<div class='room-game'>
                <h5>".htmlspecialchars($row['name'])."".htmlspecialchars($row['id_room'])."</h5>
                <p>".htmlspecialchars($row['game_map'])."</p>
                <p>".htmlspecialchars($row['game_type'])."</p>
                <p>".htmlspecialchars($row['game_mode'])."</p>
                <p>".htmlspecialchars($row['count_players'])."/".htmlspecialchars($row['max_players'])."</p>
                <button onclick='joinRoom(`".htmlspecialchars($row['id_room'])."`)'>join</button>
            </div>";
        }
    }

?>