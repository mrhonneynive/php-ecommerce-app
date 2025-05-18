<?php
session_start();
require "./db.php";

if (!isset($_SESSION["user_id"])) {
    echo 0;
    exit;
}

$stmt = $db->prepare("SELECT SUM(quantity) FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = ?)");
$stmt->execute([$_SESSION["user_id"]]);
echo $stmt->fetchColumn() ?: 0;
?>
