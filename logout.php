<?php
    require "./protect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        session_start();
        session_unset();
        session_destroy();

        setcookie("remember_token", "", time() - 3600, "/"); 

        header("Location: login.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <div class="container">
        <h1>Are you sure?</h1>
        <form action="" method="post">
            <button class="button">Logout</button>
        </form>
    </div>
</body>
</html>