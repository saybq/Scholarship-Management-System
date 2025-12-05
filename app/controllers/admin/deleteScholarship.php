<?php
    //The purpose of this file is not to delete entirely the program but it is just gonna update the status to inactive

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
        SET status = 'inactive'
        WHERE scholarship_ID = :id");

    $sql->execute([":id" => $id]);

    header("Location: /Scholarship/app/views/users/admin/manageScholarship.php?deleted=1");
    exit;
