<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "student") {
        header("Location: ../auth/login.php");
        exit;
    }

    if ($_SESSION["role"] !== "student") {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
        }
    
    $student_ID = $_SESSION["user_id"];
    $scholarship_ID = $_POST["scholarship_ID"] ?? null;
    $requirements_link = $_POST["requirements_link"] ?? null;

    // ---------------------
    // VALIDATION
    // ---------------------
    if (empty($scholarship_ID) || empty($requirements_link)) {
        header("Location: /Scholarship/app/views/users/student/scholarships.php?error=empty");
        exit;
    }

    // // OPTIONAL: prevent duplicate applications
    // $check = $pdo->prepare("SELECT COUNT(*) FROM application WHERE student_ID = ? AND scholarship_ID = ?");
    // $check->execute([$student_ID, $scholarship_ID]);
    // if ($check->fetchColumn() > 0) {
    //     header("Location: /Scholarship/app/views/users/student/scholarships.php?error=already_applied");
    //     exit;
    // }

    try {
        $sql = "INSERT INTO application (student_ID, scholarship_ID, requirements_link, date_applied, status)
                VALUES (?, ?, ?, CURDATE(), 'pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_ID, $scholarship_ID, $requirements_link]);

        header("Location: /Scholarship/app/views/users/student/scholarships.php?success=1");
        exit;

    } catch (Exception $e) {
        header("Location: /Scholarship/app/views/users/student/scholarships.php?error=1");
        exit;
    }
