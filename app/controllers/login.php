<?php
require_once __DIR__ . '/../core/dbconnection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

function tryLogin($pdo, $table, $userIdField, $role, $username, $password) {

    $stmt = $pdo->prepare("
        SELECT $userIdField AS id, username, password
        FROM $table
        WHERE username = BINARY :username
        LIMIT 1
    ");

    $stmt->execute([":username" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["logged_in"] = true;
        $_SESSION["role"] = $role;
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        return true;
    }

    return false;
}

if (tryLogin($pdo, "student", "ID", "student", $username, $password)) {
    header("Location: /Scholarship/app/views/users/student/dashboard.php");
    exit;
}

if (tryLogin($pdo, "sponsor", "sponsor_ID", "sponsor", $username, $password)) {
    header("Location: /Scholarship/app/views/users/sponsor/dashboard.php");
    exit;
}

if (tryLogin($pdo, "admissionofficer", "admin_ID", "admin", $username, $password)) {
    header("Location: /Scholarship/app/views/users/admin/dashboard.php");
    exit;
}

if (tryLogin($pdo, "admissionstaff", "reviewer_ID", "reviewer", $username, $password)) {
    header("Location: /Scholarship/app/views/users/reviewer/dashboard.php");
    exit;
}

header("Location: /Scholarship/app/views/auth/login.php?error=1");
exit;
