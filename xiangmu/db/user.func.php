<?php

require_once "dbconn.php";

//检查用户和密码是否匹配
function validUser($username,$password){
    $db=getDb();
    $stmt=$db->prepare("select count(*) from user where num=:username and password=:password");
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':password',$password);
    $stmt->execute();
    return ($stmt->fetchColumn()==1);
}

//获得用户权限
function getUserGroup($username){
    $db=getDb();
    $stmt=$db->prepare("select groupid from user where num=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    return $stmt->fetchColumn();
}

//获得指定用户信息
function getUser($username){
    $db=getDb();
    $stmt=$db->prepare("select * from user,dept d where num=:username&&d.id=deptid");
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得所有用户当天信息
function getUser2(){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,time t where u.num=t.num&&to_days(Intime)=to_days(now())");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得所有用户信息
function getUser3(){
    $db=getDb();
    $stmt=$db->prepare("select * from user");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得指定部门用户信息
function getUser4($dept){
    $db=getDb();
    $stmt=$db->prepare("select * from user,dept d where d.id=deptid&&deptid=:dept");
    $stmt->bindParam(':dept',$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得指定时间用户信息
function getUser5($f_time,$t_time){
    $db=getDb();
    $stmt=$db->prepare("
select t.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_away),count(t.is_leave),d.names from time t,user u,dept d where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?) and t.num=u.num and d.id=u.deptid group by u.name;
");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//插入签到数据
function in_time($num){
    $db=getDb();
    $stmt=$db->prepare("insert into time(num,Intime,is_in) values(:num,now(),1)");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//插入签退数据
function out_time($num){
    $db=getDb();
    $stmt=$db->prepare("update time set Outtime=now() where to_days(Intime)=to_days(now()) && num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得用户下班时间
function get_outTime($username){
    $db=getDb();
    $stmt=$db->prepare("select End from user where num=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    return $stmt->fetchColumn();
}

//判断是否已经签到
function is_in($username){
    $db=getDb();
    $stmt=$db->prepare("select count(*) from time where to_days(Intime)=to_days(now()) && num=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    return ($stmt->fetchColumn()==1);
}

//判断是否已经签退
function is_out($username){
    $db=getDb();
    $stmt=$db->prepare("select count(*) from time where to_days(Outtime)=to_days(now()) && num=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();
    return ($stmt->fetchColumn()==1);
}

//判断是否已经签退
function is_same($num){
    $db=getDb();
    $stmt=$db->prepare("select * from user where  num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return ($stmt->fetchColumn()==1);
}

//修改密码
function gai_mi($num,$pwd){
    $db=getDb();
    $stmt=$db->prepare("update user set password=:password where num=:num");
    $stmt->bindParam(':password',$pwd);
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改时间
function gai_time($num,$time1,$time2){
    $db=getDb();
    $stmt=$db->prepare("update user set Begin=?,End=? where num=?");
    $stmt->bindParam(1,$time1);
    $stmt->bindParam(2,$time2);
    $stmt->bindParam(3,$num);
    $stmt->execute();
    return ($stmt->rowCount()==1);
}

//添加员工
function addworker($num,$name,$pwd,$up,$down,$dept){
    $db=getDb();
    $stmt=$db->prepare("insert into user(num,name,password,Begin,End,deptid,groupid) values(?,?,?,?,?,?,1)");
    $stmt->bindParam(1,$num);
    $stmt->bindParam(2,$name);
    $stmt->bindParam(3,$pwd);
    $stmt->bindParam(4,$up);
    $stmt->bindParam(5,$down);
    $stmt->bindParam(6,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//为旷工或请假者添加记录
function checkla($num)
{
    $db=getDb();
    $stmt=$db->prepare("insert into time(num,Intime,is_late,is_quit,is_leave,is_away,is_in)VALUES (?,now(),0,0,0,0,0)");
    $stmt->bindParam(1,$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改考勤状态
function checkstatus($num,$value){
    $one=1;
    $zero=NULL;
    $db=getDb();
    $stmt=$db->prepare("update time set is_late=?,is_quit=?,is_leave=?,is_away=? where num=?");
    if($value==1)
    {   $stmt->bindParam(1,$zero);
        $stmt->bindParam(2,$zero);
        $stmt->bindParam(3,$zero);
        $stmt->bindParam(4,$zero);
        $stmt->bindParam(5,$num);}
    elseif($value==2)
    {   $stmt->bindParam(1,$one);
        $stmt->bindParam(2,$zero);
        $stmt->bindParam(3,$zero);
        $stmt->bindParam(4,$zero);
        $stmt->bindParam(5,$num);}
    elseif($value==3)
    {   $stmt->bindParam(1,$zero);
        $stmt->bindParam(2,$one);
        $stmt->bindParam(3,$zero);
        $stmt->bindParam(4,$zero);
        $stmt->bindParam(5,$num);}
    elseif($value==4)
    {   $stmt->bindParam(1,$zero);
        $stmt->bindParam(2,$zero);
        $stmt->bindParam(3,$one);
        $stmt->bindParam(4,$zero);
        $stmt->bindParam(5,$num);}
    elseif($value==5)
    {   $stmt->bindParam(1,$zero);
        $stmt->bindParam(2,$zero);
        $stmt->bindParam(3,$zero);
        $stmt->bindParam(4,$one);
        $stmt->bindParam(5,$num);}
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//添加迟到
function add_late($num){
    $db=getDb();
    $stmt=$db->prepare("update user set Late=Late+1 where num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//添加早退
function add_quit($num){
    $db=getDb();
    $stmt=$db->prepare("update user set Quit=user.Quit+1 where num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//添加请假
function add_leave($num){
    $db=getDb();
    $stmt=$db->prepare("update user set Leaves=Leaves+1 where num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//添加旷工
function add_away($num){
    $db=getDb();
    $stmt=$db->prepare("update user set Away=Away+1 where num=:num");
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改迟到次数
function gai_late($num,$times){
    $db=getDb();
    $stmt=$db->prepare("update user set Late=:times where num=:num");
    $stmt->bindParam(':times',$times);
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改早退次数
function gai_quit($num,$times){
    $db=getDb();
    $stmt=$db->prepare("update user set Quit=:times where num=:num");
    $stmt->bindParam(':times',$times);
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改请假次数
function gai_leave($num,$times){
    $db=getDb();
    $stmt=$db->prepare("update user set Leaves=:times where num=:num");
    $stmt->bindParam(':times',$times);
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改旷工次数
function gai_away($num,$times){
    $db=getDb();
    $stmt=$db->prepare("update user set Away=:times where num=:num");
    $stmt->bindParam(':times',$times);
    $stmt->bindParam(':num',$num);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得迟到用户信息
function get_late(){
    $db=getDb();
    $stmt=$db->prepare("select name,lates from user,yuzhi where Late>=lates");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得早退用户信息
function get_quit(){
    $db=getDb();
    $stmt=$db->prepare("select name,quits from user,yuzhi where Quit>=quits");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得请假用户信息
function get_leave(){
    $db=getDb();
    $stmt=$db->prepare("select name,leavess from user,yuzhi where Leaves>=leavess");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得旷工用户信息
function get_away(){
    $db=getDb();
    $stmt=$db->prepare("select name,aways from user,yuzhi where Away>=aways");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改阈值
function gai_yuzhi($late,$quit,$leave,$away){
    $db=getDb();
    $stmt=$db->prepare("update yuzhi set lates=?,quits=?,leavess=?,aways=?");
    $stmt->bindParam(1,$late);
    $stmt->bindParam(2,$quit);
    $stmt->bindParam(3,$leave);
    $stmt->bindParam(4,$away);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得早到用户排行
function paihang1($f_time,$t_time){
    $db=getDb();
    $stmt=$db->prepare("
select u.num,u.name,count(t.Intime),names from user u,dept d,time t where CAST(t.Intime AS time)<'12:30:00' and t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?) and u.deptid=d.id and t.num=u.num group by u.num order by count(t.Intime) DESC limit 3;
");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得晚归用户排行
function paihang2($f_time,$t_time){
    $db=getDb();
    $stmt=$db->prepare("
select u.num,u.name,count(t.Outtime) ,names from user u,dept d,time t where CAST(t.Outtime AS time)>'20:30:00' and t.Outtime>=? and TO_DAYS(t.Outtime)<=TO_DAYS(?) and u.deptid=d.id and t.num=u.num group by u.num order by count(t.Outtime) DESC limit 3;
");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得早到用户信息
function zd_xx($num,$f_time,$t_time){
    $db=getDb();
    $stmt=$db->prepare("
select Intime from time where num=?and CAST(Intime AS time) <'12:30:00' and Intime>=? and TO_DAYS(Intime)<=TO_DAYS(?);
");
    $stmt->bindParam(1,$num);
    $stmt->bindParam(2,$f_time);
    $stmt->bindParam(3,$t_time);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得晚归用户信息
function wg_xx($num,$f_time,$t_time){
    $db=getDb();
    $stmt=$db->prepare("
select Outtime from time where num=? and CAST(Outtime AS time)>'20:30:00' and Outtime>=? and TO_DAYS(Outtime)<=TO_DAYS(?);
");
    $stmt->bindParam(1,$num);
    $stmt->bindParam(2,$f_time);
    $stmt->bindParam(3,$t_time);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门的考勤情况
function select_kq1(){
    $db=getDb();
    $stmt=$db->prepare("select * from user,dept d where d.id=deptid");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查带时间的某个部门考勤情况
function select_kq3($f_time,$t_time,$dept){
    $db=getDb();
    $stmt=$db->prepare("
select t.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_away),count(t.is_leave),d.names from time t,user u,dept d where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?) and t.num=u.num and d.id=u.deptid and u.deptid=? group by u.name;
");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门迟到情况x次以上的考勤情况
function select_kq4_1($times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Late>? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门早退情况x次以上的考勤情况
function select_kq4_2($times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Quit>? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门请假情况x次以上的考勤情况
function select_kq4_3($times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Leaves>? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门旷工情况x次以上的考勤情况
function select_kq4_4($times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Away>? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门迟到情况x次以上的考勤情况
function select_kq5_1($dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Late>? and d.id=? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->bindParam(2,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门早退情况x次以上的考勤情况
function select_kq5_2($dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Quit>? and d.id=? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->bindParam(2,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门请假情况x次以上的考勤情况
function select_kq5_3($dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Leaves>? and d.id=? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->bindParam(2,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门旷工情况x次以上的考勤情况
function select_kq5_4($dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select * from user u,dept d where u.Away>? and d.id=? and u.deptid=d.id group by u.num;");
    $stmt->bindParam(1,$times);
    $stmt->bindParam(2,$dept);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门迟到情况x次以上的考勤情况
function select_kq6_1($f_time,$t_time,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=d.id and u.num=t.num group by u.num HAVING count(t.is_late)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门早退情况x次以上的考勤情况
function select_kq6_2($f_time,$t_time,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=d.id and u.num=t.num group by u.num HAVING count(t.is_quit)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门请假情况x次以上的考勤情况
function select_kq6_3($f_time,$t_time,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=d.id and u.num=t.num group by u.num HAVING count(t.is_leave)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查所有部门旷工情况x次以上的考勤情况
function select_kq6_4($f_time,$t_time,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=d.id and u.num=t.num group by u.num HAVING count(t.is_away)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门迟到情况x次以上的考勤情况
function select_kq7_1($f_time,$t_time,$dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=? and u.num=t.num group by u.num HAVING count(t.is_late)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$dept);
    $stmt->bindParam(4,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门早退情况x次以上的考勤情况
function select_kq7_2($f_time,$t_time,$dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=? and u.num=t.num group by u.num HAVING count(t.is_quit)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$dept);
    $stmt->bindParam(4,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门请假情况x次以上的考勤情况
function select_kq7_3($f_time,$t_time,$dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=? and u.num=t.num group by u.num HAVING count(t.is_leave)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$dept);
    $stmt->bindParam(4,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//查某个部门旷工情况x次以上的考勤情况
function select_kq7_4($f_time,$t_time,$dept,$times){
    $db=getDb();
    $stmt=$db->prepare("select u.num,u.name,count(t.is_late),count(t.is_quit),count(t.is_leave),count(t.is_away),d.names from
user u,dept d,time t where t.Intime>=? and TO_DAYS(t.Intime)<=TO_DAYS(?)  and u.deptid=? and u.num=t.num group by u.num HAVING count(t.is_away)>?;");
    $stmt->bindParam(1,$f_time);
    $stmt->bindParam(2,$t_time);
    $stmt->bindParam(3,$dept);
    $stmt->bindParam(4,$times);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//获得报表数据
function getpicture(){
    $db=getDb();
    $stmt=$db->prepare("select * from picture");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//修改指定月份的出勤率
function setlate($month){
    $db=getDb();
    $stmt=$db->prepare("update picture set
dept1=(select ((count(t.Intime)-count(t.is_quit)-count(t.is_leave))/count(t.Intime))*100 from dept d,time t,user u where MONTH(t.Intime)=? and left(t.Intime,4)=2015 and t.num=u.num and u.deptid=d.id and d.names='研发部' group by d.names),
dept2=(select ((count(t.Intime)-count(t.is_quit)-count(t.is_leave))/count(t.Intime))*100 from dept d,time t,user u where MONTH(t.Intime)=? and left(t.Intime,4)=2015 and t.num=u.num and u.deptid=d.id and d.names='市场部' group by d.names),
dept3=(select ((count(t.Intime)-count(t.is_quit)-count(t.is_leave))/count(t.Intime))*100 from dept d,time t,user u where MONTH(t.Intime)=? and left(t.Intime,4)=2015 and t.num=u.num and u.deptid=d.id and d.names='售后部' group by d.names);");
    $stmt->bindParam(1,$month);
    $stmt->bindParam(2,$month);
    $stmt->bindParam(3,$month);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}