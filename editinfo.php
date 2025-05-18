<?php
require "./protect.php";
require "./db.php";

$user_id = $_SESSION["user_id"];
$stmt = $db->prepare("
    SELECT u.name, u.email, u.role, c.name AS city_name, d.name AS district_name
    FROM users u
    JOIN cities c ON u.city_id = c.id
    JOIN districts d ON u.district_id = d.district_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      min-height: 100vh;
    }

    .main {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      max-width: 600px;
      width: 100%;
      text-align: center;
    }

    .avatar {
      font-size: 60px;
      color: #4caf50;
      margin-bottom: 10px;
    }

    h2 {
      margin: 10px 0 5px;
      color: #2c3e50;
    }

    .sub-info {
      font-size: 14px;
      color: #777;
      margin-bottom: 30px;
    }

    .button-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .card-button {
      background-color: #4caf50;
      color: white;
      font-weight: 600;
      font-size: 16px;
      padding: 14px 24px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      min-width: 120px;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .card-button:hover {
      background-color: #43a047;
      box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    }

    .back-link {
      position: absolute;
      top: 20px;
      left: 30px;
      text-decoration: none;
      color: #4caf50;
      font-weight: bold;
    }

    .back-link:hover {
      color: #388e3c;
    }
  </style>
</head>
<body>
<a href="main.php" class="back-link"><i class="fas fa-arrow-left"></i> Back</a>

<div class="main">
  <div class="avatar"><i class="fas fa-user-circle"></i></div>
  <h2><?= htmlspecialchars($user["name"]) ?></h2>
  <div class="sub-info"><?= htmlspecialchars($user["email"]) ?> — <?= ucfirst($user["role"]) ?></div>
  <div class="sub-info"><?= htmlspecialchars($user["city_name"]) ?> / <?= htmlspecialchars($user["district_name"]) ?></div>

  <div class="button-grid">
    <a class="card-button" href="edit_name.php">Edit Name</a>
    <a class="card-button" href="edit_email.php">Edit Email</a>
    <a class="card-button" href="edit_password.php">Edit Password</a>
    <a class="card-button" href="edit_location.php">Edit Location</a>
  </div>
</div>

</body>
</html>
