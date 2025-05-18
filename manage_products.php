<?php
session_start();
require "./db.php";

// only market users can access
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "market") {
    header("Location: login.php");
    exit;
}

// make a csrf token
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// get all products for this market
$stmt = $db->prepare("SELECT * FROM products WHERE market_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        table { width: 100%; border: 1px solid black; }
        th, td { border: 1px solid black; padding: 8px; }
        th { background: #f0f0f0; }
        a { color: blue; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Products</h2>
        <p><a href="add_product.php">Add New Product</a></p>

        <?php if (empty($products)) { ?>
            <p>No products yet.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Stock</th>
                    <th>Normal Price</th>
                    <th>Discounted Price</th>
                    <th>Expiration Date</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php
                $today = date("Y-m-d");
                foreach ($products as $p) {
                    $isExpired = $p["expiration_date"] < $today;
                ?>
                <tr style="<?= $isExpired ? 'background-color:#ffe6e6;' : '' ?>">
                    <td>
                        <?= htmlspecialchars($p["title"]); ?>
                        <?php if ($isExpired): ?>
                            <span style="color:red;">(Expired)</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p["stock"]); ?></td>
                    <td><?= htmlspecialchars($p["normal_price"]); ?> TL</td>
                    <td><?= htmlspecialchars($p["discounted_price"]); ?> TL</td>
                    <td><?= htmlspecialchars($p["expiration_date"]); ?></td>
                    <td>
                        <?php if ($p["image_path"]) { ?>
                            <img src="<?= htmlspecialchars($p["image_path"]); ?>" width="50">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?= $p["id"]; ?>">Edit</a>
                        <a href="delete_product.php?id=<?= $p["id"]; ?>&csrf_token=<?= $_SESSION["csrf_token"]; ?>"
                        onclick="return confirm('Sure you want to delete?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</body>
</html>