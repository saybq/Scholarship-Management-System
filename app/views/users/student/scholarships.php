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

<body class="bg-gray-50">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../assets/components/studentSidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-6">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Available Scholarships</h1>
            <p class="text-gray-500 text-sm">Browse and apply to scholarships</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6 relative w-72">
                        <div class="absolute inset-0 rounded-lg border shadow bg-white pointer-events-none"></div>

                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                            search
                        </span>

                        <input
                            type="text"
                            placeholder="Search scholarships..."
                            class="relative w-full rounded-lg p-2 pl-10 pr-4 text-sm bg-transparent focus:ring focus:ring-blue-200 border-none z-20"
                        >
                    </div>

        <!-- Scholarship List (3 ITEMS ONLY) -->
        <div class="space-y-4">

            <!-- ITEM 1 -->
            <div class="bg-white p-5 rounded-lg shadow flex justify-between items-center border">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500">school</span>

                    <div>
                        <h2 class="font-semibold text-gray-800">DOST Scholarship Program</h2>
                        <p class="text-gray-500 text-sm mb-2">Department of Science and Technology</p>

                        <p class="text-gray-600 text-sm mb-2">
                            A merit-based scholarship for students pursuing science and technology courses.
                        </p>

                        <div class="flex gap-4 text-xs text-gray-600">
                            <span class="text-green-600">₱40,000/year</span>
                            <span class="text-orange-600">Deadline: Dec 15, 2025</span>
                            <span>4 requirements</span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('DOST Scholarship Program')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Apply Now
                </button>
            </div>

            <!-- ITEM 2 -->
            <div class="bg-white p-5 rounded-lg shadow flex justify-between items-center border">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500">school</span>

                    <div>
                        <h2 class="font-semibold text-gray-800">SM Foundation Scholarship</h2>
                        <p class="text-gray-500 text-sm mb-2">SM Foundation</p>

                        <p class="text-gray-600 text-sm mb-2">
                            Supporting deserving students from low-income families to achieve their dreams.
                        </p>

                        <div class="flex gap-4 text-xs text-gray-600">
                            <span class="text-green-600">₱30,000/year</span>
                            <span class="text-orange-600">Deadline: Dec 20, 2025</span>
                            <span>3 requirements</span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('SM Foundation Scholarship')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Apply Now
                </button>
            </div>

            <!-- ITEM 3 -->
            <div class="bg-white p-5 rounded-lg shadow flex justify-between items-center border">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-500">school</span>

                    <div>
                        <h2 class="font-semibold text-gray-800">Ayala Foundation Grant</h2>
                        <p class="text-gray-500 text-sm mb-2">Ayala Foundation</p>

                        <p class="text-gray-600 text-sm mb-2">
                            Empowering future leaders through quality education support.
                        </p>

                        <div class="flex gap-4 text-xs text-gray-600">
                            <span class="text-green-600">₱50,000/year</span>
                            <span class="text-orange-600">Deadline: Jan 5, 2026</span>
                            <span>3 requirements</span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Ayala Foundation Grant')"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Apply Now
                </button>
            </div>

        </div>

        <!-- Modal Background -->
        <div id="applyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 
            flex items-center justify-center">

            <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">

                <div class="flex justify-between items-center mb-4">
                    <h2 id="modalTitle" class="text-lg font-semibold text-gray-800"></h2>
                    <button onclick="closeModal()" class="material-symbols-outlined text-gray-500 cursor-pointer">
                        close
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-3">
                    Please review the requirements and submit your documents.
                </p>

                <h3 class="font-medium text-gray-800 mb-2">Requirements:</h3>

                <ul class="list-disc ml-6 text-sm text-gray-700 space-y-1 mb-4">
                    <li>GWA of 85% or higher</li>
                    <li>Enrolled in qualified courses</li>
                    <li>Filipino citizen</li>
                    <li>Family income below ₱300,000/year</li>
                </ul>

                <label class="text-sm font-medium text-gray-700">Google Drive Link</label>
                <input type="text" placeholder="https://drive.google.com/..."
                    class="w-full border px-3 py-2 rounded mt-1 mb-3 text-sm">

                <p class="text-xs text-gray-500 mb-4">
                    Upload all required documents to Google Drive and paste the shared link above.
                </p>

                <div class="flex justify-end gap-3">
                    <button onclick="closeModal()"
                            class="px-4 py-2 text-sm border rounded hover:bg-gray-100">Cancel</button>

                    <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Application
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal Script -->
        <script>
        function openModal(title) {
            document.getElementById('modalTitle').innerText = 'Apply for ' + title;
            document.getElementById('applyModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('applyModal').classList.add('hidden');
        }
        </script>


    </main>
</div>

</body>
</html>