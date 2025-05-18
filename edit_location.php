<?php
require "./protect.php";
require "./db.php";

$user_id = $_SESSION["user_id"];
$location = $db->prepare("SELECT city_id, district_id FROM users WHERE id = ?");
$location->execute([$user_id]);
$location = $location->fetch();

$cities = $db->query("SELECT * FROM cities")->fetchAll();

// Handle AJAX for districts
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["city_id"])) {
    $city_id = $_GET["city_id"];
    $stmt = $db->prepare("SELECT district_id, name FROM districts WHERE city_id = ?");
    $stmt->execute([$city_id]);
    header("Content-Type: application/json");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newcity = $_POST["city_id"] ?? "";
    $newdistrict = $_POST["district_id"] ?? "";

    if (!ctype_digit($newcity)) {
        $errors[] = "City is required.";
    }
    if (!ctype_digit($newdistrict)) {
        $errors[] = "District is required.";
    }

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE users SET city_id = ?, district_id = ? WHERE id = ?");
        $stmt->execute([$newcity, $newdistrict, $user_id]);
        header("Location: main.php");
        exit;
    }
    $location["city_id"] = $newcity;
    $location["district_id"] = $newdistrict;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Location</title>
    <style>
        select, .button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #ccc;
            margin-top: 10px;
        }
        .button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        .button:hover { background-color: #2980b9; }
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .main {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .err {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="main">
        <h2>Update Your Location</h2>
        <form method="POST">
            <label for="city">City:</label>
            <select name="city_id" id="city">
                <option value="">-- Select City --</option>
                <?php foreach($cities as $city): ?>
                    <option value="<?= $city["id"] ?>" <?= $city["id"] == $location["city_id"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($city["name"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="district">District:</label>
            <select name="district_id" id="district">
                <option value="">-- Select District --</option>
            </select>

            <button class="button" type="submit">Update</button>
        </form>

        <?php foreach ($errors as $e): ?>
            <p class="err"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>

    <script>
        const city = document.getElementById("city");
        const district = document.getElementById("district");
        const selectedCity = "<?= $location["city_id"] ?>";
        const selectedDistrict = "<?= $location["district_id"] ?>";

        function loadDistricts(cityId) {
            fetch("?city_id=" + cityId)
                .then(res => res.json())
                .then(data => {
                    district.innerHTML = `<option value="">-- Select District --</option>`;
                    data.forEach(d => {
                        const opt = document.createElement("option");
                        opt.value = d.district_id;
                        opt.textContent = d.name;
                        if (d.district_id == selectedDistrict) {
                            opt.selected = true;
                        }
                        district.appendChild(opt);
                    });
                });
        }

        city.addEventListener("change", () => loadDistricts(city.value));
        if (selectedCity) loadDistricts(selectedCity);
    </script>
</body>
</html>
