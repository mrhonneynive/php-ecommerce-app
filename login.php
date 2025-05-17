<?php
session_start();
require "./db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $remember = isset($_POST["remember"]);

    // Kullanıcı kontrolü
    $stmt = $db->prepare("select * from users where email = ?");
    $stmt->execute([$email]);
    $users = $stmt->fetch();

    if ($users && password_verify($password, $users["password"])) {
        $_SESSION["id"] = $users["id"];
        $_SESSION["role"] = $users["role"];

        if ($remember) {
            $cookie_token = bin2hex(random_bytes(32));
            $date_for_expire = date("Y-m-d", time() + 60 * 60 * 24 * 30); // MySQL format

            $stmt = $db->prepare("insert into tokens(user_id, token, expire_date) values (?, ?, ?)");
            $stmt->execute([$users["id"], $cookie_token, $date_for_expire]);

            setcookie("remember_token", $cookie_token, time() + 60 * 60 * 24 * 30, "/", "", false, true);
        }

        header("Location: main.php");
        exit;
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    form {
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    table {
        width: 100%;
    }

    td {
        padding: 10px 0;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
        margin-right: 5px;
    }

    button {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }

    .error {
        margin-top: 15px;
        color: #e74c3c;
        text-align: center;
        font-weight: bold;
    }
</style>

</head>
<body>
    <form action="" method="post">
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td>Remember Me:</td>
                <td><input type="checkbox" name="remember" value="1"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit">Login</button></td>
            </tr>
        </table>
    </form>

    <?php if (isset($error)) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</body>
</html>
