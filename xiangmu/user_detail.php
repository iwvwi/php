<?php
session_start();

?>

    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <title>用户信息页面</title>
        <script src="js/jquery-2.1.4.js"></script>
    </head>
<body>

<?php require_once "db/user.func.php"; ?>

<table border="1">
    <tr>
        <td>工号</td>
        <td>姓名</td>
        <td>部门</td>
        <td>迟到次数</td>
        <td>早退次数</td>
        <td>旷工次数</td>
        <td>请假次数</td>
    </tr>


    <?php
    $num=$_SESSION['username'];
    $grp=$_SESSION['usergroup'];

    if($grp==2){
        $dis="inline";
    }else{
        $dis="none";
    }


    $rows=getUser($num);
    foreach($rows as $row){
    ?>
    <tr>
        <td><?=$row['num']?></td>
        <td><?=$row['name']?></td>
        <td><?=$row['names']?></td>
        <td><?=$row['Late']?></td>
        <td><?=$row['Quit']?></td>
        <td><?=$row['Away']?></td>
        <td><?=$row['Leaves']?></td>
    </tr>
    <?php } ?>
</table>
<table>
    <tr>
        <td>
            <input type="button" name="in"  value="点我签到" onclick=window.location="function/qiandao.php">
            <div id="qd" style="display: none;float: left">已签到</div>
        </td>
        <td>
            <input type="button" name="out" value="点我签退" onclick=window.location="function/qiantui.php">
            <div id="qt" style="display: none;float: left">已签退</div>
            <div id="no_qt" style="display: none;float: left">未到签退时间</div>
        </td>
        <td>
            <input type="button" name="change" value="修改密码" onclick=window.location="function/gaimi.php">
        </td>
        <td>
            <input type="button" name="manage" style="display: <?=$dis?>" value="考勤管理" onclick=window.location="function/admin_kq.php" >
        </td>
        <td>
            <input type="button" name="setting" style="display: <?=$dis?>" value="系统设置" onclick=window.location="function/admin_set.php">
        </td>
    </tr>
</table>



<?php
     if($grp==2){
         $rows1=get_late();
         $rows2=get_quit();
         $rows3=get_leave();
         $rows4=get_away();
         $time=date('Y-m-d');
         echo"</br>";
         foreach($rows1 as $row1){
             echo"{$row1['name']}至{$time}迟到次数已达到{$row1['lates']}次，特此提醒！</br>";
         }
         foreach($rows2 as $row2){
             echo"{$row2['name']}至{$time}早退次数已达到{$row2['quits']}次，特此提醒！</br>";
         }
         foreach($rows3 as $row3){
             echo"{$row3['name']}至{$time}请假次数已达到{$row3['leavess']}次，特此提醒！</br>";
         }
         foreach($rows4 as $row4){
             echo"{$row4['name']}至{$time}旷工次数已达到{$row4['aways']}次，特此提醒！</br>";
         }

     }
?>






<script>
    $(document).ready(function(){
        $.getJSON("function/check_in_out.php",
            function(json){
                if(json.type==1){
                    $('input[name="in"]').show();
                    $('#no_qt').show();
                    $('input[name="out"]').hide();
                    $('#qd').hide();
                    $('#qt').hide();
                }
                else if(json.type==2){
                    $('input[name="in"]').show();
                    $('input[name="out"]').show();
                    $('#no_qt').hide();
                    $('#qd').hide();
                    $('#qt').hide();
                }
                else if(json.type==3){
                    $('#qd').show();
                    $('#no_qt').show();
                    $('input[name="in"]').hide();
                    $('input[name="out"]').hide();
                    $('#qt').hide();
                }
                else if(json.type==4){
                    $('#qd').show();
                    $('input[name="out"]').show();
                    $('#no_qt').hide();
                    $('input[name="in"]').hide();
                    $('#qt').hide();
                }
                else{
                    $('#qd').show();
                    $('#qt').show();
                    $('#no_qt').hide();
                    $('input[name="out"]').hide();
                    $('input[name="in"]').hide();
                }
            }
        );
    });

</script>





</body>
</html>