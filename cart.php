<?php
session_start();
require "./db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "consumer") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Get the cart ID
$stmt = $db->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

$items = [];
if ($cart_id) {
    $stmt = $db->prepare("
        SELECT ci.id AS cart_item_id, p.title, p.discounted_price, ci.quantity
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?
    ");
    $stmt->execute([$cart_id]);
    $items = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 90%; border-collapse: collapse; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        input[type="number"] { width: 60px; }
        button { padding: 6px 12px; cursor: pointer; }
        .actions { text-align: center; margin-top: 20px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2 style="text-align:center;">Your Shopping Cart</h2>

<?php if (empty($items)) : ?>
    <p style="text-align:center;">Your cart is empty.</p>
<?php else : ?>
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price (TL)</th>
            <th>Quantity</th>
            <th>Total (TL)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $grand_total = 0;
        foreach ($items as $item):
            $total = $item["discounted_price"] * $item["quantity"];
            $grand_total += $total;
        ?>
        <tr data-id="<?= $item["cart_item_id"] ?>">
            <td><?= htmlspecialchars($item["title"]) ?></td>
            <td><?= number_format($item["discounted_price"], 2) ?></td>
            <td><input type="number" class="qty" value="<?= $item["quantity"] ?>" min="1"></td>
            <td><?= number_format($total, 2) ?></td>
            <td>
                <button class="update">Update</button>
                <button class="delete">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Grand Total:</strong></td>
            <td colspan="2"><strong><?= number_format($grand_total, 2) ?> TL</strong></td>
        </tr>
    </tfoot>
</table>

<div class="actions">
    <button id="purchase">Purchase</button>
</div>

<script>
$(function() {
    $(".update").click(function() {
        let row = $(this).closest("tr");
        let id = row.data("id");
        let qty = row.find(".qty").val();

        $.post("update_cart.php", { id: id, qty: qty }, function() {
            location.reload();
        });
    });

    $(".delete").click(function() {
        if (!confirm("Are you sure to delete this item?")) return;
        let row = $(this).closest("tr");
        let id = row.data("id");

        $.post("delete_cart_item.php", { id: id }, function() {
            location.reload();
        });
    });

    $("#purchase").click(function() {
        if (!confirm("Confirm purchase?")) return;

        $.post("purchase.php", {}, function() {
            alert("Purchase complete!");
            location.reload();
        });
    });
});
</script>
<?php endif; ?>

<div class="actions">
    <p><a href="buy_product.php">🛍️ Continue Shopping</a> | <a href="main.php">🏠 Back to Home</a></p>
</div>

</body>
</html>
