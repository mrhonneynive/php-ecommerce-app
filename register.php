<?php
require "./db.php";
$cities = $db->query("SELECT * FROM cities")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["city_id"])) {
    $city_id = $_GET["city_id"];

    $stmt = $db->prepare("SELECT district_id, name FROM districts WHERE city_id = ?");
    $stmt->execute([$city_id]);
    $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("Content-Type: application/json");
    echo json_encode($districts ?: []);
    exit;
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $names = $_POST["names"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $user = $_POST["user"] ?? "";
    $city_id = $_POST["city_id"] ?? "";
    $district_id = $_POST["districts"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address!";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    }

    if (empty($names)) {
        $errors[] = "Name is empty.";
    }

    if (empty($city_id)) {
        $errors[] = "City is required.";
    }

    if (empty($district_id)) {
        $errors[] = "District is required.";
    }

    if (empty($user)) {
        $errors[] = "Invalid user type!";
    }

    if (empty($errors)) {
        session_start();
        $_SESSION["pending_user"] = [
            "names" => $names,
            "email" => $email,
            "password" => $hashed_pass,
            "city_id" => $city_id,
            "district_id" => $district_id,
            "user" => $user
        ];

        header("Location: verification.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    form {
        background-color: #ffffff;
        padding: 40px 50px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    table {
        width: 100%;
        border-spacing: 10px;
    }

    td {
        vertical-align: middle;
    }

    input[type="text"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }

    input[type="radio"] {
        margin-right: 6px;
    }

    button {
        width: 100%;
        background-color: #3498db;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #2980b9;
    }

    .error {
        color: #e74c3c;
        font-weight: bold;
        margin-top: 10px;
        text-align: center;
    }

    span {
        font-size: 14px;
    }

    #name_label {
        font-weight: 600;
        white-space: nowrap;
    }
</style>

</head>
<body>
<form action="" method="post">
    <table>
        <tr>
            <td id="name_label">Name :</td>
            <td><input type="text" name="names" id="names" value="<?= htmlspecialchars($_POST["names"] ?? '') ?>"></td>
        </tr>
        <tr>
            <td>Email :</td>
            <td><input type="text" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? '') ?>"></td>
        </tr>
        <tr>
            <td>Password :</td>
            <td><input type="password" name="password" id="password"></td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="user" value="consumer" <?= ($_POST["user"] ?? '') === "consumer" ? "checked" : "" ?> onchange="toggleUser()">
                <span>Consumer</span>
            </td>
            <td>
                <input type="radio" name="user" value="market" <?= ($_POST["user"] ?? '') === "market" ? "checked" : "" ?> onchange="toggleUser()">
                <span>Market</span>
            </td>
        </tr>
        <tr>
            <td>Cities :</td>
            <td>
                <select name="city_id" id="cities">
                    <option value="">-- Select --</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= $c["id"] ?>" <?= ($_POST["city_id"] ?? '') == $c["id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($c["name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Districts :</td>
            <td>
                <select name="districts" id="district">
                    <option value="">-- Select --</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit">Register</button></td>
        </tr>
    </table>
</form>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $e): ?>
        <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function toggleUser() {
    const user = document.querySelector("input[name=user]:checked").value;
    const nameLabel = document.getElementById("name_label");
    nameLabel.innerText = user === "market" ? "Market Name :" : "Name :";
}

document.getElementById("cities").addEventListener("change", function () {
    const city_id = this.value;
    const districtSelect = document.getElementById("district");
    districtSelect.innerHTML = `<option value="">-- Select --</option>`;

    if (!city_id) return;

    fetch("?city_id=" + city_id)
        .then(res => res.json())
        .then(data => {
            data.forEach(district => {
                const option = document.createElement("option");
                option.value = district.district_id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
        });
});
</script>

</body>
</html>
