<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>排行</title>
</head>
<body>

查询员工早到晚归前三名：
<form action="" method="post">
    从
    <input type="date" name="from_time" required="required"/>
    到
    <input type="date" name="to_time" required="required"/>
    类型：
    <select name="type">
        <option value="1">早到</option>
        <option value="2">晚归</option>
    </select>
    </br>
    <button type="submit">确定</button>
</form>

<?php
require_once "../db/user.func.php";
if(isset($_POST['from_time'])) {
    $f_time=$_POST['from_time'];
    $t_time=$_POST['to_time'];
    $type=$_POST['type'];
    $i=0;

    if($type==1){
        $rows1 = paihang1($f_time,$t_time);
        echo"
    <table>
        <tr>早到前三名</tr>
        <tr>
             <td>名次</td><td>工号</td><td>姓名</td><td>所在部门</td><td>早到次数</td><td>早到记录</td>
        </tr>
    ";
        foreach ($rows1 as $row1) {
            $i=$i+1;
            echo "
        <tr>
           <td>第{$i}名</td>
           <td>{$row1['num']}</td>
           <td>{$row1['name']}</td>
           <td>{$row1['names']}</td>
           <td>{$row1['count(t.Intime)']}</td>
           <td>如下</td>
        </tr>
    ";
            $rows2 = zd_xx($row1['num'],$f_time,$t_time);
            foreach ($rows2 as $row2) {
                echo "
                <tr><td colspan=5></td><td>
                      {$row2['Intime']}
                </td></tr>

                ";
            }
        }
        $i=0;
        echo"</table>";
    }

    if($type==2){
        $rows1 = paihang2($f_time,$t_time);
        echo"
    <table>
    <tr>晚归前三名</tr>
        <tr>
             <td>名次</td><td>工号</td><td>姓名</td><td>所在部门</td><td>晚归次数</td><td>晚归记录</td>
        </tr>
    ";
        foreach ($rows1 as $row1) {
            $i=$i+1;
            echo "
        <tr>
           <td>第{$i}名</td>
           <td>{$row1['num']}</td>
           <td>{$row1['name']}</td>
           <td>{$row1['names']}</td>
           <td>{$row1['count(t.Outtime)']}</td>
           <td>如下</td>
        </tr>
    ";
            $rows2 = wg_xx($row1['num'],$f_time,$t_time);
            foreach ($rows2 as $row2) {
                echo "
                <tr><td colspan=5></td><td>
                      {$row2['Outtime']}
                </td></tr>

                ";
            }
        }
        $i=0;
        echo"</table>";
    }

}


?>

</body>
</html>