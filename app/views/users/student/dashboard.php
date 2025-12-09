<?php
    session_start();
    require_once __DIR__ . "../../../../core/dbconnection.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "student") {
        header("Location: ../auth/login.php");
        exit;
    }

    $studentID = $_SESSION["user_id"];

    //UPCOMING DEADLINES
    $stmtDeadlines = $pdo->prepare("
        SELECT scholarship_Name, deadline
        FROM scholarshipprogram
        WHERE status = 'approved'
        AND deadline >= CURDATE()
        ORDER BY deadline ASC
        LIMIT 5
    ");
    $stmtDeadlines->execute();
    $deadlines = $stmtDeadlines->fetchAll(PDO::FETCH_ASSOC);

    //RECENT ACTIVITY
    $stmtActivity = $pdo->prepare("
        SELECT a.status, a.date_applied, s.scholarship_Name
        FROM application a
        INNER JOIN scholarshipprogram s 
            ON a.scholarship_ID = s.scholarship_ID
        WHERE a.student_ID = ?
        ORDER BY a.date_applied DESC
        LIMIT 5
    ");
    $stmtActivity->execute([$studentID]);
    $activities = $stmtActivity->fetchAll(PDO::FETCH_ASSOC);

    //TOP STATS------

    // AVAILABLE SCHOLARSHIPS
    $stmtAvailable = $pdo->prepare("
        SELECT COUNT(scholarship_ID)
        FROM scholarshipprogram
        WHERE status = 'approved'
    ");
    $stmtAvailable->execute();
    $available = $stmtAvailable->fetchColumn();

    // PENDING
    $stmtPending = $pdo->prepare("
        SELECT COUNT(application_ID)
        FROM application
        WHERE student_ID = ? AND status = 'pending'
    ");
    $stmtPending->execute([$studentID]);
    $pending = $stmtPending->fetchColumn();

    // APPROVED
    $stmtApproved = $pdo->prepare("
        SELECT COUNT(application_ID)
        FROM application
        WHERE student_ID = ? AND status = 'approved'
    ");
    $stmtApproved->execute([$studentID]);
    $approved = $stmtApproved->fetchColumn();

    // REJECTED
    $stmtRejected = $pdo->prepare("
        SELECT COUNT(application_ID)
        FROM application
        WHERE student_ID = ? AND status = 'rejected'
    ");
    $stmtRejected->execute([$studentID]);
    $rejected = $stmtRejected->fetchColumn();

    //STATISTICS SECTION-------

    // TOTAL SCHOLARSHIPS APPLIED
    $stmtSubmitted = $pdo->prepare("
        SELECT COUNT(application_ID)
        FROM application
        WHERE student_ID = ?
    ");
    $stmtSubmitted->execute([$studentID]);
    $submitted = $stmtSubmitted->fetchColumn();

    // TOTAL AWARDS (SUM OF AMOUNT OF APPROVED SCHOLARSHIPS)
    $stmtAwards = $pdo->prepare("
        SELECT SUM(sp.Amount)
        FROM application a
        INNER JOIN scholarshipprogram sp
            ON a.scholarship_ID = sp.scholarship_ID
        WHERE a.student_ID = ? AND a.status = 'approved'
    ");
    $stmtAwards->execute([$studentID]);
    $totalAwards = $stmtAwards->fetchColumn() ?? 0;

    $successRate = ($submitted > 0) ? round(($approved / $submitted) * 100) : 0;
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
<body class="bg-gray-50">

<div class="flex min-h-screen">

    <?php include __DIR__ . '/../../assets/components/studentSidebar.php'; ?>

    <main class="flex-1 p-6">

        <!-- HEADER -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Dashboard</h2>
            <p class="text-gray-500 text-sm">Welcome back, Student</p>
        </div>

        <!-- TOP CARDS -->
        <div class="grid grid-cols-4 gap-4 mb-6">

            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Available Scholarships</p>
                <p class="text-3xl font-bold mt-2"><?= $available ?></p>
                <p class="text-xs text-gray-500">Open for applications</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Pending Review</p>
                <p class="text-3xl font-bold mt-2"><?= $pending ?></p>
                <p class="text-xs text-gray-500">Awaiting decision</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Approved</p>
                <p class="text-3xl font-bold mt-2"><?= $approved ?></p>
                <p class="text-xs text-gray-500">Scholarships awarded</p>
            </div>

            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Rejected Applications</p>
                <p class="text-3xl font-bold mt-2"><?= $rejected ?></p>
                <p class="text-xs text-gray-500">Applications not approved</p>
            </div>

        </div>

        <!-- SECOND ROW (2 COLUMNS) -->
        <div class="grid grid-cols-2 gap-4 mb-6">

            <!-- UPCOMING DEADLINES -->
            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Upcoming Deadlines</p>
                <p class="text-gray-500 text-sm mb-4">Scholarships closing soon</p>

                <div class="space-y-3">
                    <?php if (empty($deadlines)): ?>
                        <p class="text-sm text-gray-500">No upcoming deadlines.</p>
                    <?php else: ?>
                        <?php foreach ($deadlines as $d): 
                            $daysLeft = ceil((strtotime($d['deadline']) - time()) / 86400);
                        ?>
                            <div class="flex items-center justify-between border p-3 rounded-lg">
                                <div>
                                    <p class="font-semibold"><?= htmlspecialchars($d['scholarship_Name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= $d['deadline'] ?></p>
                                </div>
                                <span class="text-xs text-orange-500"><?= $daysLeft ?> days left</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <div class="bg-white border rounded-xl p-4 shadow">
                <p class="font-semibold">Recent Activity</p>
                <p class="text-gray-500 text-sm mb-4">Your latest application updates</p>

                <div class="space-y-3">
                    <?php if (empty($activities)): ?>
                        <p class="text-sm text-gray-500">No recent activity.</p>
                    <?php else: ?>
                        <?php foreach ($activities as $act): ?>
                            <div class="flex justify-between items-start border p-3 rounded-lg">
                                <div>
                                    <p class="font-semibold"><?= htmlspecialchars($act['scholarship_Name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= $act['date_applied'] ?></p>
                                </div>

                                <?php 
                                    $color = [
                                        'pending' => 'text-yellow-600 bg-yellow-100',
                                        'approved' => 'text-green-600 bg-green-100',
                                        'rejected' => 'text-red-600 bg-red-100'
                                    ][$act['status']];
                                ?>

                                <div class="text-xs <?= $color ?> px-2 py-1 rounded">
                                    <?= $act['status'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- STATISTICS (FULL WIDTH) -->
        <div class="bg-white border rounded-xl p-4 shadow">

            <p class="font-semibold mb-4">Application Statistics</p>

            <div class="grid grid-cols-3 gap-4">

                <!-- SUCCESS RATE -->
                <div class="bg-blue-50 text-blue-700 rounded-xl p-6 flex flex-col items-center">
                    <p class="text-3xl font-bold"><?= $successRate ?>%</p>
                    <p class="text-xs text-blue-700">Success Rate</p>
                </div>

                <!-- TOTAL AWARDS -->
                <div class="bg-green-50 text-green-700 rounded-xl p-6 flex flex-col items-center">
                    <p class="text-3xl font-bold">₱<?= number_format($totalAwards) ?></p>
                    <p class="text-xs text-green-700">Total Awards</p>
                </div>

                <!-- SCHOLARSHIPS APPLIED -->
                <div class="bg-yellow-50 text-yellow-700 rounded-xl p-6 flex flex-col items-center">
                    <p class="text-3xl font-bold"><?= $submitted ?></p>
                    <p class="text-xs text-yellow-700">Scholarships Applied</p>
                </div>

            </div>
        </div>

    </main>
</div>

</body>
</html>
