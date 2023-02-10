<?php
if(!isset($_GET['id'])){
    header("Location: read.php");
    exit;
}
require './db_connection.php';

function response(int $success, string $message) : array {
    return ["success" => $success, "msg" => $message];
}

function updateUser(PDO $connection, int $id, string $user_name, string $user_email) : array{
    $name = trim(htmlspecialchars($user_name));
    $email = trim(htmlspecialchars($user_email));

    if (empty($name) || empty($email)) {
        return response(0, "Please fill all required fields.");
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response(0, "Invalid email address.");
    }
    try{
        # Checking whether the email already exists in our database.
        $check_email = $connection->prepare("SELECT `email` FROM `users` WHERE `email` = ? and `id` != ?");
        $check_email->bindParam(1, $email, PDO::PARAM_STR);
        $check_email->bindParam(2, $id, PDO::PARAM_INT);
        $check_email->execute();
        if($check_email->rowCount() !== 0){
            return response(0, "This email is already registered. Please try another.");
        }

        # Updating the user
        $query = $connection->prepare("UPDATE `users` SET `name`=:name, `email`=:email WHERE `id`=:id");
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return response(1, "User has been successfully updated.");
    }
    catch (PDOException $e){
        return response(0,"{$e->getMessage()}: Line number: {$e->getLine()}");
    }
}

$userID = trim($_GET['id']);
if(empty($userID) || !is_numeric($userID)){
    header("Location: read.php");
    exit;
}

if(isset($_POST['name']) && isset($_POST['email'])):
    $result = updateUser($connection, $userID, $_POST['name'], $_POST['email']); 
    if($result['success']){
        $success = $result['msg'];
    }
    else{
        $error = $result['msg'];
    }
endif;

# Here you can use query instead of prepare
$query = $connection->prepare("SELECT * FROM `users` WHERE `id`=?");
$query->bindParam(1,$userID,PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if(is_null($user)){
    header("Location: read.php");
    exit;
}

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
        <h1>Update User</h1>
        <form action="<?= $_SERVER["PHP_SELF"]; ?>?id=<?= $userID ?>" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?= $user['name']; ?>" placeholder="Your name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?= $user['email']; ?>" placeholder="Your email" required>
            </div>
            <?php if(isset($success)){?><p class="success-msg"><?= $success ?></p><?php } ?>
            <?php if(isset($error)){?><p class="err-msg"><?= $error ?></p><?php } ?>
            <div>
                <button type="submit">Update</button>
            </div>
        </form>
        <ul class="nav-links">
            <?php include_once('./nav-links.php'); ?>
        </ul>
    </main>
    <script>
        if(window.history.replaceState){
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>