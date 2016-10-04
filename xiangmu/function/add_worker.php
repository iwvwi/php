<?php
session_start();
$err_message = "";
$grp=$_SESSION['usergroup'];
if($grp!=2){
    header("location: ../login.php");
}

require_once "../db/user.func.php";

if(isset($_POST['num'])){
    $num=$_POST['num'];
    $name=$_POST['name'];
    $up=$_POST['up_time'];
    $down=$_POST['down_time'];
    $dept=$_POST['dept'];
    $pwd1=$_POST['password1'];
    $pwd2=$_POST['password2'];

    if($pwd1==$pwd2){
        if(is_same($num)){$err_message = "已有相同工号";}
        else{
            addworker($num,$name,$pwd1,$up,$down,$dept);
            header("location: admin_set.php");
        }
    }else{
        $err_message = "两次输入的密码不一致";
    }
}


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>添加人员</title>
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>

<form action="" method="post">
    <input type="text" name="num" placeholder="工号" required="required"/>
    <input type="text" name="name" placeholder="姓名" required="required"/>
    <input type="password" name="password1" placeholder="密码" required="required"/>
    <input type="password" name="password2" placeholder="密码确认" required="required"/></br>
    上班时间：
    <input type="time" name="up_time" placeholder="上班时间" required="required"/>
    下班时间：
    <input type="time" name="down_time"  placeholder="下班时间" required="required"/>
    部门：
    <select name="dept">
        <option value="1">研发部</option>
        <option value="2">市场部</option>
        <option value="3">售后部</option>
    </select>
    </br>
    <button type="submit">确定</button>
</form>

<?= $err_message ?>

</body>
</html>