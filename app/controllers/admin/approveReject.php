<?php
    session_start();

    // SECURITY CHECK
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
    }

    // Check correct role
    if ($_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    require_once __DIR__ . "/../../core/dbconnection.php";

    $action = $_GET["action"] ?? null;

    //APPROVE SCHOLARSHIP (GET request)

    if ($action === "approve") {

        $id = $_GET["id"] ?? null;

        if (empty($id)) {
            header("Location: /Scholarship/app/views/users/admin/pending.php?error=missing_id");
            exit;
        }

        try {
            $adminID = $_SESSION["user_id"];

            $stmt = $pdo->prepare("
                UPDATE scholarshipprogram 
                SET status = 'approved', note = NULL, admin_ID = ?
                WHERE scholarship_ID = ?
            ");
            $stmt->execute([$adminID, $id]);

            header("Location: /Scholarship/app/views/users/admin/pending.php?success=approved");
            exit;

        } catch (PDOException $e) {
            header("Location: /Scholarship/app/views/users/admin/pending.php?error=db_approve");
            exit;
        }
    }

    //REJECT SCHOLARSHIP (POST request)

    if ($action === "reject" && $_SERVER["REQUEST_METHOD"] === "POST") {

        $id = $_POST["scholarship_ID"] ?? null;
        $reason = $_POST["reason"] ?? null;

        if (empty($id) || empty($reason)) {
            header("Location: /Scholarship/app/views/users/admin/pending.php?error=missing_fields");
            exit;
        }

        try {
            $adminID = $_SESSION["user_id"];

            $stmt = $pdo->prepare("
                UPDATE scholarshipprogram 
                SET status = 'rejected', note = ?, admin_ID = ?
                WHERE scholarship_ID = ?
            ");
            $stmt->execute([$reason, $adminID, $id]);

            header("Location: /Scholarship/app/views/users/admin/pending.php?success=rejected");
            exit;

        } catch (PDOException $e) {
            header("Location: /Scholarship/app/views/users/admin/pending.php?error=db_reject");
            exit;
        }
    }

    //FALLBACK REDIRECT

    header("Location: /Scholarship/app/views/users/admin/pending.php");
    exit;
