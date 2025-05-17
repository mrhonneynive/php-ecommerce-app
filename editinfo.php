<?php
    require "./protect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Info</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 40px 20px;
        display: flex;
        justify-content: center;
        min-height: 100vh;
        color: #333;
    }

    .main {
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        max-width: 700px;
        width: 100%;
        text-align: center;
    }

    h3 {
        color: #555;
        margin-bottom: 30px;
        font-weight: 600;
        font-size: 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    tr {
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
    }

    td {
        flex: 1 1 150px;
        max-width: 150px;
        text-align: center;
    }

    .button {
        background-color: #4caf50;
        border-radius: 6px;
        padding: 12px 0;
        box-shadow: 0 3px 8px rgba(76,175,80,0.4);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .button a {
        text-decoration: none;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        display: block;
    }

    .button:hover {
        background-color: #43a047;
        box-shadow: 0 5px 12px rgba(67,160,71,0.6);
    }

    .link {
        position: fixed;
        top: 20px;
        left: 20px;
        font-size: 16px;
        text-decoration: none;
        color: #4caf50;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
        z-index: 10;
    }

    .link:hover {
        color: #388e3c;
    }
</style>
</head>
<body>
    <!-- Back -->
    <a href="main.php" class="link">
         Back
    </a>



    <div class="main">
        <h3>Choose Edit Profile</h3>
        <form action="" method="post">
            <table>
                <tr>
                    <td>
                        <div class="button">
                            <a href="edit_name.php">Name</a>
                        </div>    
                    </td>
                    <td>
                        <div class="button">
                            <a href="edit_email.php">Email</a>
                        </div>    
                    </td>
                    <td>
                        <div class="button">
                            <a href="edit_password.php">Password</a>
                        </div>    
                    </td>
                    <td>
                        <div class="button">
                            <a href="edit_location.php">Location</a>
                        </div>    
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
