<?php
    session_start();
    require_once __DIR__ . "../../../../core/dbconnection.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    if ($_SESSION["role"] !== "sponsor") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    $sponsorID = $_SESSION["user_id"];
    $activeTab = $_GET['tab'] ?? 'create';

    if ($activeTab === 'pending') {
        $sql = "
            SELECT 
                scholarship_ID,
                scholarship_Name AS scholarship_name,
                description,
                Amount,
                requirements,
                deadline,
                dateof_creation
            FROM scholarshipprogram
            WHERE sponsor_ID = :sid AND status = 'pending'
            ORDER BY scholarship_ID DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([":sid" => $sponsorID]);

        $pendingScholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if ($activeTab === 'approved') {
        $sqlApproved = "
            SELECT 
                scholarship_ID,
                scholarship_Name AS scholarship_name,
                description,
                Amount,
                requirements,
                deadline,
                status,
                dateof_creation
            FROM scholarshipprogram
            WHERE sponsor_ID = :sid AND status IN ('approved', 'under_review' , 'inactive')
            ORDER BY status ASC";

        $stmtApproved = $pdo->prepare($sqlApproved);
        $stmtApproved->execute([":sid" => $sponsorID]);

        $approvedScholarships = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);
    }

        if ($activeTab === 'rejected') {
            $sqlRejected = "
            SELECT 
                scholarship_ID,
                scholarship_Name AS scholarship_name,
                description,
                Amount,
                requirements,
                deadline,
                dateof_creation,
                note AS rejection_reason
            FROM scholarshipprogram
            WHERE sponsor_ID = :sid AND status = 'rejected'
            ORDER BY scholarship_ID DESC";

        $stmtRejected = $pdo->prepare($sqlRejected);
        $stmtRejected->execute([":sid" => $sponsorID]);

        $rejectedScholarships = $stmtRejected->fetchAll(PDO::FETCH_ASSOC);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My programs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>
<body class="bg-gray-50">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../../assets/components/sponsorSidebar.php'; ?>

            <main class="flex-1 p-6">

                    <div class="flex justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold">My Programs</h2>
                            <p class="text-gray-500 text-sm">Manage your scholarship programs</p>
                        </div>
                    </div>

                    <!-- ========== TABS WRAPPER (HIDDEN WHEN FORM OPENS) ========== -->
                     <div class="inline-flex bg-gray-100 rounded-md p-1 text-sm font-semibold text-gray-700 gap-2">

                        <a href="?tab=create"
                        class="px-3 py-1 rounded-lg transition <?= $activeTab === 'create' ? 'bg-white shadow' : '' ?>">
                        Create
                        </a>

                        <a href="?tab=pending"
                        class="px-3 py-1 rounded-lg transition <?= $activeTab === 'pending' ? 'bg-white shadow' : '' ?>">
                        Pending
                        </a>

                        <a href="?tab=approved"
                        class="px-3 py-1 rounded-lg transition <?= $activeTab === 'approved' ? 'bg-white shadow' : '' ?>">
                        Approved
                        </a>

                        <a href="?tab=rejected"
                        class="px-3 py-1 rounded-lg transition <?= $activeTab === 'rejected' ? 'bg-white shadow' : '' ?>">
                        Rejected
                        </a>

                        </div>

                        <!-- TAB CONTENTS -->
                        <div class="mt-4" id="tabsContainer">

                            <!-- CREATE TAB -->
                            <?php if ($activeTab === 'create'): ?>
                                <div class="tab-content-create">
                                    <div class="p-5 bg-white border rounded-xl text-center shadow">
                                        <h3 class="font-bold mb-1 text-sm">Create New Scholarship Program</h3>
                                        <p class="text-gray-500 text-xs mb-4">Propose a new scholarship for students</p>

                                        <button id="showCreateForm"
                                            class="flex items-center gap-1 mx-auto px-2 py-1 text-white bg-[#00bc7d] rounded hover:bg-green-700 text-sm">
                                            <span class="material-symbols-outlined text-base">add</span>
                                            Create Scholarship
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- PENDING TAB -->
                            <?php if ($activeTab === 'pending'): ?>
                                <div class="tab-content-pending">
                                        <?php if (empty($pendingScholarships)): ?>
                                            <p class="text-gray-500 text-center py-10 text-sm">
                                                No pending scholarship programs.
                                           </p>
                                        <?php else: ?>
                                            <?php foreach ($pendingScholarships as $sc): ?>
                                                <div class="p-4 border rounded-xl flex justify-between items-start bg-white hover:bg-gray-50 transition mb-4">

                                                    <div>
                                                        <h3 class="font-semibold"><?= htmlspecialchars($sc['scholarship_name']) ?></h3>

                                                        <p class="text-gray-500 text-sm mb-2">
                                                            <?= htmlspecialchars($sc['description']) ?>
                                                        </p>

                                                        <div class="flex items-center gap-4 text-sm text-gray-600">

                                                            <div class="flex items-center gap-1">
                                                                <span class="material-symbols-outlined text-base">payments</span>
                                                                ₱<?= number_format($sc['Amount'], 2) ?>/year
                                                            </div>

                                                            <div class="flex items-center gap-1">
                                                                <span class="material-symbols-outlined text-base text-orange-500">event</span>
                                                                Deadline: <?= date("M d, Y", strtotime($sc['deadline'])) ?>
                                                            </div>

                                                            <div class="flex items-center gap-1">
                                                                <span class="material-symbols-outlined text-base">schedule</span>
                                                                Created: <?= date("M d, Y", strtotime($sc['dateof_creation'])) ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">
                                                            Pending
                                                        </span>
                                                    </div>

                                                </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                             <?php endif; ?>

                            <!-- APPROVED TAB -->
                            <?php if ($activeTab === 'approved'): ?>
                                <div class="tab-content-approved">

                                    <?php if (empty($approvedScholarships)): ?>
                                        <p class="text-gray-500 text-center py-10 text-sm">
                                            No approved scholarship programs.
                                        </p>
                                    <?php else: ?>
                                        <?php foreach ($approvedScholarships as $sc): ?>
                                            <div class="p-4 border rounded-xl shadow-sm flex justify-between items-start bg-white hover:bg-gray-50 transition mb-4">

                                                <div>
                                                    <h3 class="font-semibold"><?= htmlspecialchars($sc['scholarship_name']) ?></h3>

                                                    <p class="text-gray-500 text-sm mb-2">
                                                        <?= htmlspecialchars($sc['description']) ?>
                                                    </p>

                                                    <div class="flex items-center gap-4 text-sm text-gray-600">

                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base">payments</span>
                                                            ₱<?= number_format($sc['Amount'], 2) ?>/year
                                                        </div>

                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base text-orange-500">event</span>
                                                            Deadline: <?= date("M d, Y", strtotime($sc['deadline'])) ?>
                                                        </div>

                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base">schedule</span>
                                                            Created: <?= date("M d, Y", strtotime($sc['dateof_creation'])) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="px-3 py-1 text-xs rounded-full font-medium 
                                                        <?= $sc['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' ?>
                                                        <?= $sc['status'] === 'under_review' ? 'bg-orange-200 text-yellow-700' : '' ?>
                                                        <?= $sc['status'] === 'inactive' ? 'bg-gray-200 text-gray-700' : '' ?>
                                                    ">
                                                        <?= strtoupper($sc['status']) ?>
                                                    </span>

                                                </div>

                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- REJECTED TAB -->
                            <?php if ($activeTab === 'rejected'): ?>
                                <div class="tab-content-rejected">

                                    <?php if (empty($rejectedScholarships)): ?>
                                        <p class="text-gray-500 text-center py-10 text-sm">
                                            No rejected scholarship programs.
                                        </p>
                                    <?php else: ?>
                                        <?php foreach ($rejectedScholarships as $sc): ?>
                                            <div class="p-4 border rounded-xl shadow-sm flex justify-between 
                                                        items-start hover:bg-gray-50 transition mb-4">

                                                <div>
                                                    <h3 class="font-semibold"><?= htmlspecialchars($sc['scholarship_name']) ?></h3>

                                                    <p class="text-gray-500 text-sm mb-2">
                                                        <?= htmlspecialchars($sc['description']) ?>
                                                    </p>

                                                    <div class="flex items-center gap-4 text-sm text-gray-600">

                                                        <!-- Amount -->
                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base">payments</span>
                                                            ₱<?= number_format($sc['Amount'], 2) ?>/year
                                                        </div>

                                                        <!-- Deadline -->
                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base text-orange-500">event</span>
                                                            Deadline: <?= date("M d, Y", strtotime($sc['deadline'])) ?>
                                                        </div>

                                                        <!-- Created -->
                                                        <div class="flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-base">schedule</span>
                                                            Created: <?= date("M d, Y", strtotime($sc['dateof_creation'])) ?>
                                                        </div>

                                                    </div>

                                                    <!-- Rejection Reason -->
                                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                                        <strong>Reason:</strong> <?= htmlspecialchars($sc['rejection_reason']) ?>
                                                    </div>
                                                </div>

                                                <!-- STATUS BADGE -->
                                                <div>
                                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-medium">
                                                        Rejected
                                                    </span>
                                                </div>

                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <!-- END TABS WRAPPER -->

                    <!-- ========== CREATE FORM SECTION ========== -->
                <form action="../../../controllers/sponsor/submitScholarship.php" method="POST">
                    <div id="createFormContainer" class="hidden">

                        <div class="max-w-3xl bg-white border rounded-xl p-6 text-sm mt-5">

                            <div class="flex items-center gap-2 mb-4">
                                <button type="button" id="cancelForm" class="material-symbols-outlined cursor-pointer">
                                    arrow_back
                                </button>

                                <div>
                                    <h2 class="text-xl font-bold">Create Scholarship Program</h2>
                                    <p class="text-gray-500 text-sm">Submit a new scholarship for approval</p>
                                </div>
                            </div>

                            <div class="space-y-4">

                                <div>
                                    <label class="font-semibold">Program Title *</label>
                                    <input name="scholarship_name" type="text" class="w-full mt-1 p-2 border rounded-lg" required>
                                </div>

                                <div>
                                    <label class="font-semibold">Description *</label>
                                    <textarea name="description" class="w-full mt-1 p-2 border rounded-lg" rows="4" required></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold">Amount *</label>
                                        <input name="amount" type="number" step="0.01" class="w-full mt-1 p-2 border rounded-lg" required>
                                    </div>

                                    <div>
                                        <label class="font-semibold">Application Deadline *</label>
                                        <input name="deadline" type="date" class="w-full mt-1 p-2 border rounded-lg" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="font-semibold">Requirements</label>
                                    <textarea name="requirements" class="w-full mt-1 p-2 border rounded-lg" rows="3"></textarea>
                                </div>

                                <div class="flex justify-end gap-3 mt-4">
                                    <button type="button" id="cancelForm2" class="px-4 py-2 border rounded-lg">Cancel</button>

                                    <button type="submit" class="flex items-center gap-1 px-4 py-2 text-white bg-[#00bc7d] rounded hover:bg-green-700">
                                        <span class="material-symbols-outlined text-base">send</span>
                                        Submit for Approval
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>

                <?php if (isset($_GET['error'])): ?>
                <script>
                    alert("Failed to submit scholarship. Please try again.");
                </script>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                <script>
                    alert("Scholarship submitted successfully!");
                </script>
                <?php endif; ?>

                <!-- ========== JAVASCRIPT TOGGLE LOGIC ========== -->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const tabsContainer = document.getElementById("tabsContainer");
                        const formContainer = document.getElementById("createFormContainer");

                        const showFormBtn = document.getElementById("showCreateForm");
                        const cancelForm = document.getElementById("cancelForm");
                        const cancelForm2 = document.getElementById("cancelForm2");

                        function showForm() {
                            tabsContainer.classList.add("hidden");
                            formContainer.classList.remove("hidden");
                        }

                        function hideForm() {
                            formContainer.classList.add("hidden");
                            tabsContainer.classList.remove("hidden");
                        }

                        showFormBtn.addEventListener("click", showForm);
                        cancelForm.addEventListener("click", hideForm);
                        cancelForm2.addEventListener("click", hideForm);
                    });
                </script>
            </main>
    </div>

</body>
</html>