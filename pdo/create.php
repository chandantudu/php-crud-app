<?php
function response(int $success, string $message) : array {
    return ["success" => $success, "msg" => $message];
}

function insertData(string $user_name, string $user_email) : array{
    require './db_connection.php';
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
      $check_email = $connection->prepare("SELECT `email` FROM `users` WHERE `email`=?");
      $check_email->bindParam(1, $email, PDO::PARAM_STR);
      $check_email->execute();
      if($check_email->rowCount() !== 0){
          return response(0, "This email is already registered. Please try another.");
      }

      $query = $connection->prepare("INSERT INTO `users`(`name`,`email`) VALUES(:name,:email)");
      $query->bindParam(':name', $name, PDO::PARAM_STR);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->execute();
      return response(1,"User has been successfully inserted.");
    }
    catch (PDOException $e){
      return response(0,"{$e->getMessage()}: Line number: {$e->getLine()}");
    }
}

if(isset($_POST['name']) && isset($_POST['email'])):
    $result = insertData($_POST['name'], $_POST['email']); 
    if($result['success']){
        $success = $result['msg'];
    }
    else{
        $error = $result['msg'];
    }
endif;
?>
<!DOCTYPE html>
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
        <h1>Create Data</h1>
        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Your name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" placeholder="Your email" required>
            </div>
            <?php if(isset($success)){?><p class="success-msg"><?= $success ?></p><?php } ?>
            <?php if(isset($error)){?><p class="err-msg"><?= $error ?></p><?php } ?>
            <div>
                <button type="submit">Insert</button>
            </div>
        </form>
        <ul class="nav-links">
            <?php include_once('./nav-links.php'); ?>
        </ul>
    </main>
<script>
    if(window.history.replaceState){
        window.history.replapceState(null, null, window.location.href);
    }
</script>
</body>
</html>