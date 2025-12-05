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

    $admin_id = $_SESSION['user_id'];

    // Get form inputs
    $username    = $_POST['username'];
    $firstname   = $_POST['first_Name'];
    $middlename  = $_POST['middle_Name'] ?? null;
    $lastname    = $_POST['last_Name'];
    $email       = $_POST['email'];
    $password    = $_POST['password'];  // optional

    // If password is entered → update it
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE admissionofficer SET
                    username = ?,
                    password = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?
                WHERE admin_ID = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $hashed,
            $firstname,
            $middlename,
            $lastname,
            $email,
            $admin_id
        ]);

    } else {
        // No password change
        $sql = "UPDATE admissionofficer SET
                    username = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?
                WHERE admin_ID = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $firstname,
            $middlename,
            $lastname,
            $email,
            $admin_id
        ]);
    }


    header("Location: /Scholarship/app/views/users/admin/profile.php?success=1");
    exit;
