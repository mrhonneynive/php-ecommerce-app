<?php
session_start();
require "./db.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "consumer") {
    header("Location: login.php");
    exit;
}

// Fetch all available products from DB
$stmt = $db->query("SELECT id, title, discounted_price FROM products WHERE stock > 0");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 800px;
            margin: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        input[type="number"] {
            width: 60px;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php if (isset($_GET["success"])): ?>
    <p style="color: green; text-align:center;">✅ Product added to cart successfully!</p>
<?php endif; ?>


<h2 style="text-align:center;">Available Products</h2>

<?php if (empty($products)) : ?>
    <p style="text-align:center;">No products available.</p>
<?php else : ?>
    <form method="POST" action="add_to_cart.php">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price (TL)</th>
                    <th>Quantity</th>
                    <th>Add</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
                  <tr>
                      <form method="POST" action="add_to_cart.php">
                          <td><?= htmlspecialchars($product["title"]) ?></td>
                          <td><?= number_format($product["discounted_price"], 2) ?></td>
                          <td>
                              <input type="number" name="quantity" value="1" min="1">
                          </td>
                          <td>
                              <input type="hidden" name="product_id" value="<?= $product["id"] ?>">
                              <button type="submit">Add to Cart</button>
                          </td>
                      </form>
                  </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
    </form>
<?php endif; ?>

<div class="actions">
    <a href="cart.php">🛒 View Cart</a>
    <a href="main.php">🏠 Back to Home</a>
</div>

</body>
</html>
