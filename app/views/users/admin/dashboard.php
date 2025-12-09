<?php
    session_start();
    require_once __DIR__ . "../../../../core/dbconnection.php";
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
        }

        // Check correct role
        if ($_SESSION["role"] !== "admin") {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
    }

    //--------------TOP STATS------------------------
    // Pending scholarships
    $pendingScholarships = $pdo->query("
        SELECT COUNT(scholarship_ID) 
        FROM scholarshipprogram 
        WHERE status = 'pending'
    ")->fetchColumn();

    // Active scholarships
    $activeScholarships = $pdo->query("
        SELECT COUNT(scholarship_ID) 
        FROM scholarshipprogram 
        WHERE status = 'approved'
    ")->fetchColumn();

    // Total students
    $totalStudents = $pdo->query("
        SELECT COUNT(ID) 
        FROM student
    ")->fetchColumn();

    // Total sponsors
    $totalSponsors = $pdo->query("
        SELECT COUNT(sponsor_ID) 
        FROM sponsor
    ")->fetchColumn();

    //--------------RECENT PENDING------------------------
    $recentPending = $pdo->prepare("
        SELECT 
            sp.scholarship_Name,
            s.sponsor_company,
            sp.dateof_creation
        FROM scholarshipprogram sp
        INNER JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
        WHERE sp.status = 'pending'
        ORDER BY sp.dateof_creation DESC
        LIMIT 5
    ");
    $recentPending->execute();
    $pendingList = $recentPending->fetchAll(PDO::FETCH_ASSOC);


    //--------------SYSTEM STATISTICS------------------------
    // Total Applications
    $totalApplications = $pdo->query("
        SELECT COUNT(application_ID) 
        FROM application
    ")->fetchColumn();

    // Total Funds (sum of scholarship amounts for all applications)
    $totalFunds = $pdo->query("
        SELECT SUM(Amount) AS totalFunds
        FROM scholarshipprogram
        WHERE status = 'approved'
    ")->fetchColumn();

    $totalFunds = $totalFunds ?? 0;

    // Approval Rate
    $approvedCount = $pdo->query("
        SELECT COUNT(application_ID) 
        FROM application
        WHERE status = 'approved'
    ")->fetchColumn();

    $approvalRate = ($totalApplications > 0) 
        ? round(($approvedCount / $totalApplications) * 100)
        : 0;
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

        <?php include __DIR__ . '/../../assets/components/adminSidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
                        <!-- Page Title -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">Dashboard</h1>
                <p class="text-gray-500 text-sm">System Administration Overview</p>
            </div>

            <!-- Top Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">

                <!-- Pending Scholarships -->
                <div class="bg-white p-5 rounded-xl shadow border">
                    <p class="text-sm font-medium text-gray-600 flex items-center gap-1">
                        Pending Scholarships
                        <span class="material-symbols-outlined text-yellow-500 text-sm">schedule</span>
                    </p>
                    <p class="text-3xl font-bold mt-2"><?= $pendingScholarships ?></p>
                    <p class="text-gray-400 text-xs">Awaiting approval</p>
                </div>

                <!-- Active Scholarships -->
                <div class="bg-white p-5 rounded-xl shadow border">
                    <p class="text-sm font-medium text-gray-600 flex items-center gap-1">
                        Active Scholarships
                        <span class="material-symbols-outlined text-green-600 text-sm">workspace_premium</span>
                    </p>
                    <p class="text-3xl font-bold mt-2"><?= $activeScholarships ?></p>
                    <p class="text-gray-400 text-xs">Total programs</p>
                </div>

                <!-- Total Students -->
                <div class="bg-white p-5 rounded-xl shadow border">
                    <p class="text-sm font-medium text-gray-600 flex items-center gap-1">
                        Total Students
                        <span class="material-symbols-outlined text-blue-600 text-sm">school</span>
                    </p>
                    <p class="text-3xl font-bold mt-2"><?= $totalStudents ?></p>
                    <p class="text-gray-400 text-xs">Registered students</p>
                </div>

                <!-- Total Sponsors -->
                <div class="bg-white p-5 rounded-xl shadow border">
                    <p class="text-sm font-medium text-gray-600 flex items-center gap-1">
                        Total Sponsors
                        <span class="material-symbols-outlined text-emerald-600 text-sm">groups</span>
                    </p>
                    <p class="text-3xl font-bold mt-2"><?= $totalSponsors ?></p>
                    <p class="text-gray-400 text-xs">Active sponsors</p>
                </div>

            </div>

            <!-- Bottom Section: Left Recent Pending + Right System Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Recent Pending Scholarships -->
                <div class="bg-white p-6 rounded-xl shadow border lg:col-span-2">
                    <h3 class="font-semibold text-lg">Recent Pending Scholarships</h3>
                    <p class="text-gray-500 text-sm mb-4">New scholarship proposals from sponsors</p>

                    <div class="space-y-3">

                        <?php if (count($pendingList) > 0): ?>
                            <?php foreach ($pendingList as $row): ?>
                                <div class="p-4 bg-gray-50 rounded-lg border flex justify-between">
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($row['scholarship_Name']) ?></p>
                                        <p class="text-gray-500 text-sm"><?= htmlspecialchars($row['sponsor_company']) ?></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded">Pending</span>
                                        <p class="text-gray-400 text-xs mt-1">
                                            <?= date("M d, Y", strtotime($row['dateof_creation'])) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm text-center font-semibold">No pending scholarship programs.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- System Statistics -->
                <div class="bg-white p-6 rounded-xl shadow border">
                    <h3 class="font-semibold text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-600 text-sm">trending_up</span>
                        System Statistics
                    </h3>

                    <div class="mt-4 space-y-4">

                        <div class="bg-red-50 border p-5 rounded-xl text-center">
                            <p class="text-3xl font-bold text-red-600"><?= number_format($totalApplications) ?></p>
                            <p class="text-gray-600 text-sm">Total Applications</p>
                        </div>

                        <div class="bg-green-50 border p-5 rounded-xl text-center">
                            <p class="text-3xl font-bold text-green-600">₱<?= number_format($totalFunds, 2) ?></p>
                            <p class="text-gray-600 text-sm">Total Funds of Scholarship</p>
                        </div>

                        <div class="bg-amber-50 border p-5 rounded-xl text-center">
                            <p class="text-3xl font-bold text-amber-600"><?= $approvalRate ?>%</p>
                            <p class="text-gray-600 text-sm">Approval Rate</p>
                        </div>

                    </div>
                </div>

            </div>

            
        </main>

    </div>
</body>
</html>
