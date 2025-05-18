<?php
session_start();
require "./db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "consumer") {
    header("Location: login.php");
    exit;
}

// Get user location
$stmt = $db->prepare("SELECT city_id, district_id FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user_location = $stmt->fetch();

$city_id = $user_location["city_id"];
$district_id = $user_location["district_id"];

$keyword = trim($_GET["q"] ?? "");
$start = max(0, (int)($_GET["start"] ?? 0));
$limit = 4;

// Get filtered product list
$stmt = $db->prepare("SELECT p.*, u.name AS market_name, u.district_id, d.name AS district_name
    FROM products p
    JOIN users u ON p.market_id = u.id
    JOIN districts d ON u.district_id = d.district_id
    WHERE p.stock > 0
    AND p.expiration_date >= CURDATE()
    AND p.title LIKE ?
    AND u.city_id = ?
    ORDER BY (u.district_id = ?) DESC");

$stmt->execute(["%$keyword%", $city_id, $district_id]);
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$end = min($start + $limit, count($allProducts));
$products = array_slice($allProducts, $start, $limit);

// view cart count sql statement
$stmt = $db->prepare("SELECT SUM(quantity) FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = ?)");
$stmt->execute([$_SESSION["user_id"]]);
$cart_count = $stmt->fetchColumn() ?: 0;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Products</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; }
        form.search { text-align: center; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 90%; max-width: 800px; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: center; }
        input[type="number"] { width: 60px; }
        .actions { margin-top: 20px; text-align: center; }
        .actions a { margin: 0 10px; text-decoration: none; color: #007bff; }
        .actions a:hover { text-decoration: underline; }
        .inactive { opacity: 0.2; pointer-events: none; }
    </style>
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $(".add-to-cart").click(function() {
        const row = $(this).closest("tr");
        const productId = $(this).data("id");
        const quantity = row.find(".qty").val();

        $.post("add_to_cart.php", { product_id: productId, quantity: quantity }, function() {
            // ✅ update cart icon count
            $.get("get_cart_count.php", function(count) {
                $("#cart-count").text(count);
            });

            alert("Product added to cart!");
        });
    });
});
</script>

<body>

<h2>Available Products</h2>
<form method="GET" class="search">
    <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit">Search</button>
</form>

<?php if (empty($products)): ?>
    <p style="text-align:center;">No products found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Market</th>
                <th>District</th>
                <th>Product</th>
                <th>Price (TL)</th>
                <th>Quantity</th>
                <th>Add</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product["market_name"]) ?></td>
                <td><?= htmlspecialchars($product["district_name"]) ?></td>
                <td><?= htmlspecialchars($product["title"]) ?></td>
                <td class="price"><?= number_format($product["discounted_price"], 2) ?></td>
                <td><input type="number" class="qty" value="1" min="1"></td>
                <td>
                    <button class="add-to-cart" data-id="<?= $product["id"] ?>">Add to Cart</button>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="actions">
        <?php if ($start > 0): ?>
            <a href="?q=<?= urlencode($keyword) ?>&start=<?= $start - $limit ?>">&laquo; Prev</a>
        <?php else: ?>
            <span class="inactive">&laquo; Prev</span>
        <?php endif; ?>

        <?php if ($end < count($allProducts)): ?>
            <a href="?q=<?= urlencode($keyword) ?>&start=<?= $start + $limit ?>">Next &raquo;</a>
        <?php else: ?>
            <span class="inactive">Next &raquo;</span>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="actions">
    <a href="cart.php">🛒 Cart (<span id="cart-count"><?= $cart_count ?></span>)</a>
    <a href="main.php">🏠 Back to Home</a>
</div>
</body>
</html>