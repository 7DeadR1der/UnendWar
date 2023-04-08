<?php 

    require_once '../connect.php';
    $rooms = mysqli_query($connect,'SELECT * FROM `rooms` WHERE `count_players` < `max_players` AND `game_state` = 0');
    if(mysqli_num_rows($rooms)>0){
        //$listRooms = mysqli_fetch_array($rooms);
        /*foreach ($listRooms as $row) {*/
        while($row = mysqli_fetch_array($rooms)){
            
            echo "<div class='room-game'>
                <h5>".htmlspecialchars($row['name'])."</h5>
                <p>".htmlspecialchars($row['game_map'])."</p>
                <p>".htmlspecialchars($row['game_type'])."</p>
                <p>".htmlspecialchars($row['game_mode'])."</p>
                <p>".htmlspecialchars($row['count_players'])."/".htmlspecialchars($row['max_players'])."</p>
                <button onclick='joinRoom(`".htmlspecialchars($row['id_room'])."`)'>join</button>
            </div>";
        }
    }

?>