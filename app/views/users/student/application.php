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
    <title>My Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../../assets/components/studentSidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold">My Applications</h1>
            <p class="text-gray-600 mb-6">Track all your scholarship applications</p>

            <!-- SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                <!-- Pending -->
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 text-yellow-600 px-3 py-2 rounded-full">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                        <div>
                            <p class="text-3xl font-semibold">2</p>
                            <p class="text-gray-600 text-sm">Pending Review</p>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 text-green-600 px-3 py-2 rounded-full">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                        <div>
                            <p class="text-3xl font-semibold">1</p>
                            <p class="text-gray-600 text-sm">Approved</p>
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 text-red-600 px-3 py-2 rounded-full">
                            <span class="material-symbols-outlined">cancel</span>
                        </div>
                        <div>
                            <p class="text-3xl font-semibold">1</p>
                            <p class="text-gray-600 text-sm">Rejected</p>
                        </div>
                    </div>
                </div>

            </div>

             <div id="tabsContainer">

                <!-- HIDDEN RADIO BUTTONS -->
                <input type="radio" name="tab" id="tab-all" class="hidden" checked>
                <input type="radio" name="tab" id="tab-pending" class="hidden">
                <input type="radio" name="tab" id="tab-approved" class="hidden">
                <input type="radio" name="tab" id="tab-rejected" class="hidden">

                <!-- SUB NAVIGATION -->
                <div class="inline-flex bg-gray-100 rounded-md p-1 text-sm mb-6 font-semibold text-gray-700 gap-1">

                    <label for="tab-all" class="tab-item cursor-pointer px-3 py-1 rounded-md transition">
                        All
                    </label>

                    <label for="tab-pending" class="tab-item cursor-pointer px-3 py-1 rounded-md transition">
                        Pending
                    </label>

                    <label for="tab-approved" class="tab-item cursor-pointer px-3 py-1 rounded-md transition">
                        Approved 
                    </label>

                    <label for="tab-rejected" class="tab-item cursor-pointer px-3 py-1 rounded-md transition">
                        Rejected 
                    </label>

                </div>

                 <!-- CONTENT WRAPPER -->
                <div class="relative">

                    <div class="tab-content-all">
                        
                        <div class="border rounded-xl p-5 bg-white shadow-sm mb-4 flex justify-between items-start">
                            <div class="flex gap-4">
                                <div class="h-6 w-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined ">article</span>
                                </div>
                                <div>
                                    <h2 class="font-semibold text-lg">DOST Scholarship Program</h2>
                                    <p class="text-gray-500 text-sm">Department of Science and Technology</p>

                                    <div class="flex gap-6 text-sm mt-2">
                                        <p>Applied: <span class="font-medium">Nov 25, 2025</span></p>
                                        <p>Amount: <span class="font-medium text-green-600">₱40,000/year</span></p>
                                    </div>
                                </div>
                            </div>
                            <span class="text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full text-sm font-medium">⏱ Pending</span>
                        </div>

                    </div>

                    <div class="border rounded-xl p-5 bg-white shadow-sm mb-4 flex justify-between items-start">
                        <div class="flex gap-4">
                            <div class="h-6 w-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined ">article</span>
                            </div>
                            <div>
                                <h2 class="font-semibold text-lg">Merit Scholarship</h2>
                                <p class="text-gray-500 text-sm">University Foundation</p>

                                <div class="flex gap-6 text-sm mt-2">
                                    <p>Applied: <span class="font-medium">Nov 20, 2025</span></p>
                                    <p>Amount: <span class="font-medium text-green-600">₱25,000/year</span></p>
                                </div>
                            </div>
                        </div>

                        <span class="text-green-600 bg-green-100 px-3 py-1 rounded-full text-sm font-medium">✔ Approved</span>
                        </div>

                    <!-- PENDING -->
                    <div class="tab-content-pending hidden">

                        

                    </div>

                    <!-- APPROVED -->
                    <div class="tab-content-approved hidden">

                        

                    </div>

                    <!-- REJECTED -->
                    <div class="tab-content-rejected hidden">



                    </div>

                </div>

        </main>

    </div>

    <style>
    #tab-all:checked ~ .inline-flex label[for="tab-all"],
    #tab-pending:checked ~ .inline-flex label[for="tab-pending"],
    #tab-approved:checked ~ .inline-flex label[for="tab-approved"],
    #tab-rejected:checked ~ .inline-flex label[for="tab-rejected"] {
        background: white;
        color: black;
    }

    #tab-all:checked ~ .tab-content-all { display: block; }
    #tab-pending:checked ~ .tab-content-pending { display: block; }
    #tab-approved:checked ~ .tab-content-approved { display: block; }
    #tab-rejected:checked ~ .tab-content-rejected { display: block; }

    .tab-content-pending,
    .tab-content-approved,
    .tab-content-rejected {
        display: none;
    }
</style>
</body>



</html>
