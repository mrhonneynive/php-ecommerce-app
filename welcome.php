<?php
session_start();
require "./db.php";


if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role'])) {
    if (isset($_COOKIE["remember_token"])) {
        $token = $_COOKIE["remember_token"];

        $stmt = $db->prepare("select tokens.user_id, users.role
                              from tokens
                              join users on tokens.user_id = users.id
                              where tokens.token = ?");
        $stmt->execute([$token]);
        $record = $stmt->fetch();

        if ($record) {
            $_SESSION["user_id"] = $record["user_id"];
            $_SESSION["role"] = $record["role"];

            header("Location: main.php");
            exit;
        }
    }
} else {
    header("Location: main.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome™</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e8f0fe;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .main {
            background: white;
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        h1 {
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .button {
            margin: 15px 0;
        }
        .button a {
            display: inline-block;
            padding: 12px 40px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .button a:hover {
            background-color: #45a049;
        }
        .button:last-child a {
            background-color: #2196F3;
        }
        .link-button:last-child a:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="main">
        <h1>Welcome™</h1>

        <div class="button">
            <a href="register.php">Register</a>
        </div>

        <br>

        <div class="button">
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
