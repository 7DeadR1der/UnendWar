
<!DOCTYPE html>
<html lang="en">
<head>
    
<link rel="stylesheet" href="../style.css">
</head>
<body>
<?php


$land = 0;


$clrLands = ['#b1c37b','#f0fafa','#e8d479','#b1c37b'];    // ['#b1c37b','#f0fafa','#e8d479','#b1c37b'];
/*
$fullColor = [
    '#f59678',        //'#f2684e',         //'#bababa', //0 красный
    '#8781bd',        //'#8394ca',        //'#4d6fb9',         //'#fc9393', //1 синий 
    '#7eca9c',        //'#2ab572',         //'#60c0ff', //2 зеленый
    '#fdf799',        //'#fef164',         //'#ffae58', //3 желтый
    '#fec689',        //'#f9ad59',         //'#f190ff', //4 оранжевый
    '#6bccf7',        //'#00bdf4',         //'#54fd7a', //5 голубой
    '#f39aac',        //'#ee68a5',         //'#e3f054', //6 розовый
    '#bd8dbf',        //'#a55da6',         //'#bababa', //7 фиолетовый
    '#bababa'         //'#0bb3b6'  7acac7         //'#bababa'  //8 бирюзовый
];*/
$fullColor = ['#bababa','#98dcde','#f39197','#8d92d9','#d88bc5','#f6f08c','#f19e65','#73e694','#b68c76'];

$clrPl = [
    '#bababa', //mercs
    '#6bccf7', //kingdom
    '#fec689', //orcs
    '#f39aac', //undead
];
/*
    ['#bababa', '#fc9393', '#60c0ff', '#ffae58', '#f190ff', '#54fd7a', '#e3f054']
*/

$unit = [
    "kt1" => "../img/units/KingdomT1.png",
    "kt2" => "../img/units/KingdomT2.png",
    "kt3" => "../img/units/KingdomT3.png",
    "kwc" => "../img/units/KingdomWarchief.png",
    "kth" => "../img/units/KingdomTownhall.png",
    "ktw" => "../img/units/KingdomTower.png",
    "st1" => "../img/units/SeaMercsT1.png",
    "st2" => "../img/units/SeaMercsT2.png",
    "st3" => "../img/units/SeaMercsT3.png",
    "swc" => "../img/units/SeaMercsWarchief.png",
    "sth" => "../img/units/SeaMercsTownhall.png",
    "stw" => "../img/units/SeaMercsTower.png",
    "ut1" => "../img/units/UndeadT1.png",
    "ut2" => "../img/units/UndeadT2.png",
    "ut3" => "../img/units/UndeadT3.png",
    "uwc" => "../img/units/UndeadWarchief.png",
    "uth" => "../img/units/UndeadTownhall.png",
    "utw" => "../img/units/UndeadTower.png",
    "ot1" => "../img/units/OrcsT1.png",
    "ot2" => "../img/units/OrcsT2.png",
    "ot3" => "../img/units/OrcsT3.png",
    "owc" => "../img/units/OrcsWarchief.png",
    "oth" => "../img/units/OrcsTownhall.png",
    "otw" => "../img/units/OrcsTower.png",
    "nt1" => "../img/units/NeutralT1.png",
    "nt2" => "../img/units/NeutralT2.png",
    "nt3" => "../img/units/NeutralT3.png",
    "nwc" => "../img/units/NeutralWarchief.png",
    "nth" => "../img/units/NeutralTownhall.png",
    "ntw" => "../img/units/NeutralTower.png"
];
$objs = [
    "mnt" => "../img/obstacle/".$land."1.png",
    "tre" => "../img/obstacle/".$land."2.png",
    "cst" => "../img/obstacle/".$land."3.png",
    "ore" => "../img/goldOre.png"
];

// $objs['']
// $unit['']
/*
$field = [
    [
        ["contains"=>0 , "src"=>$objs['mnt'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['otw'], "owner"=>2],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['oth'], "owner"=>2],
        ["contains"=>1 , "src"=>$unit['ot1'], "owner"=>2],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>$objs['tre'], "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kwc'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ot2'], "owner"=>2],
        ["contains"=>1 , "src"=>$unit['ot3'], "owner"=>2],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['owc'], "owner"=>2],
        ["contains"=>1 , "src"=>$unit['st3'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st3'], "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ot1'], "owner"=>2],
        ["contains"=>0 , "src"=>$objs['mnt'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['stw'], "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kth'], "owner"=>1],
        ["contains"=>1 , "src"=>$unit['kt1'], "owner"=>1],
        ["contains"=>1 , "src"=>$unit['kt3'], "owner"=>1],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['swc'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kt2'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>$objs['mnt'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ut3'], "owner"=>3],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st1'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st2'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['sth'], "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ktw'], "owner"=>1],
        ["contains"=>1 , "src"=>$unit['ut1'], "owner"=>3],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st1'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['stw'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['tre'], "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['uwc'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['ut1'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['ut2'], "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st3'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kt1'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ut2'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['uth'], "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['utw'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['st1'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['tre'], "owner"=>0]
    ],
];*/

$field = [
    [
        ["contains"=>0 , "src"=>$objs['mnt'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['ore'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['otw'], "owner"=>4],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['oth'], "owner"=>4],
        ["contains"=>1 , "src"=>$unit['ot1'], "owner"=>4],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>$objs['tre'], "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kwc'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ot2'], "owner"=>2],
        ["contains"=>1 , "src"=>$unit['ot3'], "owner"=>2],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['owc'], "owner"=>2],
        ["contains"=>1 , "src"=>$unit['st3'], "owner"=>0],
        ["contains"=>1 , "src"=>$unit['st3'], "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ot1'], "owner"=>2],
        ["contains"=>0 , "src"=>$objs['mnt'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['stw'], "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>"", "owner"=>1],
        ["contains"=>1 , "src"=>"", "owner"=>2],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kt2'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>"", "owner"=>3],
        ["contains"=>1 , "src"=>"", "owner"=>4],
        ["contains"=>1 , "src"=>"", "owner"=>5],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>"", "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>3],
        ["contains"=>1 , "src"=>"", "owner"=>6],
        ["contains"=>1 , "src"=>"", "owner"=>7],
        ["contains"=>1 , "src"=>"", "owner"=>8],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>0 , "src"=>$unit['st3'], "owner"=>0],
        ["contains"=>0 , "src"=>"", "owner"=>0]
    ],
    [
        ["contains"=>1 , "src"=>$unit['kt1'], "owner"=>1],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['ut2'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['uth'], "owner"=>3],
        ["contains"=>0 , "src"=>"", "owner"=>0],
        ["contains"=>1 , "src"=>$unit['utw'], "owner"=>3],
        ["contains"=>1 , "src"=>$unit['st1'], "owner"=>0],
        ["contains"=>0 , "src"=>$objs['tre'], "owner"=>0]
    ],
];

echo "<div id='game-field'>";
for($i=0;$i<8;$i++){
    for($j=0;$j<8;$j++){
        echo "<img class='gfCell' ";
        if($field[$i][$j]['contains']!=0){
            echo "src='".$field[$i][$j]['src']."' ";
            echo "style='background-color: ".$fullColor[$field[$i][$j]['owner']]."' ";
        }else{
            if($field[$i][$j]['src'] == ''){
                echo "src='../img/null.png' ";
            }else{
                echo "src='".$field[$i][$j]['src']."' ";
            }
            echo "style='background-color: ".$clrLands[$land]."' ";
        }

        echo ">";
    }
}


echo "</div>";
?>
</body>
</html>