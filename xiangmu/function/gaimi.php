<?php
session_start();
$err_message = "";

require_once "../db/user.func.php";

$user=$_SESSION['username'];

if(isset($_POST['password1'])){
    $pwd1=$_POST['password1'];
    $pwd2=$_POST['password2'];
    $pwd3=$_POST['password3'];

    if(validUser($user,$pwd1)){
        if($pwd2==$pwd3){
            gai_mi($user,$pwd2);
            header("location: ../user_detail.php");
        }else{
            $err_message = "两次输入的密码不一致";
        }
    }else{
        $err_message = "原密码错误";
    }

}


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>修改密码</title>
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>

<form action="" method="post">
    <input type="password" name="password1" placeholder="原密码" required="required"/>
    <input type="password" name="password2" placeholder="新密码" required="required"/>
    <input type="password" name="password3" placeholder="密码确认" required="required"/>
    <button type="submit">确认</button>
</form>

<?= $err_message ?>

</body>
</html>
