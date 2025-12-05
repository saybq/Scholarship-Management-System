<?php
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "student") {
        header("Location: ../auth/login.php");
        exit;
    }

    if ($_SESSION["role"] !== "student") {
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

        <?php include __DIR__ . '/../../assets/components/studentSidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- PAGE HEADER -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold">Dashboard</h2>
                <p class="text-gray-500 text-sm">Welcome back, Student</p>
            </div>

            <!-- TOP CARDS -->
            <div class="grid grid-cols-4 gap-4 mb-6">

                <!-- Available Scholarships -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Available Scholarships</p>
                    <p class="text-3xl font-bold mt-2">12</p>
                    <p class="text-xs text-gray-500">Open for applications</p>
                </div>

                <!-- Applications Submitted -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Applications Submitted</p>
                    <p class="text-3xl font-bold mt-2">3</p>
                    <p class="text-xs text-gray-500">Total applications</p>
                </div>

                <!-- Pending Review -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Pending Review</p>
                    <p class="text-3xl font-bold mt-2">2</p>
                    <p class="text-xs text-gray-500">Awaiting decision</p>
                </div>

                <!-- Approved -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Approved</p>
                    <p class="text-3xl font-bold mt-2">1</p>
                    <p class="text-xs text-gray-500">Scholarships awarded</p>
                </div>
            </div>

            <!-- SECOND ROW -->
            <div class="grid grid-cols-2 gap-4 mb-6">

                <!-- Upcoming Deadlines -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Upcoming Deadlines</p>
                    <p class="text-gray-500 text-sm mb-4">Scholarships closing soon</p>

                    <div class="space-y-3">

                        <!-- Item -->
                        <div class="flex items-center justify-between border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">DOST Scholarship Program</p>
                                <p class="text-xs text-gray-500">Dec 15, 2025</p>
                            </div>
                            <span class="text-xs text-orange-500">18 days left</span>
                        </div>

                        <div class="flex items-center justify-between border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">SM Foundation Scholarship</p>
                                <p class="text-xs text-gray-500">Dec 20, 2025</p>
                            </div>
                            <span class="text-xs text-orange-500">23 days left</span>
                        </div>

                        <div class="flex items-center justify-between border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">Ayala Foundation Grant</p>
                                <p class="text-xs text-gray-500">Jan 5, 2026</p>
                            </div>
                            <span class="text-xs text-orange-500">39 days left</span>
                        </div>

                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white border rounded-xl p-4 shadow">
                    <p class="font-semibold">Recent Activity</p>
                    <p class="text-gray-500 text-sm mb-4">Your latest application updates</p>

                    <div class="space-y-3">

                        <!-- Activity Item -->
                        <div class="flex justify-between items-start border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">Application submitted</p>
                                <p class="text-xs text-gray-500">DOST Scholarship</p>
                            </div>
                            <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded">pending</div>
                        </div>

                        <div class="flex justify-between items-start border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">Application approved</p>
                                <p class="text-xs text-gray-500">Merit Scholarship</p>
                            </div>
                            <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">approved</div>
                        </div>

                        <div class="flex justify-between items-start border p-3 rounded-lg">
                            <div>
                                <p class="font-semibold">Application submitted</p>
                                <p class="text-xs text-gray-500">SM Foundation</p>
                            </div>
                            <div class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded">pending</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- STATISTICS -->
            <div class="bg-white border rounded-xl p-4 shadow">

                <p class="font-semibold mb-4">Application Statistics</p>

                <div class="grid grid-cols-3 gap-4">

                    <!-- Success Rate -->
                    <div class="bg-blue-50 text-blue-700 rounded-xl p-6 flex flex-col items-center">
                        <p class="text-3xl font-bold">67%</p>
                        <p class="text-xs text-blue-700">Success Rate</p>
                    </div>

                    <!-- Total Awards -->
                    <div class="bg-green-50 text-green-700 rounded-xl p-6 flex flex-col items-center">
                        <p class="text-3xl font-bold">₱50,000</p>
                        <p class="text-xs text-green-700">Total Awards</p>
                    </div>

                    <!-- Scholarships Applied -->
                    <div class="bg-yellow-50 text-yellow-700 rounded-xl p-6 flex flex-col items-center">
                        <p class="text-3xl font-bold">5</p>
                        <p class="text-xs text-yellow-700">Scholarships Applied</p>
                    </div>

                </div>
            </div>

            
        </main>
    </div>

</body>
</html>
