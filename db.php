<?php

    $dsn = "mysql:host=lamp_db;dbname=256project;charset=utf8mb4";
    $name = "user";
    $pass = "userpass";

    try {
        $db = new PDO($dsn, $name, $pass);
    } catch (PDOException $e){
        die("Connection failed: " . $e->getMessage());
}
