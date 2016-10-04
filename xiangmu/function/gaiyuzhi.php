<?php
session_start();
$err_message = "";
$grp=$_SESSION['usergroup'];
if($grp!=2){
    header("location: ../login.php");
}

require_once "../db/user.func.php";

if(isset($_POST['lates'])){
    $late=$_POST['lates'];
    $quit=$_POST['quits'];
    $leave=$_POST['leavess'];
    $away=$_POST['aways'];
    gai_yuzhi($late,$quit,$leave,$away);
    header("location: admin_set.php");
}


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>改阈值</title>
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>

<form action="" method="post">
    <input type="text" name="lates" placeholder="迟到次数" required="required"/>
    <input type="text" name="quits" placeholder="早退次数" required="required"/>
    <input type="text" name="leavess" placeholder="请假次数" required="required"/>
    <input type="text" name="aways" placeholder="旷工次数" required="required"/></br>
    <button type="submit">确定</button>
</form>

<?= $err_message ?>

</body>
</html>