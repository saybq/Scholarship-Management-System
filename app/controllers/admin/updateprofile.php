<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: /Scholarship/app/views/users/admin/profile.php");
        exit;
    }

    $admin_id = $_SESSION['user_id'];

    function clean($v) {
        return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
    }

    $username   = clean($_POST['username']);
    $firstname  = clean($_POST['first_Name']);
    $middlename = clean($_POST['middle_Name'] ?? null);
    $lastname   = clean($_POST['last_Name']);
    $email      = clean($_POST['email']);
    $password   = $_POST['password']; // do NOT HTML clean passwords

    if ($username === "" || $firstname === "" || $lastname === "" || $email === "") {
        header("Location: /Scholarship/app/views/users/admin/profile.php?error=Missing required fields");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /Scholarship/app/views/users/admin/profile.php?error=Invalid email");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admissionofficer WHERE username = ? AND admin_ID != ?");
    $stmt->execute([$username, $admin_id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/admin/profile.php?error=Username already taken");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admissionofficer WHERE email = ? AND admin_ID != ?");
    $stmt->execute([$email, $admin_id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/admin/profile.php?error=Email already used");
        exit;
    }

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
