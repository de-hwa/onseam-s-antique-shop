<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "db.onsaemiro.com";
$user = "sys_onsaem";
$pass = "anjsep8763!";
$db = "onsaem_db";

$conn = mysqli_connect($host, $user, $pass, $db);
?>