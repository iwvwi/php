<?php
session_start();

$err_message = "";
$usr="";

require_once "db/user.func.php";

// 如果是提交表单
if (isset($_POST['username'])) {

    $usr = $_POST['username'];
    $pwd = $_POST['password'];

    if(validUser($usr,$pwd))
    {
        $grp=getUserGroup($usr);

        $_SESSION['username'] = $usr;
        $_SESSION['usergroup'] = $grp;
        $_SESSION['logintype'] = "bypassword";

        // 登录后重定向
        header("location: user_detail.php");
    } else {
        $err_message = "工号或密码不正确";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>


<div class="content center">
    <div class="form-box" style="width: 400px;margin: 100px auto">
        <div style="width: 280px;margin: 60px auto;position: relative">
            <form action="" method="post">
                <input type="text" name="username" value="<?=$usr?>" placeholder="工号" autocomplete="off" autofocus="autofocus" required="required"/>
                <input type="password" name="password" placeholder="密码" required="required"/>
                <button type="submit">登录</button>
                <div style="position: absolute;top: -45px;left: 0" class="error text-center">
                    <?= $err_message ?>
                </div>
            </form>
        </div>
    </div>
</div>



</body>
</html>