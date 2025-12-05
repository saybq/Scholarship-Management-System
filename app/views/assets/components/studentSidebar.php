<?php
    $current = basename($_SERVER['PHP_SELF']); 
?>

<aside class="w-56 bg-white border-r p-5 pt-4 flex flex-col text-sm">
    <h1 class="text-lg font-semibold mb-6 flex items-center gap-2">
        <img src="../../assets/img/logo.png" class="w-25">
    </h1>

    <nav class="space-y-1 grow">

        <!-- Dashboard -->
        <a href="/Scholarship/app/views/users/student/dashboard.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-xs
        <?= ($current === 'dashboard.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">dashboard</span>
            Dashboard
        </a>

        <!-- Scholarships -->
        <a href="/Scholarship/app/views/users/student/scholarships.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-xs
        <?= ($current === 'scholarships.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">school</span>
            Scholarships
        </a>

        <!-- Applications -->
        <a href="/Scholarship/app/views/users/student/application.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-xs
        <?= ($current === 'application.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">article</span>
            Applications
        </a>

        

        <!-- Profile -->
        <a href="/Scholarship/app/views/users/student/profile.php"
        class="flex items-center gap-2 px-3 py-2 rounded-md font-medium text-xs
        <?= ($current === 'profile.php') 
                ? 'bg-green-100 text-green-700' 
                : 'hover:bg-gray-100' ?>">
            <span class="material-symbols-outlined text-base leading-none">person</span>
            Profile
        </a>

    </nav>
    <a class="text-red-500 font-medium flex items-center gap-2 mt-10" href="../../../controllers/logout.php">
        <span class="material-symbols-outlined text-base leading-none">logout</span>Logout
    </a>
</aside>