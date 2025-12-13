<?php
    session_start();
    require_once __DIR__ . "../../../../core/dbconnection.php";
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "student") {
        header("Location: ../auth/login.php");
        exit;
    }

    if ($_SESSION["role"] !== "student") {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
    }
    $studentID = $_SESSION["user_id"];
    $activeTab = $_GET['tab'] ?? 'all';

    if ($activeTab === 'all') {
        $all = $pdo->prepare("
            SELECT a.*, sp.scholarship_Name, sp.Amount,
                CONCAT(s.first_Name, ' ', s.last_Name) AS sponsor_name
            FROM application a
            INNER JOIN scholarshipprogram sp ON a.scholarship_ID = sp.scholarship_ID
            INNER JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
            WHERE a.student_ID = ?
            ORDER BY a.date_applied DESC
        ");
        $all->execute([$studentID]);
    }

    if ($activeTab === 'pending') {
        $pending = $pdo->prepare("
            SELECT a.*, sp.scholarship_Name, sp.Amount,
                CONCAT(s.first_Name, ' ', s.last_Name) AS sponsor_name
            FROM application a
            INNER JOIN scholarshipprogram sp ON a.scholarship_ID = sp.scholarship_ID
            INNER JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
            WHERE a.student_ID = ? AND a.status = 'pending'
            ORDER BY a.date_applied DESC
        ");
        $pending->execute([$studentID]);
    }
    
    if ($activeTab === 'approved') {
        $approved = $pdo->prepare("
            SELECT a.*, sp.scholarship_Name, sp.Amount,
                CONCAT(s.first_Name, ' ', s.last_Name) AS sponsor_name
            FROM application a
            INNER JOIN scholarshipprogram sp ON a.scholarship_ID = sp.scholarship_ID
            INNER JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
            WHERE a.student_ID = ? AND a.status = 'approved'
            ORDER BY a.date_applied DESC
        ");
        $approved->execute([$studentID]);
    }

    if ($activeTab === 'rejected') {
        $rejected = $pdo->prepare("
        SELECT a.*, sp.scholarship_Name, sp.Amount,
            CONCAT(s.first_Name, ' ', s.last_Name) AS sponsor_name
        FROM application a
        INNER JOIN scholarshipprogram sp ON a.scholarship_ID = sp.scholarship_ID
        INNER JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
        WHERE a.student_ID = ? AND a.status = 'rejected'
        ORDER BY a.date_applied DESC
    ");
    $rejected->execute([$studentID]);
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

            <div class="inline-flex bg-gray-100 rounded-md p-1 text-sm mb-5 font-semibold text-gray-700 gap-2">

            <a href="?tab=all"
               class="px-3 py-1 rounded-lg transition <?= $activeTab === 'all' ? 'bg-white shadow' : '' ?>">
               All
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
                <?php if ($activeTab === 'admin'): ?>
                    <?php endif; ?>
        </div>

            <!-- ALL CONTENT (VISIBLE BY DEFAULT) -->
             <?php if ($activeTab === 'all'): ?>
                <div class="tab-content-all">
                    <?php while ($a = $all->fetch(PDO::FETCH_ASSOC)): ?>

                        <div class="border rounded-xl p-5 bg-white hover:bg-gray-50 shadow-sm mb-4 flex justify-between items-start">

                            <!-- LEFT SIDE: ICON + TEXT -->
                            <div class="flex gap-4 items-start">

                                <!-- ICON -->
                                <div class="w-8 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                                </div>

                                <!-- TEXT -->
                                <div>
                                    <h2 class="font-semibold text-lg"><?= htmlspecialchars($a['scholarship_Name']) ?></h2>
                                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($a['sponsor_name']) ?></p>

                                    <div class="flex gap-6 text-sm mt-2">
                                        <p>Applied:
                                            <span class="font-medium">
                                                <?= date("M d, Y", strtotime($a['date_applied'])) ?>
                                            </span>
                                        </p>
                                        <p>Amount:
                                            <span class="font-medium text-green-600">
                                                ₱<?= number_format($a['Amount']) ?>
                                            </span>
                                        </p>
                                    </div>

                                    <!-- NOTE SECTION -->
                                    <?php if (!empty($a['note'])): ?>
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                            <strong>Note:</strong> <?= htmlspecialchars($a['note']) ?>
                                        </div>
                                    <?php endif; ?>


                                </div>

                            </div>

                            <!-- STATUS BADGE -->
                            <span class="px-3 py-1 text-xs rounded-full
                                <?php if ($a['status']=="pending") echo 'bg-yellow-100 text-yellow-700'; ?>
                                <?php if ($a['status']=="approved") echo 'bg-green-100 text-green-700'; ?>
                                <?php if ($a['status']=="rejected") echo 'bg-red-100 text-red-700'; ?>
                            ">
                                <?= ucfirst($a['status']) ?>
                            </span>

                        </div>

                    <?php endwhile; ?>
                </div>
            <?php endif; ?>


            <!-- PENDING CONTENT -->
            <?php if ($activeTab === 'pending'): ?>
                <div class="tab-content-pending">
                    <?php if ($pending->rowCount() == 0): ?>
                        <p class="text-gray-500">No pending applications.</p>
                    <?php endif; ?>

                    <?php while ($a = $pending->fetch(PDO::FETCH_ASSOC)): ?>

                        <div class="border rounded-xl p-5 bg-white hover:bg-gray-50 shadow-sm mb-4 flex justify-between items-start">

                            <!-- LEFT SIDE -->
                            <div class="flex gap-4 items-start">

                                <!-- ICON -->
                                <div class="w-8 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                                </div>

                                <!-- TEXT -->
                                <div>
                                    <h2 class="font-semibold text-lg"><?= htmlspecialchars($a['scholarship_Name']) ?></h2>
                                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($a['sponsor_name']) ?></p>

                                    <div class="flex gap-6 text-sm mt-2">
                                        <p>Applied:
                                            <span class="font-medium"><?= date("M d, Y", strtotime($a['date_applied'])) ?></span>
                                        </p>
                                        <p>Amount:
                                            <span class="font-medium text-green-600">₱<?= number_format($a['Amount']) ?></span>
                                        </p>
                                    </div>

                                    <!-- NOTE SECTION -->
                                    <?php if (!empty($a['note'])): ?>
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                            <strong>Note:</strong> <?= htmlspecialchars($a['note']) ?>
                                        </div>
                                    <?php endif; ?>


                                </div>
                            </div>

                            <!-- STATUS -->
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                Pending
                            </span>

                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <!-- APPROVED CONTENT -->
             <?php if ($activeTab === 'approved'): ?>
                <div class="tab-content-approved">
                    <?php if ($approved->rowCount() == 0): ?>
                        <p class="text-gray-500">No approved applications.</p>
                    <?php endif; ?>

                    <?php while ($a = $approved->fetch(PDO::FETCH_ASSOC)): ?>

                        <div class="border rounded-xl p-5 bg-white hover:bg-gray-50 shadow-sm mb-4 flex justify-between items-start">

                            <!-- LEFT SIDE -->
                            <div class="flex gap-4 items-start">

                                <!-- ICON -->
                                <div class="w-8 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                                </div>

                                <!-- TEXT -->
                                <div>
                                    <h2 class="font-semibold text-lg"><?= htmlspecialchars($a['scholarship_Name']) ?></h2>
                                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($a['sponsor_name']) ?></p>

                                    <div class="flex gap-6 text-sm mt-2">
                                        <p>Applied:
                                            <span class="font-medium"><?= date("M d, Y", strtotime($a['date_applied'])) ?></span>
                                        </p>
                                        <p>Amount:
                                            <span class="font-medium text-green-600">₱<?= number_format($a['Amount']) ?></span>
                                        </p>
                                    </div>

                                    <?php if (!empty($a['note'])): ?>
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                            <strong>Note:</strong> <?= htmlspecialchars($a['note']) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>

                            <!-- STATUS -->
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                Approved
                            </span>

                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>


            <!-- REJECTED CONTENT -->
             <?php if ($activeTab === 'rejected'): ?>
                <div class="tab-content-rejected">
                    <?php if ($rejected->rowCount() == 0): ?>
                        <p class="text-gray-500">No rejected applications.</p>
                    <?php endif; ?>

                    <?php while ($a = $rejected->fetch(PDO::FETCH_ASSOC)): ?>

                        <div class="border rounded-xl p-5 bg-white hover:bg-gray-50 shadow-sm mb-4 flex justify-between items-start">

                            <!-- LEFT SIDE -->
                            <div class="flex gap-4 items-start">

                                <!-- ICON -->
                                <div class="w-8 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-2xl">description</span>
                                </div>

                                <!-- TEXT -->
                                <div>
                                    <h2 class="font-semibold text-lg"><?= htmlspecialchars($a['scholarship_Name']) ?></h2>
                                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($a['sponsor_name']) ?></p>

                                    <div class="flex gap-6 text-sm mt-2">
                                        <p>Applied:
                                            <span class="font-medium"><?= date("M d, Y", strtotime($a['date_applied'])) ?></span>
                                        </p>
                                        <p>Amount:
                                            <span class="font-medium text-green-600">₱<?= number_format($a['Amount']) ?></span>
                                        </p>
                                    </div>

                                    <!-- NOTE SECTION -->
                                <?php if (!empty($a['note'])): ?>
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-lg text-sm text-yellow-800">
                                            <strong>Note:</strong> <?= htmlspecialchars($a['note']) ?>
                                        </div>
                                    <?php endif; ?>


                                </div>
                            </div>

                            <!-- STATUS -->
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                Rejected
                            </span>

                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        </main>
    </div>

</body>
</html>
