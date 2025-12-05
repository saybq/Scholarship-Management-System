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
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
</head>
<body class="bg-gray-50 ">

    <div class="flex min-h-screen">

        <?php include __DIR__ . '/../../assets/components/reviewerSidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- PAGE TITLE -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold">My Profile</h2>
                <p class="text-gray-500 text-sm">Manage your reviewer account information</p>
            </div>

            <!-- PROFILE PICTURE SECTION -->
            <div class="bg-white border rounded-xl p-6 mb-6 shadow">
                <p class="font-semibold">Profile Picture</p>
                <p class="text-gray-500 text-sm mb-4">Update your profile photo</p>

                <div class="flex flex-col items-center">
                    <div class="w-28 h-28 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-4xl">
                        <span class="material-symbols-outlined">person</span>
                    </div>

                    <button class="mt-4 px-4 py-2 border rounded-md flex items-center gap-2 text-sm hover:bg-gray-50">
                        <span class="material-symbols-outlined text-base">photo_camera</span>
                        Change Photo
                    </button>
                </div>
            </div>

            <!-- PERSONAL INFORMATION -->
            <div class="bg-white border rounded-xl p-6 mb-6 shadow">

                <p class="font-semibold">Personal Information</p>
                <p class="text-gray-500 text-sm mb-4">Update your personal details</p>

                <form action="../../../controllers/reviewer/updateprofile.php" method="POST" class="space-y-4">

                    <!-- USERNAME -->
                    <div>
                        <label class="block text-sm font-semibold">Username</label>
                        <input 
                            type="text" 
                            name="username"
                            class="w-full border p-2 rounded mt-1 text-xs" 
                            value="<?//= htmlspecialchars($data['username']); ?>">
                    </div>

                    <!-- NAME GRID -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold">First Name</label>
                            <input 
                                type="text" 
                                name="first_Name"
                                class="w-full border p-2 rounded mt-1 text-xs" 
                                value="<?//= htmlspecialchars($data['first_Name']); ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold">Middle Name</label>
                            <input 
                                type="text" 
                                name="middle_Name"
                                class="w-full border p-2 rounded mt-1 text-xs" 
                                value="<?//= htmlspecialchars($data['middle_Name']); ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold">Last Name</label>
                            <input 
                                type="text" 
                                name="last_Name"
                                class="w-full border p-2 rounded mt-1 text-xs" 
                                value="<?//= htmlspecialchars($data['last_Name']); ?>">
                        </div>
                    </div>

                    <!-- EMAIL + CONTACT -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold">Email</label>
                            <input 
                                type="email" 
                                name="email"
                                class="w-full border p-2 rounded mt-1 text-xs" 
                                value="<?//= htmlspecialchars($data['email']); ?>">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold">Contact Number</label>
                            <input 
                                type="text" 
                                name="contact_Number"
                                class="w-full border p-2 rounded mt-1 text-xs"  
                                value="<?//= htmlspecialchars($data['contact_Number']); ?>">
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-semibold">Password</label>
                        <input 
                            type="password" 
                            name="password"
                            class="w-full border p-2 rounded mt-1 text-xs"  
                            placeholder="Enter new password (optional)">
                    </div>
                </form>
            </div>

            <!-- DEPARTMENT INFORMATION -->
            <div class="bg-white border rounded-xl p-6 shadow mb-20">

                <p class="font-semibold">Department Information</p>
                <p class="text-gray-500 text-sm mb-4">Your work assignment details</p>

                <label class="block text-sm font-semibold mb-1">Department</label>
                
                <select 
                    name="department"
                    class="border p-2 rounded text-xs w-60">
                    <option <?//= $data['department'] === 'Admissions Office' ? 'selected' : '' ?>>
                        Admissions Office
                    </option>
                </select>

            </div>

            <!-- SAVE BUTTON -->
            <div class="flex justify-end mt-6">
                <button 
                    form="reviewerProfileForm"
                    class="px-4 py-2 bg-[#fbbf24] text-white rounded flex items-center gap-2 text-sm hover:bg-yellow-500">
                    <span class="material-symbols-outlined text-base">save</span>
                    Save Changes
                </button>
            </div>

        </main>
    </div>

</body>
</html>