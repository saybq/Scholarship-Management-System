<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
    }

    // Check correct role
    if ($_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['assignReviewer'])) {

    $scholarship_ID = $_POST['scholarship_ID'];
    $reviewer_ID = $_POST['reviewer_ID'];

    $sql = $pdo->prepare("
        UPDATE scholarshipprogram 
        SET reviewer_ID = :reviewer_ID
        WHERE scholarship_ID = :scholarship_ID
    ");

    $sql->execute([
        ':reviewer_ID' => $reviewer_ID,
        ':scholarship_ID' => $scholarship_ID
    ]);

    header("Location: /Scholarship/app/views/users/admin/manageScholarship.php?updated=1");
    exit;
}
?>
