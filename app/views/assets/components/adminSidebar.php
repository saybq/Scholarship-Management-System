<?php
    $current = basename($_SERVER['PHP_SELF']); 
?>

<aside class="w-60 bg-white border-r p-5 pt-4 flex flex-col text-sm">
    <h1 class="text-lg font-semibold mb-6 flex items-center gap-2">
        <img src="../../assets/img/logo.png" class="w-25">
    </h1>

    <nav class="space-y-1 grow">

        <!-- Dashboard -->
        <a href="/Scholarship/app/views/users/admin/dashboard.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-m
        <?= ($current === 'dashboard.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">dashboard</span>
            Dashboard
        </a>

        <!-- Pending -->
        <a href="/Scholarship/app/views/users/admin/pending.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-m
        <?= ($current === 'pending.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-[18px]">timer</span>
            Pending
        </a>

        <!-- Manage Scholarships -->
        <a href="/Scholarship/app/views/users/admin/manageScholarship.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-m
        <?= ($current === 'manageScholarship.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">license</span>
            Manage Scholarships
        </a>

        <!-- Manage Users -->
        <a href="/Scholarship/app/views/users/admin/manageUser.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-m
        <?= ($current === 'manageUser.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">group</span>
            Manage Users
        </a>

        <!-- Profile -->
        <a href="/Scholarship/app/views/users/admin/profile.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-m
        <?= ($current === 'profile.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">person</span>
            Profile
        </a>

    </nav>
    <a class="text-red-500 font-medium flex items-center gap-2 mt-10 px-3" href="../../../controllers/logout.php">
        <span class="material-symbols-outlined text-base leading-none">logout</span>Logout
    </a>
</aside>