<?php
session_start();
require_once __DIR__ . "/../../core/dbconnection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "sponsor") {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

$id = $_SESSION['user_id'];

// MATCH FORM FIELD NAMES
$username        = $_POST['username'];
$sponsor_company = $_POST['sponsor_company'];
$firstname       = $_POST['first_Name'];
$middlename      = $_POST['middle_Name'] ?? null;
$lastname        = $_POST['last_Name'];
$email           = $_POST['email'];
$contact         = $_POST['contact_Number'];
$password        = $_POST['password'];

// UPDATE WITH OR WITHOUT PASSWORD
if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE sponsor SET
                username = ?,
                sponsor_company = ?,
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
        $sponsor_company,
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
                sponsor_company = ?,
                first_Name = ?,
                middle_Name = ?,
                last_Name = ?,
                email = ?,
                contact_Number = ?
            WHERE sponsor_ID = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $username,
        $sponsor_company,
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
