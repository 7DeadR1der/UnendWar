<?php


function mapMaker($field, $map, $count, $type, $mode, $players){
    $startPositions = [];
    switch ($map){
        case '2lr':
            $field[7][0]->resCount = 10;
            $field[0][7]->resCount = 10;
            $field[0][3]->resCount = 10;
            $field[2][0]->resCount = 10;
            $field[7][4]->resCount = 10;
            $field[5][7]->resCount = 10;
            $field[0][0]->mountains = true;
            $field[0][1]->mountains = true;
            $field[1][0]->mountains = true;
            $field[2][4]->mountains = true;
            $field[3][7]->mountains = true;
            $field[4][0]->mountains = true;
            $field[5][3]->mountains = true;
            $field[6][7]->mountains = true;
            $field[7][6]->mountains = true;
            $field[7][7]->mountains = true;
            array_push($startPositions,$field[6][1]);
            array_push($startPositions,$field[1][6]);
            break;
        case '2gm':
            $field[0][2]->resCount = 10;
            $field[4][0]->resCount = 10;
            $field[0][7]->resCount = 10;
            $field[5][7]->resCount = 10;
            $field[7][3]->resCount = 10;
            $field[7][0]->mountains = true;
            $field[6][0]->mountains = true;
            $field[7][1]->mountains = true;
            $field[0][3]->mountains = true;
            $field[0][4]->mountains = true;
            $field[0][5]->mountains = true;
            $field[1][3]->mountains = true;
            $field[1][4]->mountains = true;
            $field[2][7]->mountains = true;
            $field[3][6]->mountains = true;
            $field[3][7]->mountains = true;
            $field[4][6]->mountains = true;
            $field[4][7]->mountains = true;
            $field[3][1]->mountains = true;
            $field[4][1]->mountains = true;
            $field[4][2]->mountains = true;
            $field[5][2]->mountains = true;
            $field[5][3]->mountains = true;
            $field[6][3]->mountains = true;
            $field[6][4]->mountains = true;
            array_push($startPositions,$field[1][0]);
            array_push($startPositions,$field[7][6]);

            break;
        case '2lm':
            $field[0][0]->resCount = 15;
            $field[1][7]->resCount = 15;
            $field[6][0]->resCount = 15;
            $field[7][7]->resCount = 15;
            $field[0][5]->mountains = true;
            $field[0][6]->mountains = true;
            $field[0][7]->mountains = true;
            $field[1][1]->mountains = true;
            $field[3][3]->mountains = true;
            $field[3][4]->mountains = true;
            $field[4][3]->mountains = true;
            $field[4][4]->mountains = true;
            $field[6][6]->mountains = true;
            $field[7][0]->mountains = true;
            $field[7][1]->mountains = true;
            $field[7][2]->mountains = true;
            array_push($startPositions,$field[6][2]);
            array_push($startPositions,$field[1][5]);

            break;
        case '2mc':
            //rework
            break;
        case '2or':
            $field[0][0]->resCount = 10;
            $field[7][7]->resCount = 10;
            $field[3][3]->resCount = 10;
            $field[4][4]->resCount = 10;
            $field[0][5]->resCount = 10;
            $field[7][2]->resCount = 10;
            $field[3][4]->mountains = true;
            $field[4][3]->mountains = true;
            $field[0][6]->mountains = true;
            $field[0][7]->mountains = true;
            $field[3][0]->mountains = true;
            $field[4][0]->mountains = true;
            $field[5][0]->mountains = true;
            $field[6][0]->mountains = true;
            $field[7][0]->mountains = true;
            $field[6][1]->mountains = true;
            $field[7][1]->mountains = true;
            $field[1][6]->mountains = true;
            $field[1][7]->mountains = true;
            $field[2][7]->mountains = true;
            $field[3][7]->mountains = true;
            $field[4][7]->mountains = true;
            array_push($startPositions,$field[0][1]);
            array_push($startPositions,$field[7][6]);
            break;
        case '2wp':
            $field[7][0]->resCount = 8;
            $field[0][7]->resCount = 8;
            $field[0][1]->resCount = 10;
            $field[1][0]->resCount = 10;
            $field[7][6]->resCount = 10;
            $field[6][7]->resCount = 10;
            $field[0][0]->mountains = true;
            $field[0][4]->mountains = true;
            $field[2][2]->mountains = true;
            $field[2][7]->mountains = true;
            $field[3][4]->mountains = true;
            $field[4][3]->mountains = true;
            $field[5][0]->mountains = true;
            $field[5][5]->mountains = true;
            $field[7][3]->mountains = true;
            $field[7][7]->mountains = true;
            array_push($startPositions,$field[7][1]);
            array_push($startPositions,$field[0][6]);
            break;
        case '4s':
            foreach($field[0] as $cell){
                $cell->mountains = true;
            }
            foreach($field[7] as $cell){
                $cell->mountains = true;
            }
            $field[1][3]->resCount = 12;
            $field[3][6]->resCount = 12;
            $field[6][4]->resCount = 12;
            $field[4][1]->resCount = 12;
            $field[3][3]->resCount = 6;
            $field[3][4]->resCount = 6;
            $field[4][3]->resCount = 6;
            $field[4][4]->resCount = 6;
            $field[1][0]->mountains = true;
            $field[1][1]->mountains = true;
            $field[2][0]->mountains = true;
            $field[3][0]->mountains = true;
            $field[4][0]->mountains = true;
            $field[5][0]->mountains = true;
            $field[6][0]->mountains = true;
            $field[6][1]->mountains = true;
            $field[1][6]->mountains = true;
            $field[1][7]->mountains = true;
            $field[2][7]->mountains = true;
            $field[3][7]->mountains = true;
            $field[4][7]->mountains = true;
            $field[5][7]->mountains = true;
            $field[6][7]->mountains = true;
            $field[6][6]->mountains = true;
            array_push($startPositions,$field[1][5]);
            array_push($startPositions,$field[2][1]);
            array_push($startPositions,$field[6][2]);
            array_push($startPositions,$field[5][6]);
            break;
        case '4lt':
            $field[0][0]->resCount = 10;
            $field[0][7]->resCount = 10;
            $field[7][0]->resCount = 10;
            $field[7][7]->resCount = 10;
            $field[0][4]->resCount = 10;
            $field[3][0]->resCount = 10;
            $field[4][7]->resCount = 10;
            $field[7][3]->resCount = 10;
            $field[2][4]->mountains = true;
            $field[3][2]->mountains = true;
            $field[4][5]->mountains = true;
            $field[5][3]->mountains = true;
            array_push($startPositions,$field[0][2]);
            array_push($startPositions,$field[2][7]);
            array_push($startPositions,$field[7][5]);
            array_push($startPositions,$field[5][0]);
            break;
        case '4cg':
            $field[0][0]->resCount = 15;
            $field[0][7]->resCount = 15;
            $field[7][0]->resCount = 15;
            $field[7][7]->resCount = 15;
            $field[3][3]->resCount = 15;
            $field[4][4]->resCount = 15;
            $field[0][2]->mountains = true;
            $field[1][6]->mountains = true;
            $field[2][0]->mountains = true;
            $field[3][4]->mountains = true;
            $field[4][3]->mountains = true;
            $field[5][7]->mountains = true;
            $field[6][1]->mountains = true;
            $field[7][5]->mountains = true;
            array_push($startPositions,$field[3][0]);
            array_push($startPositions,$field[0][3]);
            array_push($startPositions,$field[4][7]);
            array_push($startPositions,$field[7][4]);

            break;
        case '6gn':
            foreach($field as $row){
                foreach($row as $cell){
                    $cell->resCount =3;
                }
            }
            array_push($startPositions,$field[0][0]);
            array_push($startPositions,$field[0][4]);
            array_push($startPositions,$field[2][7]);
            array_push($startPositions,$field[5][0]);
            array_push($startPositions,$field[7][3]);
            array_push($startPositions,$field[7][7]);
            break;
        case '6gw':
            $field[0][3]->resCount = 10;
            $field[0][7]->resCount = 10;
            $field[1][0]->resCount = 10;
            $field[2][5]->resCount = 10;
            $field[3][2]->resCount = 10;
            $field[5][4]->resCount = 10;
            $field[7][0]->resCount = 10;
            $field[6][7]->resCount = 10;
            $field[7][4]->resCount = 10;
            $field[0][0]->mountains = true;
            $field[0][1]->mountains = true;
            $field[0][5]->mountains = true;
            $field[3][3]->mountains = true;
            $field[3][5]->mountains = true;
            $field[3][7]->mountains = true;
            $field[5][3]->mountains = true;
            $field[5][0]->mountains = true;
            $field[7][2]->mountains = true;
            $field[7][6]->mountains = true;
            $field[7][7]->mountains = true;

            array_push($startPositions,$field[1][7]);
            array_push($startPositions,$field[0][4]);
            array_push($startPositions,$field[2][0]);
            array_push($startPositions,$field[6][0]);
            array_push($startPositions,$field[5][7]);
            array_push($startPositions,$field[7][3]);

            break;
        case '2lra':

            break;
        case '2lrb':

            break;
        case '2lrc':

            break;
        default:

            break;
    }
    playerMaker($mode,$startPositions,$count,$players);
}

function playerMaker($mode,$startPositions,$count,$players){
    $n = $count;
    $l = count($startPositions);
    for ($i=0;$i<$l;$i++){
        if($n>0){
            $k = rand(0,$l-1);
            $startPositions[$k]->resCount = 0;
            switch($mode){
                case 'classic':
                    $startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                    break;
                case 'fast':
                    $startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                    break;
                case 'nomad':
                    $startPositions[$k]->contains = new Unit($players[$i+1]->faction->t1,$i+1,true);
                    $players[$i+1]->gold +=4;
                    break;
                default:
                    $startPositions[$k]->contains = new Unit($players[$i+1]->faction->townhall,$i+1,true);
                    break;
            
            }
            $n-=1;
            array_splice($startPositions,$k,1);
        }
    }

}
?>