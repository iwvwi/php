<?php
session_start();

require_once "../db/user.func.php";

$user=$_SESSION['username'];

if(is_in($user)){
    $update=out_time($user);
    header("location: ../user_detail.php");
}
else{
    header("location: ../user_detail.php");
}






