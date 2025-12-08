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

        // default empty values
        $middle = "N/A";
        $last = "N/A";
        $contact = "N/A";
        $password = password_hash("1234", PASSWORD_DEFAULT);

        if ($role === "admin_officer") {
            $sql = "INSERT INTO admissionofficer 
                    (username, password, first_Name, middle_Name, last_Name, email)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $middle, $last, $email]);
        }

        if ($role === "admin_staff") {
            $sql = "INSERT INTO admissionstaff 
                    (username, password, first_Name,  last_Name, department, email)
                    VALUES (?, ?, ?, ?, 'N/A', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $last, $email]);
        }

        if ($role === "sponsor") {
            $sql = "INSERT INTO sponsor 
                    (username, password, first_Name, last_Name, middle_Name, email, contact_Number, sponsor_company, sponsor_type)
                    VALUES (?, ?, ?, ?, 'N/A', ?, ?, 'N/A', 'external')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $firstname, $last, $email, $contact]);
        }

        if ($role === "student") {
            $currentYear = date("Y");

            $sql = "INSERT INTO student 
                    (username, password, student_ID, first_Name, middle_Name, last_Name, contact_Number, email, year_Level, college_department, course)
                    VALUES (?, ?, ?, ?, 'N/A', ?, 'N/A', ?, 'N/A', 'N/A', 'N/A')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $currentYear, $firstname, $last, $email]);
        }

        header("Location: /Scholarship/app/views/users/admin/manageUser.php?created=1");
        exit;
    }


