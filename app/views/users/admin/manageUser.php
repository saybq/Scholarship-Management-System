<?php
    require_once __DIR__ . "../../../../core/dbconnection.php";
    session_start();
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
            header("Location: /Scholarship/app/views/auth/login.php");
            exit;
    }

    // Check correct role
    if ($_SESSION["role"] !== "admin") {
        header("Location: /Scholarship/app/views/auth/login.php");
        exit;
    }

    // FETCH ADMISSION OFFICERS (Admin)
    $queryAdmin = $pdo->query("
        SELECT admin_ID, first_Name, last_Name, email 
        FROM admissionofficer
    ");
    $adminUsers = $queryAdmin->fetchAll(PDO::FETCH_ASSOC);

    // FETCH ADMISSION STAFF (Reviewers)
    $queryReviewers = $pdo->query("
        SELECT reviewer_ID, username, first_Name, last_Name, email 
        FROM admissionstaff
    ");
    $reviewers = $queryReviewers->fetchAll(PDO::FETCH_ASSOC);

    // FETCH SPONSORS
    $querySponsors = $pdo->query("
        SELECT sponsor_ID, username, first_Name, last_Name, email, sponsor_company 
        FROM sponsor
    ");
    $sponsors = $querySponsors->fetchAll(PDO::FETCH_ASSOC);

    // FETCH STUDENTS
    $queryStudents = $pdo->query("
        SELECT ID, username, first_Name, last_Name, email 
        FROM student
    ");
    $students = $queryStudents->fetchAll(PDO::FETCH_ASSOC);

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

            <!-- ===================== TABS ===================== -->

            <input type="radio" name="tab" id="tab-admin" class="hidden" checked>
            <input type="radio" name="tab" id="tab-reviewers" class="hidden">
            <input type="radio" name="tab" id="tab-sponsors" class="hidden">
            <input type="radio" name="tab" id="tab-students" class="hidden">

            <!-- TAB BUTTONS -->
            <div class="inline-flex bg-gray-100 rounded-md p-1 text-sm mb-5 font-semibold text-gray-700 gap-2">
                <label for="tab-admin" class="tab-item cursor-pointer px-3 py-1 rounded-lg transition">Admin</label>
                <label for="tab-reviewers" class="tab-item cursor-pointer px-3 py-1 rounded-lg transition">Reviewers</label>
                <label for="tab-sponsors" class="tab-item cursor-pointer px-3 py-1 rounded-lg transition">Sponsors</label>
                <label for="tab-students" class="tab-item cursor-pointer px-3 py-1 rounded-lg transition">Students</label>
            </div>

            <!-- ===================== TAB CONTENTS ===================== -->

            <div class="mt-4">

                <!-- ADMIN TAB -->
                <div class="tab-admin">
                    <?php foreach ($adminUsers as $admin): ?>
                        <div class="bg-white p-5 border rounded-xl flex justify-between items-center shadow mb-3">

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
                </div>
            
                <!-- REVIEWERS TAB -->
                <div class="tab-reviewers hidden">
                    <?php foreach ($reviewers as $rev): ?>
                        <div class="bg-white p-5 border rounded-xl flex justify-between items-center shadow mb-3">

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
                                    )">edit
                                </button>

                                <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                    onclick="openDeleteModal(
                                    '<?= htmlspecialchars($rev['first_Name'] . ' ' . $rev['last_Name']) ?>',
                                    '/Scholarship/app/controllers/admin/deleteUser.php?type=reviewer&id=<?= $rev['reviewer_ID'] ?>')">delete
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- SPONSORS TAB -->
                <div class="tab-sponsors hidden">
                    <?php foreach ($sponsors as $s): ?>
                        <div class="bg-white p-5 border rounded-xl flex justify-between items-center shadow mb-3">

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
                                    )">edit
                                </button>

                                <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                    onclick="openDeleteModal(
                                        '<?= htmlspecialchars($s['first_Name'] . ' ' . $s['last_Name']) ?>',
                                        '/Scholarship/app/controllers/admin/deleteUser.php?type=sponsor&id=<?= $s['sponsor_ID'] ?>')">delete
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>


                <!-- STUDENTS TAB -->
                <div class="tab-students hidden">
                    <?php foreach ($students as $st): ?>
                        <div class="bg-white p-5 border rounded-xl flex justify-between items-center shadow mb-3">

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
                                    )">edit
                                </button>

                                <button class="material-symbols-outlined text-red-500 cursor-pointer"
                                    onclick="openDeleteModal(
                                        '<?= htmlspecialchars($st['first_Name'] . ' ' . $st['last_Name']) ?>',
                                        '/Scholarship/app/controllers/admin/deleteUser.php?type=student&id=<?= $st['ID'] ?>')">delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


            </div>
            <!-- ========================================= -->
            <!-- CREATE USER MODAL -->
            <!-- ========================================= -->
            <div id="createUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

                <div class="bg-white w-[450px] rounded-xl shadow-lg p-6 relative">

                    <!-- Close Button -->
                    <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                            onclick="closeCreateUserModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <h2 class="text-xl font-semibold mb-4">Create User</h2>

                    <!-- FORM -->
                    <form action="/Scholarship/app/controllers/admin/createUser.php" method="POST" class="space-y-3">
                        
                        <!-- Username -->
                        <div>
                            <label class="text-sm font-medium">Username</label>
                            <input type="text" name="username" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                        </div>

                        <!-- First Name -->
                        <div>
                            <label class="text-sm font-medium">First Name</label>
                            <input type="text" name="firstname" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                        </div>

                        <!-- Role -->
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

                        <!-- Footer Buttons -->
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

            <!-- ========================================= -->
            <!-- DELETE CONFIRMATION MODAL -->
            <!-- ========================================= -->
            <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

                <div class="bg-white w-[420px] rounded-xl shadow-lg p-6 relative">
                    
                    <!-- Close Button -->
                    <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                            onclick="closeDeleteModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <!-- Title -->
                    <h2 class="text-xl font-semibold mb-1">Delete User</h2>

                    <!-- Message -->
                    <p class="text-gray-600 text-sm mb-6">
                        Are you sure you want to delete 
                        "<span id="deleteUserName" class="font-semibold"></span>"?
                        This action cannot be undone.
                    </p>

                    <!-- Buttons -->
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

            <!-- ========================================= -->
            <!-- EDIT USER MODAL -->
            <!-- ========================================= -->
            <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

                <div class="bg-white w-[420px] rounded-xl shadow-lg p-6 relative">
                    
                    <!-- Close Button -->
                    <button class="absolute top-3 right-3 text-gray-500 hover:text-black"
                            onclick="closeEditModal()">
                        <span class="material-symbols-outlined">close</span>
                    </button>

                    <h2 class="text-xl font-semibold mb-4">Edit User Login</h2>

                    <form id="editUserForm" method="POST" action="/Scholarship/app/controllers/admin/updateUser.php" class="space-y-3">

                        <!-- hidden -->
                        <input type="hidden" name="id" id="editUserId">
                        <input type="hidden" name="type" id="editUserType">

                        <!-- Username -->
                        <div>
                            <label class="text-sm font-medium">Username</label>
                            <input type="text" name="username" id="editUsername" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-[#00bc7d] focus:border-[#00bc7d] text-sm">
                        </div>

                        <!-- Password -->
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

        


            <!-- ========== TAB LOGIC CSS ========== -->
            <style>
                /* Highlight active tab */
                #tab-admin:checked ~ .inline-flex label[for="tab-admin"],
                #tab-reviewers:checked ~ .inline-flex label[for="tab-reviewers"],
                #tab-sponsors:checked ~ .inline-flex label[for="tab-sponsors"],
                #tab-students:checked ~ .inline-flex label[for="tab-students"] {
                    background-color: white;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                }

                /* Show/hide tab contents */
                #tab-admin:checked ~ .mt-4 .tab-admin { display: block; }
                #tab-admin:checked ~ .mt-4 .tab-reviewers,
                #tab-admin:checked ~ .mt-4 .tab-sponsors,
                #tab-admin:checked ~ .mt-4 .tab-students { display: none; }

                #tab-reviewers:checked ~ .mt-4 .tab-reviewers { display: block; }
                #tab-reviewers:checked ~ .mt-4 .tab-admin,
                #tab-reviewers:checked ~ .mt-4 .tab-sponsors,
                #tab-reviewers:checked ~ .mt-4 .tab-students { display: none; }

                #tab-sponsors:checked ~ .mt-4 .tab-sponsors { display: block; }
                #tab-sponsors:checked ~ .mt-4 .tab-admin,
                #tab-sponsors:checked ~ .mt-4 .tab-reviewers,
                #tab-sponsors:checked ~ .mt-4 .tab-students { display: none; }

                #tab-students:checked ~ .mt-4 .tab-students { display: block; }
                #tab-students:checked ~ .mt-4 .tab-admin,
                #tab-students:checked ~ .mt-4 .tab-reviewers,
                #tab-students:checked ~ .mt-4 .tab-sponsors { display: none; }
            </style>

            <!-- ========================================= -->
            <!-- JS TO OPEN / CLOSE MODAL -->
            <!-- ========================================= -->

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

                ///
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

