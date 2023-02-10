<?php
if(isset($_GET['id'])):

    require './db_connection.php';
    
    $userID = trim($_GET['id']);

    if(empty($userID) || !is_numeric($userID)){
        header("Location: read.php");
        exit;
    }

    $query = $connection->query("DELETE FROM `users` WHERE `id`='$userID'");
    
endif;
header("Location: read.php");
exit;