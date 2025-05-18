<?php
session_start();
require "./db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "consumer") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["id"]) || !isset($_POST["qty"])) {
    http_response_code(400);
    echo "Bad Request";
    exit;
}

$id = (int)$_POST["id"];
$qty = max(1, (int)$_POST["qty"]);

$stmt = $db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
$stmt->execute([$qty, $id]);

echo "Updated";