<?php

session_start();

require_once "../db/user.func.php";

$user=$_SESSION['username'];

in_time($user);

header("location: ../user_detail.php");