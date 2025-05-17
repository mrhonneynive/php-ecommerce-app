<?php
    //session_start();
    require "db.php";

    if (!isset($_SESSION["user_id"])) {
        if (isset($_COOKIE["remember_token"])) {
            $stmt = $db->prepare("select user_id from tokens where token = ? and expire_date > NOW()");
            $stmt->execute([$_COOKIE["remember_token"]]);
            $row = $stmt->fetch();
            if ($row) {
                $_SESSION["user_id"] = $row["user_id"];

                // fetching user role too
                $stmt = $db->prepare("select role from users where id = ?");
                $stmt->execute([$_SESSION["user_id"]]);
                $_SESSION["role"] = $stmt->fetchColumn();
            } else {
                header("Location: login.php");
                exit;
            }
        } else {
            header("Location: login.php");
            exit;
        }
    }
   