<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== "sponsor") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: /Scholarship/app/views/users/sponsor/profile.php");
        exit;
    }

    $id = $_SESSION['user_id'];

    function clean($v) {
        return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
    }

    $username        = clean($_POST['username']);
    $firstname       = clean($_POST['first_Name']);
    $middlename      = clean($_POST['middle_Name'] ?? null);
    $lastname        = clean($_POST['last_Name']);
    $email           = clean($_POST['email']);
    $contact         = clean($_POST['contact_Number']);
    $password        = $_POST['password']; // no cleaning for password

    if ($username === "" || $firstname === "" || $lastname === "" || $email === "" || $contact === "") {
        header("Location: /Scholarship/app/views/users/sponsor/profile.php?error=Missing required fields");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /Scholarship/app/views/users/sponsor/profile.php?error=Invalid email");
        exit;
    }

    if (!preg_match("/^[0-9]{11}$/", $contact)) { 
        header("Location: /Scholarship/app/views/users/sponsor/profile.php?error=Invalid contact number (11 digits required)");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM sponsor WHERE username = ? AND sponsor_ID != ?");
    $stmt->execute([$username, $id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/sponsor/profile.php?error=Username already taken");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM sponsor WHERE email = ? AND sponsor_ID != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/sponsor/profile.php?error=Email already used");
        exit;
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE sponsor SET
                    username = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?,
                    contact_Number = ?,
                    password = ?
                WHERE sponsor_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $firstname,
            $middlename,
            $lastname,
            $email,
            $contact,
            $hashed,
            $id
        ]);
    } else {
        $sql = "UPDATE sponsor SET
                    username = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?,
                    contact_Number = ?
                WHERE sponsor_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $firstname,
            $middlename,
            $lastname,
            $email,
            $contact,
            $id
        ]);
    }

    header("Location: /Scholarship/app/views/users/sponsor/profile.php?success=1");
    exit;
