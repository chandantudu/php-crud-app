<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'crud';

// DSN(Database Source Name)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";

try{
   $connection = new PDO($dsn, $db_user, $db_password);
   // SET THE PDO ERROR MODE TO EXCEPTION
   $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
   echo "Connection failed - ".$e->getMessage();
}