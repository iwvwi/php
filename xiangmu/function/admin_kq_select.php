<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>考勤查询</title>
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>
条件查询：
<form action="" method="post">
    时间：
    <input id="type_all" type="radio" value="1" name="type1" checked>全部
    <input id="type_some" type="radio" value="2" name="type1">自定义
    <div id="add1"></div>
    部门：
    <select name="dept">
        <option value="1">研发部</option>
        <option value="2">市场部</option>
        <option value="3">售后部</option>
        <option value="4" selected = "selected" >全部</option>
    </select></br>
    特殊条件：
    <input id="te_no" type="radio" value="1" name="type2" checked>无
    <input id="te_yes" type="radio" value="2" name="type2">有
    <div id="add2"></div>

    <button type="submit" >确定</button>
</form>

<?php
require_once "../db/user.func.php";
if(isset($_POST['type1'])){
    $type1=$_POST['type1'];
    $type2=$_POST['type2'];
    $dept=$_POST['dept'];
    if($type1==1&&$type2==1){
        if($dept==4){ $rows = select_kq1();}
        else{ $rows = getUser4($dept);}
        echo"
    <table>
        <tr>
             <td>工号</td><td>姓名</td><td>迟到次数</td><td>早退次数</td><td>事假次数</td><td>旷工次数</td><td>所在部门</td>
        </tr>
    ";
        foreach ($rows as $row) {
            echo "
        <tr>
           <td>{$row['num']}</td>
           <td>{$row['name']}</td>
           <td>{$row['Late']}</td>
           <td>{$row['Quit']}</td>
           <td>{$row['Leaves']}</td>
           <td>{$row['Away']}</td>
           <td>{$row['names']}</td>
        </tr>
    ";
        }
        echo"</table>";
    }

    if($type1==2&&$type2==1){
        $f_time=$_POST['from_time'];
        $t_time=$_POST['to_time'];
        if($dept==4){ $rows = getUser5($f_time,$t_time);}
        else{ $rows = select_kq3($f_time,$t_time,$dept);}
        echo"
    <table>
        <tr>
             <td>工号</td><td>姓名</td><td>迟到次数</td><td>早退次数</td><td>事假次数</td><td>旷工次数</td><td>所在部门</td>
        </tr>
    ";
        foreach ($rows as $row) {
            echo "
        <tr>
           <td>{$row['num']}</td>
           <td>{$row['name']}</td>
           <td>{$row['count(t.is_late)']}</td>
           <td>{$row['count(t.is_quit)']}</td>
           <td>{$row['count(t.is_leave)']}</td>
           <td>{$row['count(t.is_away)']}</td>
           <td>{$row['names']}</td>
        </tr>
    ";
        }
        echo"</table>";
    }
    $rows=array();

    if($type1==1&&$type2==2){
        $kq_type=$_POST['kq_type'];
        $times=$_POST['times'];
        if($dept==4&&$kq_type==1){ $rows = select_kq4_1($times);}
        if($dept==4&&$kq_type==2){ $rows = select_kq4_2($times);}
        if($dept==4&&$kq_type==3){ $rows = select_kq4_3($times);}
        if($dept==4&&$kq_type==4){ $rows = select_kq4_4($times);}
        if($dept!=4&&$kq_type==1){ $rows = select_kq5_1($dept,$times);}
        if($dept!=4&&$kq_type==2){ $rows = select_kq5_2($dept,$times);}
        if($dept!=4&&$kq_type==3){ $rows = select_kq5_3($dept,$times);}
        if($dept!=4&&$kq_type==4){ $rows = select_kq5_4($dept,$times);}
        echo"
    <table>
        <tr>
             <td>工号</td><td>姓名</td><td>迟到次数</td><td>早退次数</td><td>事假次数</td><td>旷工次数</td><td>所在部门</td>
        </tr>
    ";
        foreach ($rows as $row) {
            echo "
        <tr>
           <td>{$row['num']}</td>
           <td>{$row['name']}</td>
           <td>{$row['Late']}</td>
           <td>{$row['Quit']}</td>
           <td>{$row['Leaves']}</td>
           <td>{$row['Away']}</td>
           <td>{$row['names']}</td>
        </tr>
    ";
        }
        echo"</table>";
    }

    if($type1==2&&$type2==2){
        $f_time=$_POST['from_time'];
        $t_time=$_POST['to_time'];
        $kq_type=$_POST['kq_type'];
        $times=$_POST['times'];
        if($dept==4&&$kq_type==1){ $rows = select_kq6_1($f_time,$t_time,$times);}
        if($dept==4&&$kq_type==2){ $rows = select_kq6_2($f_time,$t_time,$times);}
        if($dept==4&&$kq_type==3){ $rows = select_kq6_3($f_time,$t_time,$times);;}
        if($dept==4&&$kq_type==4){ $rows = select_kq6_4($f_time,$t_time,$times);}
        if($dept!=4&&$kq_type==1){ $rows = select_kq7_1($f_time,$t_time,$dept,$times);}
        if($dept!=4&&$kq_type==2){ $rows = select_kq7_2($f_time,$t_time,$dept,$times);}
        if($dept!=4&&$kq_type==3){ $rows = select_kq7_3($f_time,$t_time,$dept,$times);}
        if($dept!=4&&$kq_type==4){ $rows = select_kq7_4($f_time,$t_time,$dept,$times);}
        echo"
    <table>
        <tr>
             <td>工号</td><td>姓名</td><td>迟到次数</td><td>早退次数</td><td>事假次数</td><td>旷工次数</td><td>所在部门</td>
        </tr>
    ";
        foreach ($rows as $row) {
            echo "
        <tr>
           <td>{$row['num']}</td>
           <td>{$row['name']}</td>
           <td>{$row['count(t.is_late)']}</td>
           <td>{$row['count(t.is_quit)']}</td>
           <td>{$row['count(t.is_leave)']}</td>
           <td>{$row['count(t.is_away)']}</td>
           <td>{$row['names']}</td>
        </tr>
    ";
        }
        echo"</table>";
    }








}

?>

</body>
</html>

<script>
    $(document).ready(function(){
        $("#type_some").click(function () {
            if($('#add1').html()==""){
                $('#add1').prepend('从<input type="date" name="from_time" required="required"/>到<input type="date" name="to_time" required="required"/>');
            }
        });
        $("#type_all").click(function () {
            if(this.checked){
                $('#add1').empty();
            }
        });

        $("#te_yes").click(function () {
            if($('#add2').html()==""){
                $('#add2').prepend('<select name="kq_type"><option value="1">迟到</option><option value="2">早退</option><option value="3">请假</option><option value="4">旷工</option></select><input type="text" name="times" placeholder="几次以上" required="required"/>');
            }
        });
        $("#te_no").click(function () {
            if(this.checked){
                $('#add2').empty();
            }
        });
    });
</script>

