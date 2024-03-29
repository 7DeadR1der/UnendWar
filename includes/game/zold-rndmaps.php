<?php
$mapSettings = 'rnd';
        $zones = [];
        //выбор шаблона
        $array_pattern = [];
        //if($count<=6){
            //array_push($array_pattern,'sixPat');
            if($count<=4){
                array_push($array_pattern,'quad');
                array_push($array_pattern,'rubi');
                array_push($array_pattern,'crystal');
                if($count<=2){
                    array_push($array_pattern,'fire');
                    array_push($array_pattern,'shotDown');
                    array_push($array_pattern,'skirmish');
                    array_push($array_pattern,'richBeach');
                    //array_push($array_pattern,'midPat');
                }
            }
        //}
        $selectPattern = $array_pattern[rand(0,count($array_pattern)-1)];
        switch($selectPattern){
            case'fire':
                $zones = [
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,0],[0,1],[1,1],[2,0],[2,1],[3,0],[3,1]]],//[1,0],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[0,2],[0,3],[1,2],[1,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,4],[0,5],[1,4],[1,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,6],[2,7],[3,6],[3,7]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[4,0],[4,1],[5,0],[5,1]]],
                    ["dif" => 1, "player" => 0, "value" => 7, "cells" => [[4,2],[4,3],[5,2],[5,3]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[4,4],[4,5],[5,4],[5,5]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[4,6],[4,7],[5,6],[5,7]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[6,0],[6,1],[7,0],[7,1]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[6,2],[6,3],[7,2],[7,3]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,4],[6,5],[7,4],[7,5],[6,6],[6,7],[7,7]]],//,[7,6]
                ];
                array_push($startPositions,$field[1][0]);
                array_push($startPositions,$field[7][6]);
                break;
            case'shotDown':
                $zones = [
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[0,2],[0,3],[1,2],[1,3]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,4],[0,5],[0,7],[1,4],[1,5],[1,6],[1,7]]],//[0,6],
                    ["dif" => 1, "player" => 0, "value" => 10, "cells" => [[2,0],[2,1],[3,0],[3,1],[4,0],[4,1],[5,0],[5,1]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,2],[4,3],[5,2],[5,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,4],[4,5],[5,4],[5,5]]],
                    ["dif" => 1, "player" => 0, "value" => 10, "cells" => [[2,6],[2,7],[3,6],[3,7],[4,6],[4,7],[5,6],[5,7]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,0],[6,1],[6,2],[6,3],[7,0],[7,2],[7,3]]],//,[7,1]
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[6,4],[6,5],[7,4],[7,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[6,6],[6,7],[7,6],[7,7]]],
                ];
                array_push($startPositions,$field[0][6]);
                array_push($startPositions,$field[7][1]);
                break;
            case'skirmish':
                $zones = [
                    ["dif" => 9, "player" => 0, "value" => 0 , "cells" => [[0,2],[1,2]]],
                    ["dif" => 9, "player" => 0, "value" => 0 , "cells" => [[6,5],[7,5]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,1],[1,0],[1,1]]],//[0,0],
                    ["dif" => 1, "player" => 0, "value" => 21, "cells" => [[0,3],[0,4],[0,5],[0,6],[0,7],[1,3],[1,4],[1,5],[1,6],[1,7],[2,3],[2,4],[2,5],[2,6],[2,7]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[3,3],[3,4],[4,3],[4,4]]],
                    ["dif" => 1, "player" => 0, "value" => 21, "cells" => [[5,0],[5,1],[5,2],[5,3],[5,4],[6,0],[6,1],[6,2],[6,3],[6,4],[7,0],[7,1],[7,2],[7,3],[7,4]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,6],[6,7],[7,6]]],//,[7,7]
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,0],[2,1],[2,2],[3,0],[3,1],[3,2],[4,0],[4,1],[4,2]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[3,5],[3,6],[3,7],[4,5],[4,6],[4,7],[5,5],[5,6],[5,7]]],
                    
                ];
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[7][7]);
                break;
            case'richBeach':
                $zones = [
                    ["dif" => 9, "player" => 0, "value" => 0 , "cells" => [[5,3],[5,4],[6,3],[6,4],[7,3],[7,4]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,2],[0,3],[1,2],[1,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,4],[0,5],[1,4],[1,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,0],[2,1],[3,0],[3,1]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,6],[2,7],[3,6],[3,7]]],
                    ["dif" => 1, "player" => 0, "value" => 7, "cells" => [[4,0],[4,1],[4,2],[4,3]]],
                    ["dif" => 1, "player" => 0, "value" => 7, "cells" => [[4,4],[4,5],[4,6],[4,7]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,0],[6,1],[6,2],[7,0],[7,2]]],//[7][1]
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,5],[6,6],[6,7],[7,5],[7,7]]],//[7][6]
                ];
                array_push($startPositions,$field[7][1]);
                array_push($startPositions,$field[7][6]);
                break;
            case'quad':
                $zones = [
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,1],[1,0],[1,1]]],//[0,0],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[0,2],[0,3],[1,2],[1,3]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[0,4],[0,5],[1,4],[1,5]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,6],[1,6],[1,7]]],//[0,7],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[2,0],[2,1],[3,0],[3,1]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[2,6],[2,7],[3,6],[3,7]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[4,0],[4,1],[5,0],[5,1]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,2],[4,3],[5,2],[5,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,4],[4,5],[5,4],[5,5]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[4,6],[4,7],[5,6],[5,7]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,0],[6,1],[7,1]]],//[7,0],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[6,2],[6,3],[7,2],[7,3]]],
                    ["dif" => 1, "player" => 0, "value" => 5 , "cells" => [[6,4],[6,5],[7,4],[7,5]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,6],[6,7],[7,6]]],//,[7,7]
                ];
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][7]);
                array_push($startPositions,$field[7][0]);
                array_push($startPositions,$field[7][7]);
                break;
            case'rubi':
                $zones = [
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,2],[0,3],[1,2],[1,3],[0,5],[1,4],[1,5]]],//[0,4],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[2,0],[2,1],[3,1],[4,0],[4,1],[5,0],[5,1]]],//[3,0],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[2,6],[2,7],[3,6],[3,7],[4,6],[5,6],[5,7]]],//,[4,7]
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,2],[4,3],[5,2],[5,3]]],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[4,4],[4,5],[5,4],[5,5]]],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[6,0],[6,1],[7,0],[7,1]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,2],[6,3],[7,2],[6,4],[6,5],[7,4],[7,5]]],//[7,3],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[6,6],[6,7],[7,6],[7,7]]],
                ];
                array_push($startPositions,$field[0][4]);
                array_push($startPositions,$field[3][0]);
                array_push($startPositions,$field[4][7]);
                array_push($startPositions,$field[7][3]);
                break;
            case'crystal':
                $zones = [
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[0,3],[1,3],[1,4]]],//[0,4],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[3,1],[4,0],[4,1]]],//[3,0],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[3,6],[3,7],[4,6]]],//,[4,7]
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[6,0],[6,1],[7,0],[7,1]]],
                    ["dif" => 0, "player" => 1, "value" => 10, "cells" => [[6,3],[6,4],[7,4]]],//[7,3],
                    ["dif" => 1, "player" => 0, "value" => 12, "cells" => [[6,6],[6,7],[7,6],[7,7]]],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[2,2],[2,3],[3,2],[3,3]]],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[2,4],[2,5],[3,4],[3,5]]],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[4,2],[4,3],[5,2],[5,3]]],
                    ["dif" => 1, "player" => 0, "value" => 7 , "cells" => [[4,4],[4,5],[5,4],[5,5]]],
                ];
                array_push($startPositions,$field[0][4]);
                array_push($startPositions,$field[3][0]);
                array_push($startPositions,$field[4][7]);
                array_push($startPositions,$field[7][3]);
                break;
            case'canyon':
                $zones = [
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[0,3],[0,4],[1,3],[1,4]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[3,0],[3,1],[4,0],[4,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[3,6],[3,7],[4,6],[4,7]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[6,0],[6,1],[7,0],[7,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[6,3],[6,4],[7,3],[7,4]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[6,6],[6,7],[7,6],[7,7]]],
                    ["dif" => 1, "player" => 0, "value" => 32, "cells" => [[2,2],[2,3],[2,4],[2,5],[3,2],[3,3],[3,4],[3,5],[4,2],[4,3],[4,4],[4,5],[5,2],[5,3],[5,4],[5,5]]],
                ];
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                break;
            case'cross':
                $zones = [
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[0,0],[0,1],[1,0],[1,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[0,3],[0,4],[1,3],[1,4]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[0,6],[0,7],[1,6],[1,7]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[3,0],[3,1],[4,0],[4,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[3,6],[3,7],[4,6],[4,7]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[6,0],[6,1],[7,0],[7,1]]],
                    ["dif" => 0, "player" => 1, "value" => 16, "cells" => [[6,3],[6,4],[7,3],[7,4]]],
                    ["dif" => 1, "player" => 0, "value" => 20, "cells" => [[6,6],[6,7],[7,6],[7,7]]],
                    ["dif" => 1, "player" => 0, "value" => 32, "cells" => [[2,2],[2,3],[2,4],[2,5],[3,2],[3,3],[3,4],[3,5],[4,2],[4,3],[4,4],[4,5],[5,2],[5,3],[5,4],[5,5]]],
                ];
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                array_push($startPositions,$field[0][0]);
                break;
            default:
                break;
        }
        
        shuffle($startPositions);
        $maxObs = 12;
        $countObs = 0;

        for($k=0;$k<count($zones);$k++){
            $countObstacles = count($zones[$k]['cells'])/4;
            $filledArray = [];
            // if($zones[$k]['player'] == 1){
            //     $rnd = rand(0,count($zones[$k]['cells'])-1);
            //     //$zones[$k]['cells'][$rnd]=
            //     $iC=$zones[$k]['cells'][$rnd][0];
            //     $jC=$zones[$k]['cells'][$rnd][1];
            //     array_push($startPositions,$field[$iC][$jC]);
            //     foreach($zones[$k]['cells'] as $key => $value){
            //         if($value == [$iC,$jC]){array_splice($zones[$k]['cells'],$key,1);}
            //         /*if($value[0] ==$iC+1 && $value[1] == $jC){array_splice($zones[$k]['cells'],$key,1);}
            //         if($value[0] ==$iC-1 && $value[1] == $jC){array_splice($zones[$k]['cells'],$key,1);}
            //         if($value[0] ==$iC && $value[1] == $jC+1){array_splice($zones[$k]['cells'],$key,1);}
            //         if($value[0] ==$iC && $value[1] == $jC-1){array_splice($zones[$k]['cells'],$key,1);}*/
            //         /*if($value == [$iC,$jC+1])
            //             array_splice($zones[$k]['cells'],$key,1);
            //         if($value == [$iC-1,$jC])
            //             array_splice($zones[$k]['cells'],$key,1);
            //         if($value == [$iC,$jC-1])
            //             array_splice($zones[$k]['cells'],$key,1);*/
            //     }
            // }
            $flag = true;
            
            $times = ceil($zones[$k]['value']/8);
            $valueObj = $zones[$k]['value'];
            $valueEnemy = $zones[$k]['value'];
            $arrayEnemies = ['B','B','B','B','B','B','O','O','O','D'];
            $arrayObjects = [];
            if($zones[$k]['dif'] == 1){
                // C = Chest , G = goldOre , A = allGoldInOneOre , F = Fort Ogre
                $arrayObjects = ['C','C','C','C','C','G','P','P','F','F'];
            }else{
                $arrayObjects = ['G','G','G','G','G','G','G','G','G','G'];
            }

            while(($valueObj>0 && count($zones[$k]['cells'])>0)){
                $rndCh= array_rand($arrayObjects);
                $rnd = rand(0,count($zones[$k]['cells'])-1);
                switch($arrayObjects[$rndCh]){
                    case 'C': //chest
                        $thisValue = 5;
                        if($valueObj>=$thisValue){
                            $field[$zones[$k]['cells'][$rnd][0]][$zones[$k]['cells'][$rnd][1]]->contains = new Unit($players[0]->faction->t1,0,true);
                            $valueObj-=$thisValue;
                            foreach($zones[$k]['cells'] as $key => $value){
                                if($value == $zones[$k]['cells'][$rnd])
                                    array_splice($zones[$k]['cells'],$key,1);
                            }
                        }
                        break;
                    case 'G':
                        $thisValue = 5;
                        $rescount = $thisValue;
                        if($valueObj<$thisValue){
                            $rescount = $valueObj;
                        }
                        $field[$zones[$k]['cells'][$rnd][0]][$zones[$k]['cells'][$rnd][1]]->resCount += $rescount;
                        $valueObj -= $rescount;
                        foreach($zones[$k]['cells'] as $key => $value){
                            if($value == $zones[$k]['cells'][$rnd]){
                                array_push($filledArray,$value);
                                array_splice($zones[$k]['cells'],$key,1);
                            }
                        }
                        break;
                    case 'P':
                        $thisValue = 7;
                        if($valueObj>=$thisValue){
                            $field[$zones[$k]['cells'][$rnd][0]][$zones[$k]['cells'][$rnd][1]]->contains = new Unit($players[0]->faction->tower,0,true);
                            $valueObj-=$thisValue;
                            $field[$zones[$k]['cells'][$rnd][0]][$zones[$k]['cells'][$rnd][1]]->resCount += $thisValue;
                            $valueEnemy-=$thisValue;
                            foreach($zones[$k]['cells'] as $key => $value){
                                if($value == $zones[$k]['cells'][$rnd])
                                    array_splice($zones[$k]['cells'],$key,1);
                            }
                        }
                        break;
                    case 'F':
                        $thisValue = 12;
                        if($valueObj>=$thisValue){
                            $field[$zones[$k]['cells'][$rnd][0]][$zones[$k]['cells'][$rnd][1]]->contains = new Unit($players[0]->faction->townhall,0,true);
                            $valueObj-=$thisValue;
                            $valueEnemy-=$thisValue;
                            foreach($zones[$k]['cells'] as $key => $value){
                                if($value == $zones[$k]['cells'][$rnd])
                                    array_splice($zones[$k]['cells'],$key,1);
                            }
                        }
                        break;
                    case 'D': //i dont know)
                        //nothing
                        break;
                    default:
                        break;
                    
                }
            }
            
            foreach($zones[$k]['cells'] as $key => $value){
                array_push($filledArray,$value);
            }
            if($zones[$k]['dif'] == 1){
                while(($valueEnemy>5 && count($filledArray)>0)){
                    $rndCh= array_rand($arrayEnemies);
                    $rnd = rand(0,count($filledArray)-1);
                    $enemy = $arrayEnemies[$rndCh];
                    /*if($field[$filledArray[$rnd][0]][$filledArray[$rnd][1]]->resCount>=7 && $valueEnemy>=7){
                        $enemy = 'P';
                    }*/
                    switch($enemy){
                        case 'B':
                            $thisValue = 5;
                            $field[$filledArray[$rnd][0]][$filledArray[$rnd][1]]->contains = new Unit($players[0]->faction->t2,0,true);
                            $valueEnemy-=8;
                            foreach($filledArray as $key => $value){
                                if($value == $filledArray[$rnd])
                                    array_splice($filledArray,$key,1);
                            }
                            break;
                        case 'O':
                            $thisValue = 7;
                            if($valueEnemy>=$thisValue){
                                $field[$filledArray[$rnd][0]][$filledArray[$rnd][1]]->contains = new Unit($players[0]->faction->t3,0,true);
                                $valueEnemy-=$thisValue;
                                foreach($filledArray as $key => $value){
                                    if($value == $filledArray[$rnd])
                                        array_splice($filledArray,$key,1);
                                }
                            }
                            break;
                        case 'D':
                            $thisValue = 12;
                            if($valueEnemy>=$thisValue){
                                $field[$filledArray[$rnd][0]][$filledArray[$rnd][1]]->contains = new Unit($players[0]->faction->warchief,0,true);
                                $valueEnemy-=$thisValue;
                                foreach($filledArray as $key => $value){
                                    if($value == $filledArray[$rnd])
                                        array_splice($filledArray,$key,1);
                                }
                            }
                            break;
                        default:
                            break;
                    }

                }
            }
            if($zones[$k]['dif'] == 9 ){
                foreach($filledArray as $key => $value){
                    if($countObs<$maxObs)
                                $field[$value[0]][$value[1]]->obstacle = mt_rand(1,2);
                                $countObs +=1;

                }
            }else if(count($filledArray)>0 && $zones[$k]['player'] == 0){
                foreach($filledArray as $key => $value){
                    if($field[$value[0]][$value[1]]->resCount == 0){
                        if($countObstacles>0){
                            if($countObs<$maxObs){
                                if(mt_rand(0,1)){
                                    $field[$value[0]][$value[1]]->obstacle = mt_rand(1,2);
                                    $countObstacles-=1;
                                    $countObs +=1;
                                }
                            }
                        }
                    }
                }
            }

        }