<?php
session_start();
require_once __DIR__ . "../../../../core/dbconnection.php";

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}
if ($_SESSION["role"] !== "admin") {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

// FETCH PENDING SCHOLARSHIPS
try {
    $stmt = $pdo->prepare("
        SELECT 
            sp.scholarship_ID,
            sp.scholarship_Name,
            sp.description,
            sp.Amount,
            sp.requirements,
            sp.deadline,
            sp.dateof_creation,
            s.sponsor_company
        FROM scholarshipprogram sp
        JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
        WHERE sp.status = 'pending'
        ORDER BY sp.dateof_creation DESC
    ");

    $stmt->execute();
    $pendingList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">

    <?php include __DIR__ . '/../../assets/components/adminSidebar.php'; ?>

    <main class="flex-1 p-6">

        <h1 class="text-2xl font-bold text-gray-800">Pending Scholarships</h1>
        <p class="text-gray-500 mt-1 mb-6 text-sm">
            Review and approve scholarship proposals from sponsors
        </p>

        <?php if (empty($pendingList)): ?>
            <div class="p-6 bg-white rounded-xl shadow text-center text-gray-600">
                No pending scholarship proposals.
            </div>
        <?php endif; ?>

        <?php foreach ($pendingList as $row): ?>

        <?php
            $safeName = json_encode($row['scholarship_Name']); 
            $safeID = json_encode($row['scholarship_ID']);
        ?>

        <div class="bg-white p-6 rounded-xl shadow border mb-6">

            <!-- Header -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['scholarship_Name']); ?></h3>
                    <p class="text-gray-500 text-sm flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-base">domain</span>
                        <?= htmlspecialchars($row['sponsor_company']); ?>
                    </p>
                </div>

                <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                    Pending
                </span>
            </div>

            <!-- Description -->
            <p class="text-gray-700 text-sm mt-4 leading-relaxed">
                <?= nl2br(htmlspecialchars($row['description'])); ?>
            </p>

            <!-- Amount + Deadline -->
            <div class="flex items-center gap-6 mt-5 text-sm">

                <div class="flex items-center gap-1 text-green-600 font-medium">
                    <span class="material-symbols-outlined text-base">payments</span>
                    ₱<?= number_format($row['Amount']); ?>/year
                </div>

                <div class="flex items-center gap-1 text-blue-600">
                    <span class="material-symbols-outlined text-base">event</span>
                    Deadline: <?= htmlspecialchars($row['deadline']); ?>
                </div>

            </div>

            <!-- Requirements -->
            <div class="bg-gray-50 border rounded-lg p-4 mt-5 text-sm">
                <p class="font-semibold mb-1">Requirements:</p>
                <p class="text-gray-600 text-sm">
                    <?= nl2br(htmlspecialchars($row['requirements'])); ?>
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center mt-4 text-xs text-gray-500">
                <p>Submitted: <?= htmlspecialchars($row['dateof_creation']); ?></p>

                <div class="flex gap-3">

                    <!-- Approve -->
                    <a href="/Scholarship/app/controllers/admin/approveReject.php?action=approve&id=<?= $row['scholarship_ID']; ?>"
                       class="flex items-center gap-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-xs">
                        <span class="material-symbols-outlined text-sm">check</span>
                        Approve
                    </a>

                    <!-- Reject -->
                    <button 
                        onclick='openRejectModal(<?= $safeName ?>, <?= $safeID ?>)'
                        class="flex items-center gap-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-xs">
                        <span class="material-symbols-outlined text-sm">close</span>
                        Reject
                    </button>

                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </main>
</div>


<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white w-[550px] rounded-xl shadow-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Reject Scholarship Proposal</h2>
            <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <p class="text-gray-600 mb-3 text-sm">
            Please provide a reason for rejecting 
            <span id="modalScholarshipName" class="font-semibold"></span>.
        </p>

        <form method="POST" action="/Scholarship/app/controllers/admin/approveReject.php?action=reject">
            <input type="hidden" id="rejectID" name="scholarship_ID">

            <label class="text-sm font-medium">Rejection Reason</label>
            <textarea
                name="reason"
                id="rejectionReason"
                required
                placeholder="Enter the reason for rejection..."
                class="w-full border rounded-lg p-3 text-sm mt-1 h-24 focus:ring focus:ring-red-200"
            ></textarea>

            <div class="flex justify-end gap-3 mt-5">

                <button onclick="closeRejectModal()" 
                        type="button"
                        class="px-4 py-2 rounded-md border hover:bg-gray-100">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Reject Proposal
                </button>

            </div>
        </form>

    </div>
</div>


<script>
    function openRejectModal(name, id) {
        document.getElementById("modalScholarshipName").innerText = '"' + name + '"';
        document.getElementById("rejectID").value = id;
        document.getElementById("rejectModal").classList.remove("hidden");
    }

    function closeRejectModal() {
        document.getElementById("rejectModal").classList.add("hidden");
    }
</script>

</body>
</html>
