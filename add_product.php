<?php
session_start();
// always have a crsf token before the form
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
require "./db.php";

// only market users
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "market") {
    header("Location: login.php");
    exit;
}

$errors = [];
$title = "";
$stock = "";
$normal_price = "";
$discounted_price = "";
$expiration_date = "";

// check form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"] ?? "");
    $stock = trim($_POST["stock"] ?? "");
    $normal_price = trim($_POST["normal_price"] ?? "");
    $discounted_price = trim($_POST["discounted_price"] ?? "");
    $expiration_date = trim($_POST["expiration_date"] ?? "");
    $csrf_token = $_POST["csrf_token"] ?? "";

    // check csrf
    if ($csrf_token != $_SESSION["csrf_token"]) {
        $errors[] = "Bad CSRF token!";
    }

    // validate inputs
    if (empty($title)) {
        $errors[] = "Title is needed.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errors[] = "Stock must be a number >= 0.";
    }
    if (!is_numeric($normal_price) || $normal_price <= 0) {
        $errors[] = "Normal price must be > 0.";
    }
    if (!is_numeric($discounted_price) || $discounted_price <= 0) {
        $errors[] = "Discounted price must be > 0.";
    }
    if ($discounted_price >= $normal_price) {
        $errors[] = "Discounted price should be less than normal.";
    }
    if (empty($expiration_date) || strtotime($expiration_date) < time()) {
        $errors[] = "Expiration date must be in future.";
    }

    // handle image upload
    $image_path = null;
    $image_dir = "uploads/";

    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true); // create it if it doesn't exist
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $file_type = mime_content_type($_FILES["image"]["tmp_name"]);
        $file_size = $_FILES["image"]["size"];

        if (!in_array($file_type, ["image/jpeg", "image/png"])) {
            $errors[] = "Only JPEG or PNG images are allowed.";
        }

        if ($file_size > 2 * 1024 * 1024) {
            $errors[] = "Image must be smaller than 2MB.";
        }

        if (empty($errors)) {
            $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $filename = uniqid("img_", true) . "." . $ext;
            $image_path = $image_dir . $filename;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                $errors[] = "Image upload failed. Check folder permissions for /uploads.";
            }
        }
    } else if ($_FILES["image"]["error"] !== 4) {
        $errors[] = "Image upload error code: " . $_FILES["image"]["error"];
    }


    // if no errors, save to db
    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO products (market_id, title, stock, normal_price, discounted_price, expiration_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION["user_id"], $title, $stock, $normal_price, $discounted_price, $expiration_date, $image_path]);

        // new csrf token
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        header("Location: manage_products.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 600px; margin: auto; }
        .error { color: red; }
        table { width: 100%; }
        td { padding: 8px; }
        input, button { width: 100%; padding: 8px; }
        button { background: green; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
            <table>
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"></td>
                </tr>
                <tr>
                    <td>Stock:</td>
                    <td><input type="number" name="stock" value="<?php echo htmlspecialchars($stock); ?>"></td>
                </tr>
                <tr>
                    <td>Normal Price (TL):</td>
                    <td><input type="number" step="0.01" name="normal_price" value="<?php echo htmlspecialchars($normal_price); ?>"></td>
                </tr>
                <tr>
                    <td>Discounted Price (TL):</td>
                    <td><input type="number" step="0.01" name="discounted_price" value="<?php echo htmlspecialchars($discounted_price); ?>"></td>
                </tr>
                <tr>
                    <td>Expiration Date:</td>
                    <td><input type="date" name="expiration_date" value="<?php echo htmlspecialchars($expiration_date); ?>"></td>
                </tr>
                <tr>
                    <td>Image:</td>
                    <td><input type="file" name="image" accept="image/jpeg,image/png"></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit">Add Product</button></td>
                </tr>
            </table>
        </form>

        <?php if (!empty($errors)) { ?>
            <?php foreach ($errors as $e) { ?>
                <p class="error"><?php echo htmlspecialchars($e); ?></p>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>