<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SESSION["role"] !== "reviewer") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Scholarship/app/views/users/reviewer/profile.php");
        exit;
    }

    $reviewer_ID = $_SESSION['user_id'];

    function clean($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    $username     = clean($_POST['username']);
    $firstname    = clean($_POST['first_Name']);
    $middlename   = clean($_POST['middle_Name'] ?? null);
    $lastname     = clean($_POST['last_Name']);
    $email        = clean($_POST['email']);
    $department   = clean($_POST['department']);
    $password     = $_POST['password'];

    if ($username === "" || $firstname === "" || $lastname === "" || $email === "" || $department === "") {
        header("Location: /Scholarship/app/views/users/reviewer/profile.php?error=Missing required fields");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /Scholarship/app/views/users/reviewer/profile.php?error=Invalid email");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admissionstaff WHERE username = ? AND reviewer_ID != ?");
    $stmt->execute([$username, $reviewer_ID]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/reviewer/profile.php?error=Username already taken");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admissionstaff WHERE email = ? AND reviewer_ID != ?");
    $stmt->execute([$email, $reviewer_ID]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/reviewer/profile.php?error=Email already used");
        exit;
    }

    // UPDATE
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE admissionstaff SET
                    username = ?,
                    password = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?,
                    department = ?
                WHERE reviewer_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username, $hashed, $firstname, $middlename, $lastname, $email, $department, $reviewer_ID
        ]);

    } else {
        $sql = "UPDATE admissionstaff SET
                    username = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    email = ?,
                    department = ?
                WHERE reviewer_ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username, $firstname, $middlename, $lastname, $email, $department, $reviewer_ID
        ]);
    }

    header("Location: /Scholarship/app/views/users/reviewer/profile.php?success=1");
    exit;
