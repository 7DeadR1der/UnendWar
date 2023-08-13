<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
        include_once("game/classes.php");
        var_dump($_SESSION['user']);

        echo $_SESSION["user"]["login"];



        if(empty($_GET['test'])){
                echo '1111111111111111111111111111111';
        }else{
                echo '2222222222222222222222222222222';
        }
        // $array = [1,2,3,4];
        // foreach ($array as $e) {
        //         if(isset($e) && $e!=false){
        //                 echo "proshel false";
        //         }else{
        //                 echo "ne proshel false";
        //         }
        // }
        // if(isset($array[5]) && $array[5]!=false){
        //         echo "proshel false";
        // }else{
        //         echo "ne proshel false";
        // }
        /*
        $k='';
        $j='hire';
        switch($k){
                case'':
                        echo 'null string';
                        break;
                case'hire':
                        echo 'hire string';
                        break;
                default:
                        echo 'error';
                        break;
                        

        }
        $fd=3;
        $ddd=cll($fd);
        echo $ddd;
        function cll($fd){
                $fd+=3;
                $fd*=2;
                return $fd;
        }
        print_r($gameSettings);
*/
        //session_start();
    
/*

        session_start();
        require_once 'general.php';
        $gameCreator = 2;
        $query = mysqli_query($connect, "SELECT `id_room` FROM `rooms` WHERE (`id_creator` = '$gameCreator')
        ORDER BY `id_room` DESC LIMIT 1");
        print_r($query);*/
        //echo $query;
        //$idroom = mysqli_fetch_row($query);
        //print_r($idroom);
        //echo time();
         //WHERE `id_creator` = '$gameCreator'021
         
        //isset($_COOKIE['lm'])?setcookie("lm",time()):setcookie("lm",'123123');
        //echo $_COOKIE['lm'];
        /*

        class TestObj {
                public $name, $number;
                function __construct($name,$number)
                {
                        $this->name = $name;
                        $this->number = $number;
                }
        }
        $anton = new TestObj("Anton",20);
        $max = new TestObj("Max",12);
        $copy = $anton;
        print_r($anton);
        echo "<br>";
        print_r($max);
        echo "<br>";
        print_r($copy);
        echo "<br>";
        $copy->name = "Copy NULL";
        $anton->number = 999999;
        print_r($anton);
        echo "<br>";
        print_r($copy);
        function test($obj){
                $obj->name='test';
                $obj->number=0;
                //return $obj;
        }
        test($copy);
        //$secObj = test($copy);
        echo "<br>";
        print_r($anton);
        echo "<br>";
        print_r($copy);
        echo "<br>";    

        //print_r($secObj);
        /*
        for($i=0;$i<100;$i++){
                $arr = ['111','222','333','444'];
                $k = rand(0,3);
                array_splice($arr,$k,1);
                print_r($arr);
                echo "<br>";
        }*//*
        $arrays = ['asdas',213,['okey', $anton],'asds444'];
        $data = json_encode($arrays);
        print_r($data);
*/

?>