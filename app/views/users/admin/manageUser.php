<?php
require_once __DIR__ . "../../../../core/dbconnection.php";
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "admin") {
    header("Location: /Scholarship/app/views/auth/login.php");
    exit;
}

$activeTab = $_GET['tab'] ?? 'admin';

// Load only the required data
if ($activeTab === 'admin') {
    $adminUsers = $pdo->query("
        SELECT admin_ID, first_Name, last_Name, email 
        FROM admissionofficer
    ")->fetchAll(PDO::FETCH_ASSOC);
}

if ($activeTab === 'reviewers') {
    $reviewers = $pdo->query("
        SELECT reviewer_ID, username, first_Name, last_Name, email 
        FROM admissionstaff
    ")->fetchAll(PDO::FETCH_ASSOC);
}

if ($activeTab === 'sponsors') {
    $sponsors = $pdo->query("
        SELECT sponsor_ID, username, first_Name, last_Name, email, sponsor_company 
        FROM sponsor
    ")->fetchAll(PDO::FETCH_ASSOC);
}

if ($activeTab === 'students') {
    $students = $pdo->query("
        SELECT ID, username, first_Name, last_Name, email 
        FROM student
    ")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>

<body class="bg-gray-50">
<?php include __DIR__ . '/alerts.php'; ?>

<div class="flex min-h-screen">
    <?php include __DIR__ . '/../../assets/components/adminSidebar.php'; ?>

    <main class="flex-1 p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Manage Users</h1>
                <p class="text-gray-500 text-sm">Manage all user accounts in the system</p>
            </div>

            <button onclick="openCreateUserModal()" class="flex items-center gap-1 px-4 py-2 bg-[#00bc7d] text-white rounded-lg hover:bg-green-700 text-sm">
                <span class="material-symbols-outlined">add</span>
                Add User
            </button>
        </div>

        <!-- SEARCH BAR -->
        <div class="mb-6 relative w-72">
            <div class="absolute inset-0 rounded-lg border shadow bg-white pointer-events-none"></div>

            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                search
            </span>

            <input
                type="text"
                placeholder="Search User..."
                class="relative w-full rounded-lg p-2 pl-10 pr-4 text-sm bg-transparent focus:ring focus:ring-blue-200 border-none z-20"
            >
        </div>

        <!-- ===================== NEW URL-BASED TABS ===================== -->

        <div class="inline-flex bg-gray-100 rounded-md p-1 text-sm mb-5 font-semibold text-gray-700 gap-2">

            <a href="?tab=admin"
               class="px-3 py-1 rounded-lg transition <?= $activeTab === 'admin' ? 'bg-white shadow' : '' ?>">
               Admin
            </a>

            <a href="?tab=reviewers"
               class="px-3 py-1 rounded-lg transition <?= $activeTab === 'reviewers' ? 'bg-white shadow' : '' ?>">
               Reviewers
            </a>

            <a href="?tab=sponsors"
               class="px-3 py-1 rounded-lg transition <?= $activeTab === 'sponsors' ? 'bg-white shadow' : '' ?>">
               Sponsors
            </a>

            <a href="?tab=students"
               class="px-3 py-1 rounded-lg transition <?= $activeTab === 'students' ? 'bg-white shadow' : '' ?>">
               Students
            </a>

        </div>

        <!-- ===================== CONTENT ===================== -->

        <div class="mt-4">

            <!-- ADMIN TAB -->
            <?php if ($activeTab === 'admin'): ?>
                <?php foreach ($adminUsers as $admin): ?>
                    <div class="bg-white hover:bg-gray-50 p-5 border rounded-xl flex justify-between items-center shadow mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-full">
                                <span class="material-symbols-outlined">shield_person</span>
                            </div>
                            <div>
                                <h3 class="font-semibold"><?= htmlspecialchars($admin['first_Name'] . ' ' . $admin['last_Name']) ?></h3>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($admin['email']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- REVIEWERS TAB -->
            <?php if ($activeTab === 'reviewers'): ?>
                <?php foreach ($reviewers as $rev): ?>
                    <div class="bg-white hover:bg-gray-50 p-5 border rounded-xl flex justify-between items-center shadow mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-full">
                                <span class="material-symbols-outlined">shield_person</span>
                            </div>
                            <div>
                                <h3 class="font-semibold"><?= htmlspecialchars($rev['first_Name'] . ' ' . $rev['last_Name']) ?></h3>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($rev['email']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="material-symbols-outlined text-blue-600 cursor-pointer"
                                onclick="openEditModal(
                                    'reviewer',
                                    '<?= $rev['reviewer_ID'] ?>',
                                    '<?= htmlspecialchars($rev['username']) ?>'
                                )">edit</button>

                            <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                onclick="openDeleteModal(
                                    '<?= htmlspecialchars($rev['first_Name'] . ' ' . $rev['last_Name']) ?>',
                                    '/Scholarship/app/controllers/admin/deleteUser.php?type=reviewer&id=<?= $rev['reviewer_ID'] ?>'
                                )">delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- SPONSORS TAB -->
            <?php if ($activeTab === 'sponsors'): ?>
                <?php foreach ($sponsors as $s): ?>
                    <div class="bg-white hover:bg-gray-50 p-5 border rounded-xl flex justify-between items-center shadow mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-full">
                                <span class="material-symbols-outlined">shield_person</span>
                            </div>
                            <div>
                                <h3 class="font-semibold"><?= htmlspecialchars($s['first_Name'] . ' ' . $s['last_Name']) ?></h3>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($s['email']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="material-symbols-outlined text-blue-600 cursor-pointer"
                                onclick="openEditModal(
                                    'sponsor',
                                    '<?= $s['sponsor_ID'] ?>',
                                    '<?= htmlspecialchars($s['username']) ?>'
                                )">edit</button>

                            <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                onclick="openDeleteModal(
                                    '<?= htmlspecialchars($s['first_Name'] . ' ' . $s['last_Name']) ?>',
                                    '/Scholarship/app/controllers/admin/deleteUser.php?type=sponsor&id=<?= $s['sponsor_ID'] ?>'
                                )">delete</button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- STUDENTS TAB -->
            <?php if ($activeTab === 'students'): ?>
                <?php foreach ($students as $st): ?>
                    <div class="bg-white hover:bg-gray-50 p-5 border rounded-xl flex justify-between items-center shadow mb-3">

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-full">
                                <span class="material-symbols-outlined">shield_person</span>
                            </div>

                            <div>
                                <h3 class="font-semibold"><?= htmlspecialchars($st['first_Name'] . ' ' . $st['last_Name']) ?></h3>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($st['email']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="material-symbols-outlined text-blue-600 cursor-pointer"
                                onclick="openEditModal(
                                    'student',
                                    '<?= $st['ID'] ?>',
                                    '<?= htmlspecialchars($st['username']) ?>'
                                )">edit</button>

                            <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                onclick="openDeleteModal(
                                    '<?= htmlspecialchars($st['first_Name'] . ' ' . $st['last_Name']) ?>',
                                    '/Scholarship/app/controllers/admin/deleteUser.php?type=student&id=<?= $st['ID'] ?>'
                                )">delete</button>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>


        <!-- CREATE, DELETE, EDIT modals (unchanged, same as your version) -->
        <!-- I keep them exactly as-is to avoid breaking anything -->

        <!-- CREATE USER MODAL -->
        <div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-[450px] rounded-xl shadow-lg p-6 relative">
                <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                        onclick="closeCreateUserModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <h2 class="text-xl font-semibold mb-4">Create User</h2>

                <form action="/Scholarship/app/controllers/admin/createUser.php" method="POST" class="space-y-3">

                    <div>
                        <label class="text-sm font-medium">Username</label>
                        <input type="text" name="username" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">First Name</label>
                        <input type="text" name="firstname" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" name="email" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Role</label>
                        <select name="role" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                            <option value="">Select role...</option>
                            <option value="admin_officer">Admission Officer</option>
                            <option value="admin_staff">Admission Staff</option>
                            <option value="sponsor">Sponsor</option>
                            <option value="student">Student</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeCreateUserModal()"
                                class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>

                        <button type="submit" name="createUser"
                                class="px-4 py-2 rounded-lg bg-[#00bc7d] text-white hover:bg-green-700">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-[420px] rounded-xl shadow-lg p-6 relative">

                <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                        onclick="closeDeleteModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <h2 class="text-xl font-semibold mb-1">Delete User</h2>

                <p class="text-gray-600 text-sm mb-6">
                    Are you sure you want to delete 
                    "<span id="deleteUserName" class="font-semibold"></span>"?
                    This action cannot be undone.
                </p>

                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()"
                            class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button id="confirmDeleteBtn"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- EDIT USER MODAL -->
        <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div class="bg-white w-[420px] rounded-xl shadow-lg p-6 relative">

                <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                        onclick="closeEditModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <h2 class="text-xl font-semibold mb-4">Edit User Login</h2>

                <form id="editUserForm" method="POST" action="/Scholarship/app/controllers/admin/updateUser.php" class="space-y-3">
                    <input type="hidden" name="id" id="editUserId">
                    <input type="hidden" name="type" id="editUserType">

                    <div>
                        <label class="text-sm font-medium">Username</label>
                        <input type="text" name="username" id="editUsername" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-medium">New Password</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>

                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- JS -->
        <script>
            function openDeleteModal(name, deleteUrl) {
                document.getElementById('deleteUserName').innerText = name;
                document.getElementById('confirmDeleteBtn')
                    .setAttribute("onclick", `location.href='${deleteUrl}'`);
                document.getElementById('deleteModal').classList.remove('hidden');
            }
            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            function openCreateUserModal() {
                document.getElementById('createUserModal').classList.remove('hidden');
            }
            function closeCreateUserModal() {
                document.getElementById('createUserModal').classList.add('hidden');
            }

            function openEditModal(type, id, username) {
                document.getElementById("editUserId").value = id;
                document.getElementById("editUserType").value = type;
                document.getElementById("editUsername").value = username;

                document.getElementById("editUserModal").classList.remove("hidden");
            }

            function closeEditModal() {
                document.getElementById("editUserModal").classList.add("hidden");
            }
        </script>

    </main>
</div>

</body>
</html>
