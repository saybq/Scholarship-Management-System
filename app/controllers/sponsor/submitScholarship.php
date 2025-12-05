<?php
session_start();
require_once __DIR__ . "/../../core/dbconnection.php";

// Sponsor must be logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "sponsor") {
    header("Location: ../../views/auth/login.php");
    exit;
}

$sponsor_ID   = $_SESSION["user_id"];
$name         = $_POST['scholarship_name'] ?? '';
$desc         = $_POST['description'] ?? '';
$amount_input = $_POST['amount'] ?? '';
$requirements = $_POST['requirements'] ?? '';
$deadline     = $_POST['deadline'] ?? '';

$amount = (float)$amount_input;
$today  = date("Y-m-d");

// Required field validation
if (empty($name) || empty($desc) || empty($amount_input) || empty($deadline)) {
    header("Location: ../../views/sponsor/scholarships.php?error=1");
    exit;
}

$sql = "INSERT INTO scholarshipprogram
        (sponsor_ID, scholarship_Name, description, Amount, requirements, deadline, status, admin_ID, dateof_creation)
        VALUES (:sponsor_ID, :name, :desc, :amount, :requirements, :deadline, 'pending', NULL, :today)";

try {

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':sponsor_ID'   => $sponsor_ID,
        ':name'         => $name,
        ':desc'         => $desc,
        ':amount'       => $amount,
        ':requirements' => $requirements,
        ':deadline'     => $deadline,
        ':today'        => $today
    ]);

    header("Location: /Scholarship/app/views/users/sponsor/myprograms.php?success=1");
    exit;

} catch (PDOException $e) {
    $error_message = urlencode($e->getMessage());
    header("Location: /Scholarship/app/views/users/sponsor/myprograms.php?error=1");
    exit;
}
