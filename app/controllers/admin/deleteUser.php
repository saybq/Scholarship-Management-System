<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    // Must be logged in
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    // Must be admin
    if ($_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    $type = $_GET['type'];      // student / sponsor / reviewer
    $id   = intval($_GET['id']); // ensure integer

    switch ($type) {
        case "student":
            $table = "student";
            $column = "ID";
            break;

        case "sponsor":
            $table = "sponsor";
            $column = "sponsor_ID";
            break;

        case "reviewer": // admission staff
            $table = "admissionstaff";
            $column = "reviewer_ID";
            break;

        default:
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=invalid_type");
            exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $column = :id");

        if ($stmt->execute([':id' => $id])) {
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?deleted=1");
            exit;
        } else {
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?delete_error=1");
            exit;
        }
    } 
    catch (PDOException $e) {
        header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=exception");
        exit;
    }