
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-8">
        
        <h4 class="text-center text-2xl font-semibold mb-6">Login</h4>

        <?php if (isset($_GET['error'])): ?>
            <p class="text-red-600 text-sm mb-3 text-center">
                Incorrect username or password.
            </p>
        <?php endif; ?>

        <form action="../../controllers/login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block font-medium mb-1">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username"
                    placeholder="Enter username" 
                    required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                >
            </div>

            <div class="mb-4">
                <label for="password" class="block font-medium mb-1">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    placeholder="Enter password" 
                    required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                >
            </div>

            <button 
                type="submit" 
                class="w-full mt-4 bg-black text-white py-2 rounded-lg hover:opacity-80 transition"
            >
                Login
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Don't have an account? 
            <a href="signup.php" class="text-blue-600 hover:underline">Register here</a>
        </p>

    </div>

</body>
</html>



