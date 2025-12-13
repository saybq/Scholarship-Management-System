<?php
    //The purpose of this file is to set the status to under_review

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

    $id = $_GET["id"] ?? null;

    if (!$id) {
        die("Missing scholarship ID.");
    }

    $sql = $pdo->prepare("
        UPDATE scholarshipprogram 
        SET status = 'under_review'
        WHERE scholarship_ID = :id");

    $sql->execute([":id" => $id]);

    header("Location: /Scholarship/app/views/users/admin/manageScholarship.php?under_review=1");
    exit;