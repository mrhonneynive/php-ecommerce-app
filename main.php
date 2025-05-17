<?php
session_start();

// check if user is logged in
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .container {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    h1 {
        margin-bottom: 20px;
        color: #2c3e50;
    }

    a {
        text-decoration: none;
        font-size: 16px;
        display: inline-block;
        margin-top: 10px;
        color: #3498db;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #1d6fa5;
    }

    .logout-link {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #e74c3c;
        font-weight: bold;
        font-size: 16px;
    }

    .logout-link i {
        margin-right: 5px;
    }

    .icon-link {
        margin-top: 25px;
        display: inline-block;
        color: #2E7D32;
        font-weight: 600;
        transition: transform 0.2s ease;
    }

    .icon-link:hover {
        transform: translateY(-3px);
        color: #1b5e20;
    }

    .icon-link i {
        font-size: 40px;
        display: block;
        margin-bottom: 5px;
    }
</style>

</head>
<body>
        <!-- Logout -->
        <p><a class="logout-link" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></p>
    <div class="container">
        <h1>Welcome!</h1>

        <?php if ($_SESSION["role"] == "consumer"): ?>
            <a class="icon-link" href="buy_product.php">
                <i class="fa-solid fa-cart-shopping"></i>
                Buy Products
            </a>
        <?php endif; ?>


        <?php if ($_SESSION["role"] == "market") { ?>
            <p><a href="manage_products.php">Manage Products</a></p>
        <?php } ?>

        <?php if ($_SESSION["role"] == "market" || $_SESSION["role"] == "consumer") { ?>
            <a class="icon-link" href="editinfo.php">
                <i class="fa-solid fa-user-pen"></i>
                <?php echo ($_SESSION["role"] == "market") ? "Edit Market Info" : "Edit Your Info"; ?>
            </a>
        <?php } ?>
    </div>
</body>
</html>
