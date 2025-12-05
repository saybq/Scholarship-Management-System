<?php
require_once __DIR__ . '/../core/dbconnection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

// 1. Try Student Login
$stmt = $pdo->prepare("SELECT * FROM student WHERE username = :username LIMIT 1");
$stmt->execute([":username" => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    
    $_SESSION["logged_in"] = true;
    $_SESSION["role"] = "student";
    $_SESSION["user_id"] = $user["student_ID"];
    $_SESSION["username"] = $user["username"];

    header("Location: /Scholarship/app/views/users/student/dashboard.php");
    exit;
}


// 2. Try Sponsor Login
$stmt = $pdo->prepare("SELECT * FROM sponsor WHERE username = :username LIMIT 1");
$stmt->execute([":username" => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {

    $_SESSION["logged_in"] = true;
    $_SESSION["role"] = "sponsor";
    $_SESSION["user_id"] = $user["sponsor_ID"];
    $_SESSION["username"] = $user["username"];

    header("Location: /Scholarship/app/views/users/sponsor/dashboard.php");
    exit;
}


// 3. Try Admin (Admission Officer)
$stmt = $pdo->prepare("SELECT * FROM admissionofficer WHERE username = :username LIMIT 1");
$stmt->execute([":username" => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {

    $_SESSION["logged_in"] = true;
    $_SESSION["role"] = "admin";
    $_SESSION["user_id"] = $user["admin_ID"];
    $_SESSION["username"] = $user["username"];

    header("Location: /Scholarship/app/views/users/admin/dashboard.php");
    exit;
}


// 4. Try Reviewer (Admission Staff)
$stmt = $pdo->prepare("SELECT * FROM admissionstaff WHERE username = :username LIMIT 1");
$stmt->execute([":username" => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {

    $_SESSION["logged_in"] = true;
    $_SESSION["role"] = "reviewer";
    $_SESSION["user_id"] = $user["reviewer_ID"];
    $_SESSION["username"] = $user["username"];

    header("Location: /Scholarship/app/views/users/reviewer/dashboard.php");
    exit;
}

    header("Location: /Scholarship/app/views/auth/login.php?error=1");
    exit;