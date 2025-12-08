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

$action = $_GET["action"] ?? null;
$program = $_GET["program"] ?? null;

if ($action === "approve") {

    $id = $_GET["id"] ?? null;

    if (!$id) {
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?error=missing_id");
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE application 
            SET status = 'approved', note = 'Please wait for next instructions'
            WHERE application_ID = ?
        ");
        $stmt->execute([$id]);

        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?program=$program&success=approved");
        exit;

    } catch (PDOException $e) {
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?program=$program&error=db_approve");
        exit;
    }
}

if ($action === "reject" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["application_ID"] ?? null;
    $reason = $_POST["reason"] ?? null;

    if (!$id || !$reason) {
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?program=$program&error=missing_fields");
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE application 
            SET status = 'rejected', note = ?
            WHERE application_ID = ?
        ");
        $stmt->execute([$reason, $id]);

        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?program=$program&success=rejected");
        exit;

    } catch (PDOException $e) {
        header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php?program=$program&error=db_reject");
        exit;
    }
}

header("Location: /Scholarship/app/views/users/reviewer/assignedPrograms.php");
exit;
