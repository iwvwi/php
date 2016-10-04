<?php
session_start();
$err_message="";
$err_message2="";
require_once "../db/user.func.php";
$rows=getUser2();
$rows2=getUser3();

$grp=$_SESSION['usergroup'];
if($grp!=2){
    header("location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理员管理页面</title>
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>

    <table border='1'>
        <tr><td>工号</td>
            <td>姓名</td>
            <td>签到时间</td>
            <td>签退时间</td>
            <td>迟到次数</td>
            <td>早退次数</td>
            <td>旷工次数</td>
            <td>请假次数</td>
            <td>考勤状态</td>
            <td>修改状态</td>
            <td>确定修改</td>
            <td>调整次数</td>
        </tr>

        <?php
        foreach($rows as $row){
            if($row['is_late']){$err_message="迟到";}
            elseif($row['is_quit']){$err_message="早退";}
            elseif($row['is_leave']){$err_message="请假";}
            elseif($row['is_away']){$err_message="旷工";}
            elseif($row['is_in']&&!$row['is_late']&&!$row['is_quit']&&!$row['is_leave']&&!$row['is_away']){$err_message="正常";}
            else{$err_message="";}
        ?>
        <form action="change_type1.php" method="post">
        <tr><td id="num"><?=$row['num']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['Intime']?></td>
            <td><?=$row['Outtime']?></td>
            <td><?=$row['Late']?></td>
            <td><?=$row['Quit']?></td>
            <td><?=$row['Away']?></td>
            <td><?=$row['Leaves']?></td>
            <td><?=$err_message?></td>
            <td><select name="<?=$row['num']?>">
                    <option value='1'>正常</option>
                    <option value='2'>迟到</option>
                    <option value='3'>早退</option>
                    <option value='4'>请假</option>
                    <option value='5'>旷工</option>
                </select></td>
            <td><button type='submit'>确认修改</button></td>
            <td><a href="gaici.php?num=<?=$row['num']?>">调整次数</a></td>
        </tr></form><?php } ?>

        <?php
            foreach($rows2 as $row2){
                if(!is_in($row2['num'])){
                    ?>
        <form action="change_type2.php" method="post">
                    <tr>
                        <td><?=$row2['num']?></td>
                        <td><?=$row2['name']?></td>
                        <td>未签到</td>
                        <td>未签退</td>
                        <td><?=$row2['Late']?></td>
                        <td><?=$row2['Quit']?></td>
                        <td><?=$row2['Away']?></td>
                        <td><?=$row2['Leaves']?></td>
                        <td><?=$err_message2?></td>
                        <td>
                            <select name="<?=$row2['num']?>">
                                <option value='4'>请假</option>
                                <option value='5'>旷工</option>
                            </select>
                        </td>
                        <td><button type='submit'>确认修改</button></td>
                        <td><a href="gaici.php?num=<?=$row2['num']?>">调整次数</a></td>
                    </tr>
        </form><?php }}?>
    </table>
    <input type="button" name="manage1"  value="考勤查询" onclick=window.location="admin_kq_select.php" >
    <input type="button" name="manage2"  value="考勤排行榜" onclick=window.location="admin_kq_row.php" >
    <form action="" method="post">
        <select name="month">
            <option value="1">1月</option>
            <option value="2">2月</option>
            <option value="3">3月</option>
            <option value="4">4月</option>
            <option value="5">5月</option>
            <option value="6">6月</option>
            <option value="7">7月</option>
            <option value="8">8月</option>
            <option value="9">9月</option>
            <option value="10">10月</option>
            <option value="11">11月</option>
            <option value="12">12月</option>
        </select>
        <button type="submit" >生成考勤情况图</button>
    </form>


<?php

require_once "../db/user.func.php";



if(isset($_POST['month'])){
    $month=$_POST['month'];
    setlate($month);


    echo "<img src='make_report.php'>";
    $rows=getpicture();
    foreach($rows as $row){
        echo"</br>研发部出勤率：{$row['dept1']}%";
        echo"</br>市场部出勤率：{$row['dept2']}%";
        echo"</br>售后部出勤率：{$row['dept3']}%";
    }
}

?>



</body>
</html>


