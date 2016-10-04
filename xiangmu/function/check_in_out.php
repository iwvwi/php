<?php
session_start();

header("Content-type: application/json");

require_once "../db/user.func.php";

date_default_timezone_set('Asia/Shanghai');
$num=$_SESSION['username'];
$time=get_outTime($num);
$time2=date("H:i:s",time());

if(is_in($num)){
    if(strtotime($time)>strtotime($time2)){
        echo '{ "type": 3 }';
    }
    else if(is_out($num)){
        echo '{ "type": 5 }';
    }
    else{
        echo '{ "type": 4 }';
    }
}
else{
    if(strtotime($time)>strtotime($time2)){
        echo '{ "type": 1 }';
    }
    else{
        echo '{ "type": 2 }';
    }
}