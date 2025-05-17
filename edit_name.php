<?php
    require "./protect.php";
    require "./db.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
        $newname = trim($newname);

        $stmt = $db->prepare("update users set name = ? where id = ?");
        $stmt->execute([$newname, $_SESSION["user_id"]]);

        $success = "Name updated successfully";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Name</title>
    <style>

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
}

.main {
    background: white;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 450px;
    text-align: center;
}

h1 {
    margin-bottom: 25px;
    font-weight: 600;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 10px 5px;
    vertical-align: middle;
    font-size: 16px;
    color: #444;
}

input[type="text"] {
    width: 100%;
    padding: 10px 12px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus {
    border-color: #4CAF50;
    outline: none;
}

.button {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 12px 0;
    font-size: 18px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #45a049;
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
        <h1>This Page For Updating Your Name</h1>
        <form action="" method="post">
            <table>
                <tr>
                    <td>Enter your new name:</td>
                    <td><input type="text" name="newname"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="button">Update</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>