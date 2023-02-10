<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "crud";

$connection = new mysqli($db_host,$db_user,$db_pass,$db_name);

if($connection->connect_errno){
    echo "Connection Failed".mysqli_connect_error();
    exit;
}