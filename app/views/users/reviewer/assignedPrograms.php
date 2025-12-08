<?php
session_start();
require_once __DIR__ . "../../../../core/dbconnection.php";

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "reviewer") {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

$reviewer_ID = $_SESSION["user_id"];

// ------------------------------------------------------------
// 1. FETCH PROGRAMS ASSIGNED TO THIS REVIEWER
// ------------------------------------------------------------
$sqlPrograms = "
    SELECT sp.scholarship_ID, sp.scholarship_Name, sp.requirements, 
        sp.Amount, sp.deadline,
        s.sponsor_company
    FROM scholarshipprogram sp
    LEFT JOIN sponsor s ON sp.sponsor_ID = s.sponsor_ID
    WHERE sp.reviewer_ID = ?
    AND sp.deadline >= CURDATE()
";

$stmtPrograms = $pdo->prepare($sqlPrograms);
$stmtPrograms->execute([$reviewer_ID]);
$programs = $stmtPrograms->fetchAll(PDO::FETCH_ASSOC);

// ------------------------------------------------------------
// 2. FETCH APPLICATIONS FOR SELECTED PROGRAM
// ------------------------------------------------------------
$applications = [];
$selectedProgram = null;

if (isset($_GET["program"])) {
    $selectedProgram = intval($_GET["program"]);

    $sqlApplications = "
        SELECT a.*, 
            s.first_Name, s.last_Name, s.student_ID, 
            s.course, s.year_Level
        FROM application a
        INNER JOIN student s ON a.student_ID = s.ID
        WHERE a.scholarship_ID = ?
        AND a.status = 'pending'
        ORDER BY a.date_applied DESC
    ";


    $stmtApps = $pdo->prepare($sqlApplications);
    $stmtApps->execute([$selectedProgram]);
    $applications = $stmtApps->fetchAll(PDO::FETCH_ASSOC);

    foreach ($programs as $prog) {
        if ($prog["scholarship_ID"] == $selectedProgram) {
            $selectedProgramName = $prog["scholarship_Name"];
            break;
        }
    }
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
<body class="bg-gray-50">

<div class="flex min-h-screen">

    <?php include __DIR__ . '/../../assets/components/reviewerSidebar.php'; ?>

    <main class="flex-1 p-8">

        <!-- PAGE 1: PROGRAM LIST -->
        <section id="programList" class="<?= isset($_GET['program']) ? 'hidden' : '' ?>">
            <h1 class="text-2xl font-semibold">Assigned Programs</h1>
            <p class="text-gray-500 mt-1 mb-6 text-sm">List of All Scholarship Programs Assigned to you</p>

            <?php if (empty($programs)): ?>
                <p class="text-gray-500">No programs assigned to you.</p>
            <?php endif; ?>

            <?php foreach ($programs as $p): ?>

                <?php
                    $countQuery = $pdo->prepare("
                        SELECT 
                            COUNT(*) AS total,
                            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending
                        FROM application
                        WHERE scholarship_ID = ?
                    ");
                    $countQuery->execute([$p["scholarship_ID"]]);
                    $counts = $countQuery->fetch(PDO::FETCH_ASSOC);
                ?>

                <a href="?program=<?= $p['scholarship_ID'] ?>">
                <div class="cursor-pointer bg-white border rounded-lg p-5 shadow-sm hover:bg-gray-100 transition flex items-center justify-between mb-3">

                    <div>
                        <h2 class="text-lg font-semibold"><?= htmlspecialchars($p["scholarship_Name"]) ?></h2>
                        <p class="text-sm text-gray-500">
                            <?= htmlspecialchars($p["sponsor_company"]) ?>
                        </p>

                        <?php
                            $req = $p["requirements"];

                            $requirementsArray = preg_split("/\r\n|\n|\r|,/", $req);

                            $requirementsArray = array_filter($requirementsArray);

                            $requirementsFormatted = implode(", ", $requirementsArray);
                        ?>

                        <p class="text-sm text-gray-500">
                            <strong>Requirements:</strong> <?= htmlspecialchars($requirementsFormatted) ?>
                        </p>


                        <div class="flex items-center gap-6 mt-3 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-base">groups</span>
                                <?= $counts["total"] ?> total applications
                            </span>

                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-base">event</span>
                                Deadline: <?= htmlspecialchars($p["deadline"]) ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <?php if ($counts["pending"] > 0): ?>
                        <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full">
                            <?= $counts["pending"] ?> pending
                        </span>
                        <?php endif; ?>

                        <span class="material-symbols-outlined text-gray-500">chevron_right</span>
                    </div>
                </div>
                </a>

            <?php endforeach; ?>
        </section>

        <!-- PAGE 2: APPLICATIONS -->
        <section id="programApplications" class="<?= isset($_GET['program']) ? '' : 'hidden' ?>">

            <a href="assignedPrograms.php" class="flex items-center gap-1 text-gray-600 mb-6 hover:text-gray-800">
                <span class="material-symbols-outlined">arrow_back</span>
                Back
            </a>

            <?php if ($selectedProgram): ?>
                <h1 class="text-2xl font-semibold mb-1"><?= htmlspecialchars($selectedProgramName) ?></h1>
                <p class="text-gray-500 mb-6">Applications for this program</p>
            <?php endif; ?>

            <?php if (empty($applications)): ?>
                <div class="bg-white border rounded-lg p-6 shadow-sm text-center">
                    <span class="material-symbols-outlined text-5xl text-green-500 mb-3">check_circle</span>
                    <h2 class="text-xl font-semibold">All Done!</h2>
                    <p class="text-gray-600 mt-1">You’ve reviewed all current pending applications for this program.</p>

                    <a href="assignedPrograms.php"
                    class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Go Back to Programs
                    </a>
                </div>
            <?php endif; ?>


            <?php foreach ($applications as $a): ?>
                <div class="bg-white border rounded-lg p-5 shadow-sm mb-4">
                    <div class="flex justify-between">

                        <div>
                            <div class="flex items-center mb-2">
                                <span class="material-symbols-outlined text-yellow-500 text-3xl mr-2">person</span>
                                <div>
                                    <h2 class="font-semibold text-lg">
                                        <?= $a["first_Name"] . " " . $a["last_Name"] ?>
                                    </h2>
                                    <p class="text-sm text-gray-600">
                                        <?= $a["student_ID"] ?> | 
                                        <?= $a["course"] ?> |
                                        <?= $a["year_Level"] ?>
                                    </p>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mt-3">
                                Applied: <?= $a["date_applied"] ?>
                                <?php if ($a["requirements_link"]): ?>
                                <a href="<?= $a["requirements_link"] ?>" target="_blank"
                                    class="text-blue-600 hover:underline ml-3">
                                    <span class="material-symbols-outlined text-sm align-middle">description</span>
                                    View Documents
                                </a>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="flex flex-col items-end justify-end">

                            <div class="flex items-center gap-2 mt-auto">
                                <a href="/Scholarship/app/controllers/reviewer/approveReject.php?action=approve&id=<?= $a['application_ID'] ?>&program=<?= $selectedProgram ?>"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check</span> Approve
                                </a>

                                <button onclick="openRejectModal('<?= $a['first_Name'] . ' ' . $a['last_Name'] ?>', <?= $a['application_ID'] ?>, <?= $selectedProgram ?>)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">close</span> Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- REJECT MODAL -->
        <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">

                <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <h2 class="text-xl font-semibold mb-1">Reject Application</h2>
                <p id="rejectModalText" class="text-sm text-gray-600 mb-4"></p>

                <form method="POST" id="rejectForm">

                    <input type="hidden" name="application_ID" id="rejectApplicationID">
                </form>

                <label class="text-sm font-medium">Rejection Reason</label>
                <textarea name="reason" form="rejectForm"
                    class="w-full border rounded-lg p-2 mt-1 mb-4 focus:ring focus:ring-red-200"
                    rows="3"
                    placeholder="Enter rejection reason..."></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="submit" form="rejectForm"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Reject Application
                    </button>
                </div>

            </div>
        </div>

    </main>
</div>

<script>
    function openRejectModal(name, appID, programID) {
        document.getElementById("rejectModalText").innerText =
            `Please provide a reason for rejecting "${name}".`;

        document.getElementById("rejectApplicationID").value = appID;

        document.getElementById("rejectForm").action =
            `/Scholarship/app/controllers/reviewer/approveReject.php?action=reject&program=${programID}`;

        document.getElementById("rejectModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("rejectModal").classList.add("hidden");
    }
</script>

</body>
</html>
