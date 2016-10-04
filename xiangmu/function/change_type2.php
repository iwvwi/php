<?php
require_once "../db/user.func.php";
$rows=getUser3();

foreach($rows as $row){
    $num=$row['num'];

    if(isset($_POST[$num])){
        checkla($num);
        $value=$_POST[$num];
        checkstatus($num,$value);

        if($value==2){add_late($num);}
        elseif($value==3){add_quit($num);}
        elseif($value==4){add_leave($num);}
        elseif($value==5){add_away($num);}

        header("location: admin_kq.php");
    }
    else{
        header("location: admin_kq.php");
    }
}