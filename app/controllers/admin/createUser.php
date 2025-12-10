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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Scholarship/app/views/users/admin/manageUser.php");
        exit;
    }

    if (isset($_POST['createUser'])) {
        $role = $_POST['role'];
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];

        $password = password_hash("1234", PASSWORD_DEFAULT);

        $table = '';
        switch($role) {
            case 'admin_officer':
                $table = 'admissionofficer';
                break;
            case 'admin_staff':
                $table = 'admissionstaff';
                break;
            case 'sponsor':
                $table = 'sponsor';
                break;
            case 'student':
                $table = 'student';
                break;
            default:
                header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=invalidrole");
                exit;
        }

        $stmt = $pdo->prepare("SELECT 1 FROM $table WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=emailtaken");
            exit;
        }

        $stmt = $pdo->prepare("SELECT 1 FROM $table WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            header("Location: /Scholarship/app/views/users/admin/manageUser.php?error=usernametaken");
            exit;
        }

        if ($role === "admin_officer") {
            $sql = "INSERT INTO admissionofficer 
                    (username, password, first_Name, middle_Name, last_Name, email)
                    VALUES (?, ?, ?, 'N/A', 'N/A', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $email]);
        }

        if ($role === "admin_staff") {
            $sql = "INSERT INTO admissionstaff 
                    (username, password, first_Name, last_Name, department, email)
                    VALUES (?, ?, ?, 'N/A', 'N/A', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $email]);
        }

        if ($role === "sponsor") {
            $sql = "INSERT INTO sponsor 
                    (username, password, first_Name, last_Name, middle_Name, email, contact_Number, sponsor_company, sponsor_type)
                    VALUES (?, ?, ?, 'N/A', 'N/A', ?, ?, 'N/A', 'external')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $email, $contact]);
        }

        if ($role === "student") {
            $currentTime = (int) time() % 10000000;
            $sql = "INSERT INTO student 
                    (username, password, student_ID, first_Name, middle_Name, last_Name, contact_Number, email, year_Level, college_department, course)
                    VALUES (?, ?, ?, ?, 'N/A', ?, 'N/A', ?, 'N/A', 'N/A', 'N/A')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $currentTime, $firstname, $last, $email]);
        }

            header("Location: /Scholarship/app/views/users/admin/manageUser.php?created=1");
            exit;
    }



