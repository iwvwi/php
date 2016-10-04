<?php
session_start();
require_once "../db/user.func.php";
$grp=$_SESSION['usergroup'];
if($grp!=2){
    header("location: ../login.php");
}
$num=$_GET['num'];
if(isset($_POST['up_time'])){
    $time1=$_POST['up_time'];
    $time2=$_POST['down_time'];
    gai_time($num,$time1,$time2);
    header("location: admin_set.php");
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>修改工作时间</title>
</head>
<body>

<form action="" method="post">
    新上班时间：<input type="time" name="up_time" placeholder="上班时间" required="required"/></br>
    新下班时间：<input type="time" name="down_time"  placeholder="下班时间" required="required"/></br>
    <button type="submit">确认</button>
</form>


</body>
</html>