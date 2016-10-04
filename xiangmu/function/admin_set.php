<?php
session_start();
require_once "../db/user.func.php";
$grp=$_SESSION['usergroup'];
$num=$_SESSION['username'];
if($grp==2){
    $rows=getUser3($num);
    echo "<table><tr><td>工号</td><td>姓名</td><td>上班时间</td><td>下班时间</td><td>修改</td></tr>";
    foreach($rows as $row){
        echo "
    <tr>
        <td>{$row['num']}</td>
        <td>{$row['name']}</td>
        <td>{$row['Begin']}</td>
        <td>{$row['End']}</td>
        <td><a href='gaitime.php?num={$row['num']}'>修改</a></td>
    </tr>
    ";
    }
    echo "</table>
       <button type='submit' onclick=window.location='add_worker.php'>添加员工</button>
       <button type='submit' onclick=window.location='gaiyuzhi.php'>修改阈值</button>
       ";
}
else{
    header("location: ../login.php");
}