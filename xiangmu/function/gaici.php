<?php
require_once "../db/user.func.php";
$num=$_GET['num'];
$rows=getUser($num);

if(isset($_POST['late_times'])){
    $times=$_POST['late_times'];
    gai_late($num,$times);
    header("location: admin_kq.php");
}
if(isset($_POST['quit_times'])){
    $times=$_POST['quit_times'];
    gai_quit($num,$times);
    header("location: admin_kq.php");
}
if(isset($_POST['leave_times'])){
    $times=$_POST['leave_times'];
    gai_leave($num,$times);
    header("location: admin_kq.php");
}
if(isset($_POST['away_times'])){
    $times=$_POST['away_times'];
    gai_away($num,$times);
    header("location: admin_kq.php");
}
?>
<table>
    <tr><td>工号</td><td>姓名</td><td>迟到次数</td><td>早退次数</td><td>旷工次数</td><td>请假次数</td></tr>

    <?php
        foreach($rows as $row){
    ?>
            <tr>
                <td><?=$row['num']?></td>
                <td><?=$row['name']?></td>
                <td><?=$row['Late']?></td>
                <td><?=$row['Quit']?></td>
                <td><?=$row['Away']?></td>
                <td><?=$row['Leaves']?></td>
            </tr>
    <?php }?>

</table>

<form action="" method="post">
    <input type="text" name="late_times" placeholder="迟到次数" required="required"/>
    <button type="submit">确认修改</button>
</form>
<form action="" method="post">
    <input type="text" name="quit_times" placeholder="早退次数" required="required"/>
    <button type="submit">确认修改</button>
</form>
<form action="" method="post">
    <input type="text" name="away_times" placeholder="旷工次数" required="required"/>
    <button type="submit">确认修改</button>
</form>
<form action="" method="post">
    <input type="text" name="leave_times" placeholder="请假次数" required="required"/>
    <button type="submit">确认修改</button>
</form>


