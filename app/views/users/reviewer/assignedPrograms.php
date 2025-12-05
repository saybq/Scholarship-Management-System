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
    <title>Assigned Programs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>
<body class="bg-gray-50 ">

    <div class="flex min-h-screen">

    <?php include __DIR__ . '/../../assets/components/reviewerSidebar.php'; ?>

    <main class="flex-1 p-8">

        <!-- PAGE 1: PROGRAM LIST -->

        <section id="programList">
            <h1 class="text-2xl font-semibold mb-6">Assigned Programs</h1>

            <!-- Scholarship Card -->
            <div onclick="openProgram()"
                class="cursor-pointer bg-white border rounded-lg p-5 shadow-sm hover:bg-gray-100 transition flex items-center justify-between">

                <!-- LEFT SIDE -->
                <div>
                    <h2 class="text-lg font-semibold">DOST Scholarship Program</h2>
                    <p class="text-sm text-gray-500">Department of Science and Technology</p>

                    <div class="flex items-center gap-6 mt-3 text-sm text-gray-600">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">groups</span>
                            25 total applications
                        </span>

                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">event</span>
                            Deadline: Dec 15, 2025
                        </span>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center gap-3">
                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full">
                        5 pending
                    </span>

                    <span class="material-symbols-outlined text-gray-500">
                        chevron_right
                    </span>
                </div>
            </div>
        </section>

        <!-- PAGE 2: APPLICATIONS -->
        <section id="programApplications" class="hidden">

            <!-- Back Button -->
            <button onclick="closeProgram()"
                class="flex items-center gap-1 text-gray-600 mb-6 hover:text-gray-800">
                <span class="material-symbols-outlined">arrow_back</span>
                Back
            </button>

            <h1 class="text-2xl font-semibold mb-1">DOST Scholarship Program</h1>
            <p class="text-gray-500 mb-6">Department of Science and Technology</p>

            <!-- Application Card 1 -->
            <div class="bg-white border rounded-lg p-5 shadow-sm mb-4">
                <div class="flex justify-between">

                    <!-- LEFT SIDE -->
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="material-symbols-outlined text-yellow-500 text-3xl mr-2">person</span>
                            <div>
                                <h2 class="font-semibold text-lg">Juan Dela Cruz</h2>
                                <p class="text-sm text-gray-600">
                                    2021-00123 | BS Computer Science | 3rd Year
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mt-3">
                            Applied: Nov 25, 2025
                            <a href="#" class="text-blue-600 hover:underline ml-3">
                                <span class="material-symbols-outlined text-sm align-middle">description</span>
                                View Documents
                            </a>
                        </p>
                    </div>

                    <!-- RIGHT SIDE — bottom aligned -->
                    <div class="flex flex-col items-end justify-end">
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            Pending
                        </span>

                        <div class="flex items-center gap-2 mt-auto">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check</span> Approve
                            </button>

                            <button onclick="openRejectModal('Earl Alabastro')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">close</span> Reject
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white border rounded-lg p-5 shadow-sm mb-4">
                <div class="flex justify-between">

                    <!-- LEFT SIDE -->
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="material-symbols-outlined text-yellow-500 text-3xl mr-2">person</span>
                            <div>
                                <h2 class="font-semibold text-lg">Earl Alabastro</h2>
                                <p class="text-sm text-gray-600">
                                    2022-00142 | BS Computer Science | 2rd Year
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 mt-3">
                            Applied: Nov 30, 2025
                            <a href="#" class="text-blue-600 hover:underline ml-3">
                                <span class="material-symbols-outlined text-sm align-middle">description</span>
                                View Documents
                            </a>
                        </p>
                    </div>

                    <!-- RIGHT SIDE — bottom aligned -->
                    <div class="flex flex-col items-end justify-end">
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-base">schedule</span>
                            Pending
                        </span>

                        <div class="flex items-center gap-2 mt-auto">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check</span> Approve
                            </button>

                            <button onclick="openRejectModal('Earl Alabastro')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">close</span> Reject
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </section>

                <!-- MODAL BACKDROP -->
        <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <!-- MODAL BOX -->
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">

                <!-- Close button (X) -->
                <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <h2 class="text-xl font-semibold mb-1">Reject Application</h2>
                <p id="rejectModalText" class="text-sm text-gray-600 mb-4">
                    Please provide a reason for rejecting this application.
                </p>

                <label class="text-sm font-medium">Rejection Reason</label>
                <textarea
                    id="rejectionReason"
                    class="w-full border rounded-lg p-2 mt-1 mb-4 focus:ring focus:ring-red-200"
                    rows="3"
                    placeholder="Enter the reason for rejection..."
                ></textarea>

                <!-- BUTTONS -->
                <div class="flex justify-end gap-2">
                    <button onclick="closeModal()"
                        class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button onclick="submitRejection()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject Application
                    </button>
                </div>

            </div>
        </div>

    </main>
</div>

<script>
    function openProgram() {
        document.getElementById('programList').classList.add('hidden');
        document.getElementById('programApplications').classList.remove('hidden');
    }

    function closeProgram() {
        document.getElementById('programApplications').classList.add('hidden');
        document.getElementById('programList').classList.remove('hidden');
    }

    function openRejectModal(applicantName) {
        // Update text dynamically
        document.getElementById("rejectModalText").innerText =
            `Please provide a reason for rejecting "${applicantName}".`;

        document.getElementById("rejectModal").classList.remove("hidden");
        document.body.classList.add("overflow-hidden"); // disable background scroll
    }

    function closeModal() {
        document.getElementById("rejectModal").classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
        document.getElementById("rejectionReason").value = "";
    }

    function submitRejection() {
        let reason = document.getElementById("rejectionReason").value.trim();
        if (reason === "") {
            alert("Please enter a rejection reason.");
            return;
        }

        // Placeholder — connect to backend later
        console.log("Rejected with reason:", reason);

        closeModal();
    }
</script>

</body>
</html>