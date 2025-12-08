<?php
    session_start();
    require_once __DIR__ . "/../../core/dbconnection.php";

    if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== "student") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: /Scholarship/app/views/users/student/profile.php");
        exit;
    }

    $student_id = $_SESSION["user_id"];

    function clean($v) {
        return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
    }

    $username        = clean($_POST['username']);
    $studentID       = clean($_POST['student_ID']);
    $firstname       = clean($_POST['first_Name']);
    $middlename      = clean($_POST['middle_Name'] ?? null);
    $lastname        = clean($_POST['last_Name']);
    $email           = clean($_POST['email']);
    $contact         = clean($_POST['contact_Number']);
    $year            = clean($_POST['year_Level']);
    $department      = clean($_POST['college_department']);
    $course          = clean($_POST['course']);
    $password        = $_POST['password']; 

    if (
        $username === "" || $studentID === "" || $firstname === "" ||
        $lastname === "" || $email === "" || $contact === "" ||
        $year === "" || $department === "" || $course === ""
    ) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Missing required fields");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Invalid email");
        exit;
    }

    if (!preg_match("/^[0-9]{11}$/", $contact)) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Invalid contact number (11 digits required)");
        exit;
    }

    $stmt = $pdo->prepare("SELECT ID FROM student WHERE username = ? AND ID != ?");
    $stmt->execute([$username, $student_id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Username already taken");
        exit;
    }

    $stmt = $pdo->prepare("SELECT ID FROM student WHERE email = ? AND ID != ?");
    $stmt->execute([$email, $student_id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Email already used");
        exit;
    }

    $stmt = $pdo->prepare("SELECT ID FROM student WHERE student_ID = ? AND ID != ?");
    $stmt->execute([$studentID, $student_id]);
    if ($stmt->fetch()) {
        header("Location: /Scholarship/app/views/users/student/profile.php?error=Student ID already used");
        exit;
    }

    if (!empty($password)) {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE student SET
                    username = ?,
                    password = ?,
                    student_ID = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    contact_Number = ?,
                    email = ?,
                    year_Level = ?,
                    college_department = ?,
                    course = ?
                WHERE ID = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $hashed,
            $studentID,
            $firstname,
            $middlename,
            $lastname,
            $contact,
            $email,
            $year,
            $department,
            $course,
            $student_id
        ]);

    } else {

        $sql = "UPDATE student SET
                    username = ?,
                    student_ID = ?,
                    first_Name = ?,
                    middle_Name = ?,
                    last_Name = ?,
                    contact_Number = ?,
                    email = ?,
                    year_Level = ?,
                    college_department = ?,
                    course = ?
                WHERE ID = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $studentID,
            $firstname,
            $middlename,
            $lastname,
            $contact,
            $email,
            $year,
            $department,
            $course,
            $student_id
        ]);
    }

    header("Location: /Scholarship/app/views/users/student/profile.php?success=1");
    exit;
