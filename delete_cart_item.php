<?php
session_start();
require "./db.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "consumer") {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["id"])) {
    http_response_code(400);
    echo "Bad Request";
    exit;
}

$id = (int)$_POST["id"];

$stmt = $db->prepare("DELETE FROM cart_items WHERE id = ?");
$stmt->execute([$id]);

echo "Deleted";