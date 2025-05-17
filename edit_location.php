<?php
    require "./protect.php";
    require "./db.php";

    $user_id = $_SESSION["user_id"];
    $stmt = $db->prepare("select city_id, district_id from users where id = ?");
    $stmt->execute([$user_id]);
    $location = $stmt->fetch();

    $cities = $db->query("select * from cities")->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["city_id"])) {
    $city_id = $_GET["city_id"];

    $stmt = $db->prepare("select district_id, name from districts where city_id = ?");
    $stmt->execute([$city_id]);
    $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("Content-Type: application/json");
    echo json_encode($districts ?: []);
    exit;
}


    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $newcity = $_POST["city_id"] ?? "";
        $newdistrict = $_POST["district_id"] ?? "";

        if (empty($newcity)){
            $errors[] = "City is required.";
        }

        if (empty($newdistrict)) {
            $errors[] = "District is required.";
        }

        if( empty($errors)){
            $stmt = $db->prepare("update users set city_id = ?, district_id = ? where id = ?");
            $stmt->execute([$newcity, $newdistrict, $user_id]);

            header("Location: main.php");
            exit;
        }

        // override displayed values after the form err
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

select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 2px solid #ccc;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background-color: #fff;
}

select:focus {
    border-color: #3498db;
    outline: none;
}


.button {
    padding: 12px 24px;
    font-size: 16px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
    width: 100%;
}

.button:hover {
    background-color: #2980b9;
}


body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.main {
    background-color: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 90%;
}

h1 {
    margin-bottom: 30px;
    color: #2c3e50;
    text-align: center;
}

table {
    width: 100%;
}

td {
    padding: 10px;
    vertical-align: top;
    font-size: 16px;
    color: #2c3e50;
}

.err {
    color: #e74c3c;
    font-weight: bold;
    margin-top: 10px;
    text-align: center;
}


.link {
    position: absolute;
    top: 20px;
    left: 30px;
    color: #3498db;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.link:hover {
    color: #1d6fa5;
}

    </style>
</head>
<body>
    <a href="editinfo.php" class="link">
        Back
    </a>
    <div class="main">
        <h1>This Page For Updating Your location</h1>
        <form action="" method="post">
            <table>
                <tr>
                    <td>City:</td>
                    <td>
                        <select name="city_id" id="city">
                            <option value="">SELECT CITY</option>
                            <?php foreach($cities as $city) :?>
                                <option value="<?=$city["id"]?>" <?= $city["id"] == $location["city_id"] ? "selected" : "" ?>>
                                    <?=$city["name"]?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>District:</td>
                    <td>
                        <select name="district_id" id="district">
                            <option value="">SELECT DISTRICT</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <button class="button">Update</button>
                    </td>
                </tr>
            </table>
        </form>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <p class="err"><?= $e ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <script>
        document.getElementById("city").addEventListener("change", function(){
            cityId = this.value;

            fetch("?city_id=" + cityId)
            .then(res => res.json())
            .then(data => {
                const districtSelect = document.getElementById("district");
                districtSelect.innerHTML = `<option value="">SELECT DISTRICT</option>`;

                data.forEach(district => {
                    const option = document.createElement("option");
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                })
            })
        })

        //current location
        window.addEventListener("DOMContentLoaded", () => {
            const selectedCity = "<?= $location["city_id"] ?>";
            const selectedDistrict = "<?= $location["district_id"] ?>";

            if (selectedCity) {
                fetch("?city_id=" + selectedCity)
                    .then(res => res.json())
                    .then(data => {
                        const districtSelect = document.getElementById("district");
                        districtSelect.innerHTML = `<option value="">SELECT DISTRICT</option>`;

                        data.forEach(district => {
                            const option = document.createElement("option");
                            option.value = district.id;
                            option.textContent = district.name;
                            if (district.id == selectedDistrict) {
                                option.selected = true;
                            }
                            districtSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</body>
</html>