<?php
session_start();
require "./db.php";

// only market users
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "market") {
    header("Location: login.php");
    exit;
}

// check id and csrf
if (!isset($_GET["id"]) || !isset($_GET["csrf_token"]) || $_GET["csrf_token"] != $_SESSION["csrf_token"]) {
    header("Location: manage_products.php");
    exit;
}

$product_id = $_GET["id"];

// get product
$stmt = $db->prepare("SELECT image_path FROM products WHERE id = ? AND market_id = ?");
$stmt->execute([$product_id, $_SESSION["id"]]);
$product = $stmt->fetch();

if ($product) {
    // delete image if exists
    if ($product["image_path"] && file_exists($product["image_path"])) {
        unlink($product["image_path"]);
    }

    // delete product
    $stmt = $db->prepare("DELETE FROM products WHERE id = ? AND market_id = ?");
    $stmt->execute([$product_id, $_SESSION["id"]]);

    // new csrf token
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

header("Location: manage_products.php");
exit;
?>