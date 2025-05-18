<?php
session_start();
require "./db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "consumer") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["product_id"]) || !isset($_POST["quantity"])) {
    http_response_code(400);
    echo "Missing product_id or quantity.";
    exit;
}

$user_id = $_SESSION["user_id"];
$product_id = (int)$_POST["product_id"];
$quantity = max(1, (int)$_POST["quantity"]);

// Get or create cart for this user
$stmt = $db->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    $db->prepare("INSERT INTO carts (user_id) VALUES (?)")->execute([$user_id]);
    $cart_id = $db->lastInsertId();
}

// Check if product already in cart
$stmt = $db->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->execute([$cart_id, $product_id]);
$cart_item = $stmt->fetch();

if ($cart_item) {
    // Update quantity
    $new_qty = $cart_item["quantity"] + $quantity;
    $stmt = $db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_qty, $cart_item["id"]]);
} else {
    // Insert new
    $stmt = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$cart_id, $product_id, $quantity]);
}

header("Location: buy_product.php?success=1");
exit;
