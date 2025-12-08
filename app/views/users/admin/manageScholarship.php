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
    

$sqlApproved = "
        SELECT 
        sp.*, 
        CONCAT(s.first_Name, ' ', s.middle_Name, ' ', s.last_Name) AS sponsor_name,
        s.email AS sponsor_email,
        s.contact_Number AS sponsor_contact,
        s.sponsor_company AS sponsor_company,
        s.sponsor_type,
        CONCAT(r.first_Name, ' ', r.last_Name) AS reviewer_name,

        COUNT(a.application_ID) AS applicants_count  -- NEW FIELD

    FROM scholarshipprogram sp
    LEFT JOIN sponsor s 
        ON sp.sponsor_ID = s.sponsor_ID
    LEFT JOIN admissionstaff r
        ON sp.reviewer_ID = r.reviewer_ID
    LEFT JOIN application a
        ON sp.scholarship_ID = a.scholarship_ID  -- COUNT APPLICATIONS

    WHERE sp.status = 'approved'
    GROUP BY sp.scholarship_ID
    ORDER BY sp.scholarship_ID DESC
";


$stmt = $pdo->prepare($sqlApproved);
$stmt->execute();
$scholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);

// FETCH REVIEWERS (IMPORTANT)
$reviewers = $pdo->query("SELECT reviewer_ID, first_Name, last_Name FROM admissionstaff")->fetchAll();    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scholarships</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>

<body class="bg-gray-50">
    <!-- REVIEWER ASSIGNED ALERT-->
    <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
        <script>
            alert("Reviewer assigned successfully!");
        </script>
    <?php endif; ?>

    <!-- PROGRAM MARKED AS INACTIVE ALERT-->
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <script>
            alert("Scholarship has been marked as inactive.");
        </script>
    <?php endif; ?>

    <div class="flex min-h-screen">

        <?php include __DIR__ . '/../../assets/components/adminSidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 p-6">

            <!-- Page Title -->
            <h1 class="text-2xl font-bold text-gray-800">Manage Scholarships</h1>
            <p class="text-gray-500 mt-1 mb-6 text-sm">
                View, edit, and assign reviewers to approved scholarships.
            </p>

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


            <!-- Scholarship Card -->
            <?php foreach ($scholarships as $s): ?>
            <div class="bg-white p-6 rounded-xl shadow border mb-4">

                <div>
                    <h2 class="text-lg font-semibold"><?= htmlspecialchars($s['scholarship_Name']) ?></h2>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($s['sponsor_company']) ?></p>
                </div>

                <div class="mt-4 grid grid-cols-2 items-center text-sm">

                    <div class="flex flex-wrap items-center gap-6 text-gray-600">

                        <!-- Amount -->
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-green-600 text-base">payments</span>
                            ₱<?= number_format($s['Amount']) ?>/year
                        </div>

                        <!-- Deadline -->
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-blue-600 text-base">event</span>
                            Deadline: <?= date("M d, Y", strtotime($s['deadline'])) ?>
                        </div>

                        <!-- Applicants count -->
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-gray-500 text-base">group</span>
                            <?= $s['applicants_count'] ?> applicants
                        </div>

                    </div>

                    <div class="flex justify-end items-center gap-3">

                        <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm">
                            Reviewer: <?= htmlspecialchars($s['reviewer_name'] ?? "None") ?>
                        </span>

                        <button 
                            class="flex items-center gap-1 text-sm border px-3 py-1.5 rounded-lg hover:bg-gray-100 transition"
                            onclick="openModal(
                                '<?= $s['scholarship_ID'] ?>',
                                decodeURIComponent('<?= rawurlencode($s['scholarship_Name']) ?>'),
                                '<?= $s['reviewer_ID'] ?>'
                            )"
                        >

                            <span class="material-symbols-outlined text-base">person_search</span>
                            Change Reviewer
                        </button>

                        <button class="hover:text-red-600"
                            onclick="openDeleteModal(
                                decodeURIComponent('<?= rawurlencode($s['scholarship_Name']) ?>'),
                                '/Scholarship/app/controllers/admin/deleteScholarship.php?id=<?= $s['scholarship_ID'] ?>'
                            )">
                            <span class="material-symbols-outlined">delete</span>
                        </button>

                    </div>

                </div>

            </div>
            <?php endforeach; ?>

            <!-- Assign Reviewer Modal -->
            <div id="assignModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">

                <div class="bg-white w-[450px] p-6 rounded-xl shadow-xl relative">

                    <button onclick="closeModal()" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <h2 class="text-xl font-semibold text-gray-800">Assign Reviewer</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        Select a reviewer for "<span id="modalScholarshipName"></span>".
                    </p>

                    <!-- FORM -->
                    <form action="/Scholarship/app/controllers/admin/assignReviewer.php" method="POST" class="mt-5">

                        <input type="hidden" id="modalScholarshipID" name="scholarship_ID">

                        <div>
                            <label class="text-sm font-medium text-gray-700">Reviewer</label>
                            <select name="reviewer_ID" id="reviewerSelect" required>
                                <option value="">Select Reviewer</option>
                                <?php foreach ($reviewers as $rev): ?>
                                    <option value="<?= $rev['reviewer_ID'] ?>">
                                        <?= $rev['first_Name'] . ' ' . $rev['last_Name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                                Cancel
                            </button>

                            <button type="submit" name="assignReviewer"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                                Assign Reviewer
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- DELETE SCHOLARSHIP MODAL -->
            <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
                <div class="bg-white w-[450px] p-6 rounded-xl shadow-xl relative">

                    <button onclick="closeDeleteModal()" class="absolute right-4 top-4 text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <h2 class="text-xl font-semibold text-gray-800">Mark as Inactive</h2>

                    <p class="text-gray-600 text-sm mt-2">
                        Are you sure you want to place 
                        "<span id="deleteScholarshipName" class="font-medium"></span>"?
                        in inactive status?
                    </p>

                    <div class="flex justify-end gap-3 mt-6">
                        <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>

                        <button id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                            Yes
                        </button>
                    </div>

                </div>
            </div>

        </main>
    </div>

<script>
    function openModal(id, name, reviewerID) {
        document.getElementById('modalScholarshipName').innerText = name;
        document.getElementById('modalScholarshipID').value = id;

        // Set default selected reviewer
        if (reviewerID) {
            document.getElementById('reviewerSelect').value = reviewerID;
        }

        document.getElementById('assignModal').classList.remove('hidden');
    }

    function closeModal() {
            document.getElementById('assignModal').classList.add('hidden');
    }

    function openDeleteModal(name, deleteUrl) {
        document.getElementById('deleteScholarshipName').innerText = name;
        document.getElementById('confirmDeleteBtn').setAttribute("onclick", `location.href='${deleteUrl}'`);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
</script>

</body>
</html>
