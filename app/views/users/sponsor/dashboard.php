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

    $stmtPrograms = $pdo->prepare("
        SELECT COUNT(scholarship_ID) AS total_programs
        FROM scholarshipprogram 
        WHERE sponsor_ID = ?");

    $stmtPrograms->execute([$sponsorID]);
    $totalPrograms = $stmtPrograms->fetchColumn();

    $stmtApproved = $pdo->prepare("
        SELECT COUNT(scholarship_ID) AS approved_programs
        FROM scholarshipprogram 
        WHERE sponsor_ID = ? AND status = 'approved'");

    $stmtApproved->execute([$sponsorID]);
    $approved = $stmtApproved->fetchColumn();

    $stmtPending = $pdo->prepare("
        SELECT COUNT(scholarship_ID) AS pending_programs
        FROM scholarshipprogram 
        WHERE sponsor_ID = ? AND status = 'pending'");

    $stmtPending->execute([$sponsorID]);
    $pending = $stmtPending->fetchColumn();

    $stmtRejected = $pdo->prepare("
        SELECT COUNT(scholarship_ID) AS rejected_programs
        FROM scholarshipprogram 
        WHERE sponsor_ID = ? AND status = 'rejected'");

    $stmtRejected->execute([$sponsorID]);
    $rejected = $stmtRejected->fetchColumn();

    $stmtRecent = $pdo->prepare("
        SELECT scholarship_Name, status, dateof_creation
        FROM scholarshipprogram
        WHERE sponsor_ID = ?
        ORDER BY dateof_creation DESC
        LIMIT 3");
        
    $stmtRecent->execute([$sponsorID]);
    $recentPrograms = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

    $stmtApplicants = $pdo->prepare("
        SELECT COUNT(a.application_ID) AS total_applicants
        FROM application AS a
        JOIN scholarshipprogram AS s 
            ON a.scholarship_ID = s.scholarship_ID
        WHERE s.sponsor_ID = ?");

    $stmtApplicants->execute([$sponsorID]);
    $total_applicants = $stmtApplicants->fetch(PDO::FETCH_ASSOC)['total_applicants'];

    $stmtFunds = $pdo->prepare("
        SELECT SUM(Amount) AS total_funds
        FROM scholarshipprogram
        WHERE sponsor_ID = ? 
        AND status = 'approved'");
        
    $stmtFunds->execute([$sponsorID]);
    $total_funds = $stmtFunds->fetch(PDO::FETCH_ASSOC)['total_funds'] ?? 0;
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
        <?php include __DIR__ . '/../../assets/components/sponsorSidebar.php'; ?>

        <main class="flex-1 p-6">

            <div class="flex justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold">Dashboard</h2>
                    <p class="text-gray-500 text-sm">Welcome back, <?php echo $_SESSION["username"]; ?></p>
                </div>

                <a href="myprograms.php"
                    class="h-8 px-5 bg-[#00bc7d] text-white font-bold rounded-md shadow text-xs flex items-center hover:bg-green-600 rounded-md shadow inline-flex items-center">
                    Create Program
                </a>
            </div>

            <!-- Top Statatistics -->
            <div class="grid grid-cols-4 gap-4 mb-6">

                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm [box-shadow:inset_4px_0_0_#6b7280,0_1px_2px_rgba(0,0,0,0.05)]">
                    <p class="text-gray-500 font-medium flex items-center">Total Scholarships
                        <span class="material-symbols-outlined text-base ml-auto text-gray-600 ml-auto">license</span>
                    </p>
                    
                    <p class="text-2xl font-bold mt-1"> <?= $totalPrograms ?></p>
                    <p class="text-gray-400 text-xs">Programs created</p>

                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm  [box-shadow:inset_4px_0_0_#16a34a,0_1px_2px_rgba(0,0,0,0.05)]">
                    <p class="text-gray-500 font-medium flex items-center">Approved
                        <span class="material-symbols-outlined text-base ml-auto text-green-600">task_alt</span>
                    </p>

                    <p class="text-2xl font-bold mt-1"><?= $approved ?></p>
                    <p class="text-gray-400 text-xs">Active programs</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm [box-shadow:inset_4px_0_0_#facc15,0_1px_2px_rgba(0,0,0,0.05)]">
                    <p class="text-gray-500 font-medium flex items=center">Pending Approval
                        <span class="material-symbols-outlined text-base ml-auto text-yellow-600 ml-auto">schedule</span>
                    </p>

                    <p class="text-2xl font-bold mt-1"><?= $pending ?></p>
                    <p class="text-gray-400 text-xs">Awaiting admin review</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm [box-shadow:inset_4px_0_0_#dc2626,0_1px_2px_rgba(0,0,0,0.05)]">
                    <p class="text-gray-500 font-medium flex items=center">Rejected
                        <span class="material-symbols-outlined text-base ml-auto text-red-600 ml-auto">cancel</span>
                    </p>
                    
                    <p class="text-2xl font-bold mt-1"><?= $rejected ?></p>
                    <p class="text-gray-400 text-xs">Needs revision</p>
                </div>
            </div>

            <!-- Bottom Section-->
            <div class="grid grid-cols-2 gap-4 mt-6">

                <!-- Recent Programs -->
                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm">
                    <h3 class="font-semibold mb-3 text-base">Recent Programs</h3>
                    <p class="text-s text-gray-500 mb-3 leading-[0.1]">Your latest scholarship submissions</p>

                    <div class="space-y-2 mt-8">
                        <?php if (count($recentPrograms) === 0): ?>
                            <p class="text-gray-500 text-sm">No recent programs found.</p>
                        <?php else: ?>
                            <?php foreach ($recentPrograms as $program): ?>

                                <div class="p-3 bg-gray-50 rounded-lg flex justify-between items-center border">
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($program['scholarship_Name']) ?></p>
                                        <p class="text-xs text-gray-500">
                                            Created: <?= date("M d, Y", strtotime($program['dateof_creation'])) ?>
                                        </p>
                                    </div>

                                    <?php $status = $program['status']; ?>

                                    <span class="px-2 py-1 text-[10px] rounded-full
                                        <?= $status === 'approved' ? 'bg-green-100 text-green-700' : '' ?>
                                        <?= $status === 'inactive' ? 'bg-gray-200 text-gray-700' : '' ?>
                                        <?= $status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' ?>
                                        <?= $status === 'under_review' ? 'bg-orange-200 text-yellow-700' : '' ?>
                                        <?= $status === 'rejected' ? 'bg-red-100 text-red-700' : '' ?>
                                    ">
                                        <?= strtoupper($status) ?>
                                    </span>

                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="bg-white p-4 rounded-lg shadow-sm border text-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-sm">trending_up</span>
                        <h3 class="font-semibold">Program Statistics</h3>
                    </div>

                    <div class="space-y-3">

                        <!-- Total Applicants -->
                        <div class="p-4 bg-green-50 rounded-lg text-center">
                            <p class="text-3xl font-bold text-green-700">
                                <?= $total_applicants ?>
                            </p>
                            <p class="text-gray-500 text-xs">Total Applicants</p>
                        </div>

                        <!-- Total Funds Committed -->
                        <div class="p-4 bg-blue-50 rounded-lg text-center">
                            <p class="text-3xl font-bold text-blue-700">
                                ₱<?= number_format($total_funds, 2) ?>
                            </p>
                            <p class="text-gray-500 text-xs">Funds Committed</p>
                        </div>

                    </div>
                </div>


            </div>

        </main>
    </div>

</body>
</html>

