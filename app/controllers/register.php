<?php
require_once __DIR__ . '/../core/dbconnection.php';

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$role = $_POST["role"] ?? null;
if (!$role) {
    die("Role is required.");
}

/* ==========================
   STUDENT REGISTRATION
   ========================== */
if ($role === "student") {

    $required = ["username", "password", "student_ID", "first_Name", "last_Name", "contact_Number", "email", "year_Level", "college_department", "program"];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            die("Missing field: " . $field);
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO student 
        (username, password, student_ID, first_Name, middle_Name, last_Name, contact_Number, email, year_Level, college_department, course)
        VALUES 
        (:username, :password, :student_ID, :firstName, :middleName, :lastName, :contact, :email, :yearLevel, :dept, :course)
    ");

    $stmt->execute([
        ":username"   => $_POST["username"],
        ":password"   => password_hash($_POST["password"], PASSWORD_DEFAULT),
        ":student_ID" => $_POST["student_ID"],
        ":firstName"  => $_POST["first_Name"],
        ":middleName" => $_POST["middle_Name"] ?? "",
        ":lastName"   => $_POST["last_Name"],
        ":contact"    => $_POST["contact_Number"],
        ":email"      => $_POST["email"],
        ":yearLevel"  => $_POST["year_Level"],
        ":dept"       => $_POST["college_department"],
        ":course"     => $_POST["program"]
    ]);

    echo "<script>
        alert('Student registration successful!');
        window.location.href = '../views/auth/login.php';
    </script>";
    exit();
}

/* ==========================
   SPONSOR REGISTRATION
   ========================== */
if ($role === "sponsor") {

    $required = ["username", "password", "first_Name", "last_Name", "contact_Number", "email", "sponsor_company"];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            die("Missing field: " . $field);
        }
    }

    // AUTO SET SPONSOR TYPE TO EXTERNAL
    $sponsorType = "external";

    $stmt = $pdo->prepare("
        INSERT INTO sponsor 
        (username, password, first_Name, middle_Name, last_Name, contact_Number, sponsor_type, sponsor_company, email)
        VALUES 
        (:username, :password, :firstName, :middleName, :lastName, :contact, :type, :company, :email)
    ");

    $stmt->execute([
        ":username"   => $_POST["username"],
        ":password"   => password_hash($_POST["password"], PASSWORD_DEFAULT),
        ":firstName"  => $_POST["first_Name"],
        ":middleName" => $_POST["middle_Name"] ?? "",
        ":lastName"   => $_POST["last_Name"],
        ":contact"    => $_POST["contact_Number"],
        ":type"       => $sponsorType,
        ":company"    => $_POST["sponsor_company"],
        ":email"      => $_POST["email"]
    ]);

    echo "<script>
        alert('Sponsor registration successful!');
        window.location.href = '../views/auth/login.php';
    </script>";
    exit();
}

echo "Invalid role.";
