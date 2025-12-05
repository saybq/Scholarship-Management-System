<?php
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
        }

    // Check correct role
    if ($_SESSION["role"] !== "reviewer") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
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
            <!-- PAGE TITLE -->
            <div class="mb-6">
                <h1 class="text-3xl font-semibold text-gray-800">Dashboard</h1>
                <p class="text-gray-500">Welcome back, Reviewer</p>
            </div>

            <!-- TOP STAT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <!-- Assigned Programs -->
                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Assigned Programs</p>
                        <div class="mt-2 text-3xl font-bold">5</div>
                        <p class="text-gray-400 text-xs mt-1">Programs to review</p>
                    </div>
                    <span class="material-symbols-outlined text-yellow-600 text-sm">folder_open</span>
                </div>

                <!-- Pending Reviews -->
                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Pending Reviews</p>
                        <div class="mt-2 text-3xl font-bold">12</div>
                        <p class="text-gray-400 text-xs mt-1">Awaiting evaluation</p>
                    </div>
                    <span class="material-symbols-outlined text-blue-500 text-sm">schedule</span>
                </div>

                <!-- Approved -->
                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Approved</p>
                        <div class="mt-2 text-3xl font-bold">38</div>
                        <p class="text-gray-400 text-xs mt-1">Applications approved</p>
                    </div>
                    <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                </div>

                <!-- Rejected -->
                <div class="bg-white p-5 rounded-xl shadow flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">Rejected</p>
                        <div class="mt-2 text-3xl font-bold">10</div>
                        <p class="text-gray-400 text-xs mt-1">Applications rejected</p>
                    </div>
                    <span class="material-symbols-outlined text-red-500 text-sm">cancel</span>
                </div>

            </div>

            <!-- BOTTOM GRID SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Pending Applications -->
                <div class="bg-white p-5 rounded-xl shadow">
                    <h2 class="font-semibold text-lg">Pending Applications</h2>
                    <p class="text-gray-500 text-sm mb-4">Applications awaiting your review</p>

                    <!-- Applicant Item -->
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center mb-3">
                        <div>
                            <p class="font-semibold">Juan Dela Cruz</p>
                            <p class="text-gray-500 text-sm">DOST Scholarship</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Pending</span>
                            <p class="text-gray-400 text-xs">Nov 25, 2025</p>
                        </div>
                    </div>

                    <!-- Applicant Item -->
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center mb-3">
                        <div>
                            <p class="font-semibold">Maria Santos</p>
                            <p class="text-gray-500 text-sm">Engineering Excellence Grant</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Pending</span>
                            <p class="text-gray-400 text-xs">Nov 24, 2025</p>
                        </div>
                    </div>

                    <!-- Applicant Item -->
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-semibold">Pedro Reyes</p>
                            <p class="text-gray-500 text-sm">DOST Scholarship</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Pending</span>
                            <p class="text-gray-400 text-xs">Nov 23, 2025</p>
                        </div>
                    </div>
                </div>

                <!-- Review Statistics -->
                <div class="bg-white p-5 rounded-xl shadow">

                    <h2 class="font-semibold text-lg">Review Statistics</h2>
                    <p class="text-gray-500 text-sm mb-4">Your review performance this month</p>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-yellow-50 p-6 rounded-xl text-center">
                            <p class="text-4xl font-bold text-yellow-600">48</p>
                            <p class="text-gray-600 text-sm mt-2">Total Reviewed</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-xl text-center">
                            <p class="text-4xl font-bold text-green-600">79%</p>
                            <p class="text-gray-600 text-sm mt-2">Approval Rate</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl flex items-center gap-4">
                        <span class="material-symbols-outlined text-yellow-500 text-4xl">schedule</span>
                        <div>
                            <p class="font-semibold text-gray-700">Average Review Time</p>
                            <p class="text-gray-500 text-sm">2.3 days per application</p>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>

</body>
</html>
