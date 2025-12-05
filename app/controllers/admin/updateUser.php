<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    // Must be logged in
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    // Must be admin
    if ($_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Scholarship/app/views/users/admin/profile.php");
        exit;
    }


    $id       = $_POST["id"];
    $type     = $_POST["type"];
    $username = $_POST["username"];
    $password = trim($_POST["password"]);

    $table   = "";
    $idField = "";

    // Identify table & ID column
    switch ($type) {
        case "reviewer":
            $table = "admissionstaff";
            $idField = "reviewer_ID";
            break;

        case "sponsor":
            $table = "sponsor";
            $idField = "sponsor_ID";
            break;

        case "student":
            $table = "student";
            $idField = "ID";
            break;

        default:
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=unknown");
            exit;
    }

    try {
        // If password field is empty → only update username
        if ($password === "") {

            $stmt = $pdo->prepare("
                UPDATE $table 
                SET username = :username 
                WHERE $idField = :id
            ");

            $stmt->execute([
                ":username" => $username,
                ":id" => $id
            ]);

            header("Location: /Scholarship/app/views/users/admin/manageUser.php?updated=1");
            exit;
        }

        // If password is present → update both username & password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE $table 
            SET username = :username, password = :password
            WHERE $idField = :id
        ");

        $stmt->execute([
            ":username" => $username,
            ":password" => $hashed,
            ":id" => $id
        ]);

        header("Location: /Scholarship/app/views/users/admin/manageUser.php?updated=1");
        exit;

    } catch (PDOException $e) {
        header("Location: /Scholarship/app/views/users/admin/manageUser.php?update_error=1");
        exit;
    }
    

