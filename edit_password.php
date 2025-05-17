<?php
session_start();
require "./db.php";

if (isset($_SESSION["user_id"])) {
    require "./protect.php";
    $user = [
        "type" => "id",
        "value" => $_SESSION["user_id"]
    ];
} elseif (isset($_SESSION["reset_email_verified"])) {
    $user = [
        "type" => "email",
        "value" => $_SESSION["reset_email_verified"]
    ];
} else {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newpassword = trim($_POST["newpassword"] ?? "");
    $confirm = trim($_POST["confirm"] ?? "");

    if ($newpassword === $confirm && strlen($newpassword) > 6) {
        $hashed = password_hash($newpassword, PASSWORD_BCRYPT);

        if ($user["type"] == "id") {
            $stmt = $db->prepare("update users set password = ? where id = ?");
        } elseif ($user["type"] == "email") {
            $stmt = $db->prepare("update users set password = ? where email = ?");
        }

        $stmt->execute([$hashed, $user["value"]]);

        if ($user["type"] == "email") {
            unset($_SESSION["reset_email_verified"]);
        }

        $success = "Password updated successfully";
    } else {
        $error = "Passwords do not match or password is too short (minimum 7 characters)!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Password</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        padding: 30px;
        display: flex;
        justify-content: center;
    }
    .main {
        background: white;
        padding: 25px 30px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        width: 350px;
        text-align: center;
    }
    h1 {
        margin-bottom: 20px;
        color: #333;
        font-weight: 600;
    }
    table {
        width: 100%;
        margin-bottom: 15px;
    }
    td {
        padding: 8px 5px;
        font-size: 14px;
        color: #444;
    }
    input[type="password"] {
        width: 100%;
        padding: 8px 10px;
        font-size: 15px;
        border: 1.8px solid #ccc;
        border-radius: 5px;
        transition: border-color 0.3s ease;
    }
    input[type="password"]:focus {
        border-color: #4CAF50;
        outline: none;
    }
    .button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 0;
        width: 100%;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .button:hover {
        background-color: #45a049;
    }
    .err {
        color: red;
        font-weight: bold;
        margin-top: 15px;
    }
    .success {
        color: green;
        font-weight: bold;
        margin-top: 15px;
    }

.link {
    position: absolute;
    top: 20px;
    left: 30px;
    color: #3498db;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.link:hover {
    color: #1d6fa5;
}
</style>
</head>
<body>
<a href="editinfo.php" class="link">&#8592; Back</a>
<div class="main">
    <h1>This Page For Updating Your Password</h1>
    <form action="" method="post">
        <table>
            <tr>
                <td>NEW password:</td>
                <td><input type="password" name="newpassword" required></td>
            </tr>
            <tr>
                <td>Re-enter your NEW password:</td>
                <td><input type="password" name="confirm" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button class="button" type="submit">Update</button>
                </td>
            </tr>
        </table>
    </form>
    <?php if (isset($error)) : ?>
        <p class="err"><?=htmlspecialchars($error)?></p>
    <?php elseif (isset($success)) : ?>
        <p class="success"><?=htmlspecialchars($success)?></p>
    <?php endif ?>
</div>
</body>
</html>
