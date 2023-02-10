<?php
if(isset($_GET['id'])):

    require './db_connection.php';
    
    $userID = trim(mysqli_real_escape_string(
        $connection, 
        $_GET['id']
    ));

    if(empty($userID) || !is_numeric($userID)){
        header("Location: read.php");
        exit;
    }

    $query = mysqli_query(
                $connection,
                "DELETE FROM `users` WHERE `id`='$userID'"
            );
    
endif;
header("Location: read.php");
exit;