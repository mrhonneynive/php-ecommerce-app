<?php
    require "./protect.php";
    require "./db.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        extract($_POST);
        if (filter_var(trim($newmail), FILTER_VALIDATE_EMAIL)){

            // check if the entered email already exists
            $stmt = $db->prepare("select email from users where email = ? and id != ?");
            $stmt->execute([$newmail, $_SESSION["user_id"]]);
            $old = $stmt->fetch();

            if ($old){
                $error = "This email is already used by another account!";
            } else {
                // update mail
                $stmt = $db->prepare("update users set email = ? where id = ?");
                $stmt->execute([$newmail, $_SESSION["user_id"]]);

                $success = "Email updated successfully";
            }
        } else {
            $error = "You entered an invalid email address!";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Email</title>
    <style>

input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus {
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
}

.button:hover {
    background-color: #2980b9;
}


body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
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
    text-align: center;
}

h1 {
    margin-bottom: 30px;
    color: #2c3e50;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

td {
    padding: 12px;
    vertical-align: middle;
    font-size: 16px;
    color: #34495e;
}

p {
    margin-top: 20px;
    font-weight: bold;
}

p:empty {
    display: none;
}

p.error {
    color: #e74c3c;
}

p.success {
    color: #2ecc71;
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
        <h1>This Page For Updating Your Email</h1>
            <form action="" method="post">
                <table>
                    <tr>
                        <td>Enter your new email :</td>
                        <td><input type="text" name="newmail"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="button">Update</button>
                        </td>
                    </tr>
                </table>
            </form>
            <?php if (isset($error)) :?>
                <p><?=$error?></p>
            <?php elseif (isset($success)) :?>
                <p><?=$success?></p>
            <?php endif ?>
    </div>
</body>
</html>