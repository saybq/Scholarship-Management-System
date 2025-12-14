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

    if (!isset($_GET["id"])) {
    header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php");
    exit;
}

    $programID  = (int) $_GET["id"];
    $reviewerID = (int) $_SESSION["user_id"];

    $checkStmt = $pdo->prepare("
        SELECT scholarship_ID
        FROM scholarshipprogram
        WHERE scholarship_ID = ?
        AND reviewer_ID = ?
        AND status IN ('under_review', 'approved')
    ");
    $checkStmt->execute([$programID, $reviewerID]);

    if ($checkStmt->fetchColumn() === false) {
        // Program not assigned to this reviewer or invalid state
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php");
        exit;
    }

    $pendingStmt = $pdo->prepare("
        SELECT COUNT(application_ID)
        FROM application
        WHERE scholarship_ID = ?
        AND status = 'pending'
    ");
    $pendingStmt->execute([$programID]);

    if ((int)$pendingStmt->fetchColumn() > 0) {
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?error=pending");
        exit;
    }

    $updateStmt = $pdo->prepare("
        UPDATE scholarshipprogram
        SET status = 'completed'
        WHERE scholarship_ID = ?
    ");
    $updateStmt->execute([$programID]);

    header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?success=completed");
    exit;