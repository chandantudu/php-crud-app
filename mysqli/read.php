<?php
require './db_connection.php';
$query = mysqli_query($connection, "SELECT * FROM `users`");
$allUsers = mysqli_fetch_all($query, MYSQLI_ASSOC);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD application</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main class="container">
        <h1>Read Data</h1>
        <section class="user-list">
            <?php if(count($allUsers) > 0):
                foreach($allUsers as $user): ?>
            <ul>
                <li>
                    <span class="list-wrap">
                        <span><?= $user['name'] ?><br><em><?= $user['email'] ?></em></span>
                        <span>
                            <a href="./update.php?id=<?= $user['id'] ?>">Edit</a> 
                            <a href="./delete.php?id=<?= $user['id'] ?>" class="del">Delete</a>
                        </span>
                    </span>
                </li>
            </ul>
            <?php
                endforeach;
            else: ?>
            <p>Please Insert Some Users! 😊</p>
            <?php endif; ?>
        </section>
        <ul class="nav-links">
            <?php include_once('./nav-links.php'); ?>
        </ul>
    </main>
    <script>
        const deleteBtns = document.querySelectorAll('a.del');
        function deleteUser(e){
            e.preventDefault();
            if (confirm('Are you sure?')) {
                window.location.href = e.target.href;
            }
        }
        deleteBtns.forEach((el) => {
            el.onclick = (e) => deleteUser(e);
        });
    </script>
</body>
</html>