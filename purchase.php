<?php
session_start();
require "./db.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "consumer") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["id"];

// Get cart ID
$stmt = $db->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->execute([$user_id]);
$cart_id = $stmt->fetchColumn();

if (!$cart_id) {
    echo "No cart found.";
    exit;
}

// Get all cart items with product info
$stmt = $db->prepare("
    SELECT ci.product_id, ci.quantity, p.stock 
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
");
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll();

if (!$items) {
    echo "Cart is empty.";
    exit;
}

// Reduce stock for each product
foreach ($items as $item) {
    $new_stock = max(0, $item["stock"] - $item["quantity"]);
    $stmt = $db->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->execute([$new_stock, $item["product_id"]]);
}

// Delete cart items
$stmt = $db->prepare("DELETE FROM cart_items WHERE cart_id = ?");
$stmt->execute([$cart_id]);

echo "Purchase completed!";
