<?php
    session_start();
    require_once __DIR__ . "../../../../core/dbconnection.php";
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
        }
    if ($_SESSION["role"] !== "reviewer") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    $reviewer_ID = $_SESSION["user_id"];

    $stmt = $pdo->prepare("
    SELECT COUNT(scholarship_ID)
    FROM scholarshipprogram
    WHERE reviewer_ID = ?");
    $stmt->execute([$reviewer_ID]);
    $assignedPrograms = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
    SELECT COUNT(a.application_ID)
    FROM application a
    JOIN scholarshipprogram s ON a.scholarship_ID = s.scholarship_ID
    WHERE s.reviewer_ID = ?
    AND a.status = 'pending'");
    $stmt->execute([$reviewer_ID]);
    $pendingReviews = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
    SELECT COUNT(a.application_ID)
    FROM application a
    JOIN scholarshipprogram s ON a.scholarship_ID = s.scholarship_ID
    WHERE s.reviewer_ID = ?
    AND a.status = 'approved'");
    $stmt->execute([$reviewer_ID]);
    $approved = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
    SELECT COUNT(a.application_ID)
    FROM application a
    JOIN scholarshipprogram s ON a.scholarship_ID = s.scholarship_ID
    WHERE s.reviewer_ID = ?
    AND a.status = 'rejected'");
    $stmt->execute([$reviewer_ID]);
    $rejected = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
    SELECT 
        a.application_ID,
        a.date_applied,
        s.scholarship_Name,
        st.first_Name,
        st.middle_Name,
        st.last_Name
    FROM application a
    JOIN scholarshipprogram s 
        ON a.scholarship_ID = s.scholarship_ID
    JOIN student st
        ON a.student_ID = st.ID
    WHERE s.reviewer_ID = ?
    AND s.status = ?
    AND a.status = 'pending'
    ORDER BY a.date_applied DESC 
    LIMIT 3");
    $stmt->execute([$reviewer_ID, "approved"]);
    $pendingList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalReviewed = $approved + $rejected;

    $approvalRate = 0;
    if ($totalReviewed > 0) {
        $approvalRate = round(($approved / $totalReviewed) * 100);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>
<body class="bg-gray-50 ">

    <div class="flex min-h-screen">

        <?php include __DIR__ . '/../../assets/components/reviewerSidebar.php'; ?>
        
        <main class="flex-1 p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-semibold text-gray-800">Dashboard</h1>
                <p class="text-gray-500">Welcome back, Reviewer</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Assigned Programs</p>
                        <div class="mt-2 text-3xl font-bold"><?= $assignedPrograms ?></div>
                        <p class="text-gray-400 text-xs mt-1">Programs to review</p>
                    </div>
                    <span class="material-symbols-outlined text-yellow-600 text-sm">folder_open</span>
                </div>

                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Reviews</p>
                        <div class="mt-2 text-3xl font-bold"><?= $pendingReviews ?></div>
                        <p class="text-gray-400 text-xs mt-1">Awaiting evaluation</p>
                    </div>
                    <span class="material-symbols-outlined text-blue-500 text-sm">schedule</span>
                </div>

                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Approved</p>
                        <div class="mt-2 text-3xl font-bold"><?= $approved ?></div>
                        <p class="text-gray-400 text-xs mt-1">Applications approved</p>
                    </div>
                    <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                </div>

                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Rejected</p>
                        <div class="mt-2 text-3xl font-bold"><?= $rejected ?></div>
                        <p class="text-gray-400 text-xs mt-1">Applications rejected</p>
                    </div>
                    <span class="material-symbols-outlined text-red-500 text-sm">cancel</span>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Pending Applications -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <h2 class="font-semibold text-lg">Pending Applications</h2>
                    <p class="text-gray-500 text-sm mb-4">Applications awaiting your review</p>

                        <?php foreach ($pendingList as $p): ?>
                        <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center mb-3">
                            <div>
                                <p class="font-semibold">
                                    <?= htmlspecialchars($p['first_Name'] . ' ' . $p['middle_Name'] . ' ' . $p['last_Name']) ?>
                                </p>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($p['scholarship_Name']) ?></p>
                            </div>
                            <div class="text-right">
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Pending</span>
                                <p class="text-gray-400 text-xs"><?= htmlspecialchars($p['date_applied']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">

                    <h2 class="font-semibold text-lg">Review Statistics</h2>
                    <p class="text-gray-500 text-sm mb-4">Your review performance this month</p>

                    <div class="bg-green-50 p-6 rounded-xl text-center">
                        <p class="text-4xl font-bold text-green-600"><?= $approvalRate ?>%</p>
                        <p class="text-gray-600 text-sm mt-2">Approval Rate</p>
                    </div>

                </div>
            </div>

        </main>
    </div>

</body>
</html>
