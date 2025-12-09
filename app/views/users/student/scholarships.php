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

    $stmt = $pdo->prepare("
        SELECT 
            scholarship_ID,
            scholarship_Name,
            description,
            Amount,
            requirements,
            deadline
        FROM scholarshipprogram
        WHERE status = 'approved'
    ");
    $stmt->execute();
    $scholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarships</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>

<body class="bg-gray-50">

<div class="flex min-h-screen">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <script>alert("Application Submitted Succressfully!");</script>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <script>alert("Failed to Submit!");</script>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'empty'): ?>
            <script>alert("Please fill out all fields.");</script>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'already_applied'): ?>
            <script>alert("You have already applied for this scholarship.");</script>
        <?php endif; ?>


    <!-- Sidebar -->
    <?php include __DIR__ . '/../../assets/components/studentSidebar.php'; ?>

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

        <!-- Scholarship List -->
        <div class="space-y-4">

                <?php foreach ($scholarships as $s): ?>

                    <?php
                        // Count requirements (split by newline)
                        $reqCount = substr_count(trim($s['requirements']), "\n") + 1;
                    ?>

                    <div class="bg-white p-5 rounded-lg shadow flex justify-between items-center border">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-500">school</span>

                            <div>
                                <h2 class="font-semibold text-gray-800"><?= htmlspecialchars($s['scholarship_Name']) ?></h2>

                                <p class="text-gray-600 text-sm mb-2">
                                    <?= htmlspecialchars(substr($s['description'], 0, 100)) ?>...
                                </p>

                                <div class="flex gap-4 text-xs text-gray-600">
                                    <span class="text-green-600">₱<?= number_format($s['Amount'], 2) ?></span>
                                    <span class="text-orange-600">Deadline: <?= htmlspecialchars($s['deadline']) ?></span>
                                    <span><?= $reqCount ?> requirements</span>
                                </div>
                            </div>
                        </div>

                        <?php
                            $check = $pdo->prepare("
                                SELECT 1 FROM application
                                WHERE student_ID = ? AND scholarship_ID = ?
                                LIMIT 1
                            ");
                            $check->execute([$_SESSION['user_id'], $s['scholarship_ID']]);
                            $alreadyApplied = $check->fetchColumn() !== false;
                        ?>

                        <?php if ($alreadyApplied): ?>
                            <button 
                                class="px-4 py-2 bg-gray-300 text-gray-600 rounded text-sm cursor-not-allowed"
                                disabled
                            >
                                APPLIED
                            </button>
                        <?php else: ?>
                            <button 
                                onclick="openModal(
                                    `<?= $s['scholarship_Name'] ?>`,
                                    `<?= $s['requirements'] ?>`,
                                    <?= $s['scholarship_ID'] ?>
                                )"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm"
                            >
                                Apply Now
                            </button>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>

        </div>

        <!-- Modal Background -->
        <div id="applyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 
            flex items-center justify-center">

            <form action="/Scholarship/app/controllers/student/submitApplication.php" method="POST" class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">

                <input type="hidden" id="scholarshipID" name="scholarship_ID">

                <div class="flex justify-between items-center mb-4">
                    <h2 id="modalTitle" class="text-lg font-semibold text-gray-800"></h2>
                    <button type="button" onclick="closeModal()" 
                            class="material-symbols-outlined text-gray-500 cursor-pointer">
                        close
                    </button>
                </div>

                <p class="text-sm text-gray-600 mb-3">
                    Please review the requirements and submit your documents.
                </p>

                <h3 class="font-medium text-gray-800 mb-2">Requirements:</h3>

                <ul id="reqList" class="list-disc ml-6 text-sm text-gray-700 space-y-1 mb-4"></ul>

                <label class="text-sm font-medium text-gray-700">Google Drive Link</label>
                <input type="text" name="requirements_link" required
                    placeholder="https://drive.google.com/..."
                    class="w-full border px-3 py-2 rounded mt-1 mb-3 text-sm">

                <p class="text-xs text-gray-500 mb-4">
                    Upload all required documents to Google Drive and paste the shared link above.
                </p>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-sm border rounded hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Application
                    </button>
                </div>

            </form>

        </div>


        <!-- Modal Script -->
        <script>
            function openModal(title, requirements, id) {
                document.getElementById('modalTitle').innerText = 'Apply for ' + title;

                // Set Scholarship ID in hidden input
                document.getElementById('scholarshipID').value = id;

                // Split requirements
                const reqArr = requirements.split("\n");

                const ul = document.getElementById('reqList');
                ul.innerHTML = ""; 

                reqArr.forEach(r => {
                    if (r.trim() !== "") {
                        ul.innerHTML += `<li>${r.trim()}</li>`;
                    }
                });

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