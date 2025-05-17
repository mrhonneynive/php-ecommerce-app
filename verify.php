<?php
session_start();

$err = null;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $code = trim($_POST["code"]);

    if(isset($_SESSION["verification_code"]) && $code == $_SESSION["verification_code"]){
        require "./db.php";

        $user = $_SESSION["pending_user"];

        $stmt = $db->prepare("insert into users(email, password, name, city_id, district_id, role)
        values (?,?,?,?,?,?)");
        $stmt->execute([
            $user["email"],
            $user["password"],
            $user["names"],
            $user["city_id"],
            $user["district_id"],
            $user["user"]
        ]);

        unset($_SESSION["verification_code"]);
        unset($_SESSION["pending_user"]);
        
        header("Location: login.php");
        exit;

    } else {
        // Kod yanlış, yeni kod gönder
        header("Location: verification.php?error=wrong_code");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .main {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        input[type="text"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        p.error {
            margin-top: 20px;
            color: #e74c3c;
            font-weight: bold;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="main">
        <?php if(isset($_GET["error"]) && $_GET["error"] === "wrong_code"): ?>
            <p class="error">Verification code is incorrect. A new code has been sent to your email. Please check and try again.</p>
        <?php elseif(isset($_SESSION["mail_error"])): ?>
            <p class="error"><?= htmlspecialchars($_SESSION["mail_error"]) ?></p>
            <?php unset($_SESSION["mail_error"]); ?>
        <?php endif; ?>

        <form action="" method="post">
            <input type="text" name="code" required placeholder="Enter verification code">
            <button>Send</button>
        </form>
    </div>
</body>
</html>
