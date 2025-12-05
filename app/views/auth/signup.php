<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-10">

    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-5">
        <div class="mb-4">
            <button 
                type="button" 
                onclick="history.back()"
                class="text-gray-400 font-medium hover:text-gray-600 flex items-center"
            >
                <span class="mr-2">&larr;</span> Back
            </button>
        </div>

        <h4 class="text-center text-2xl font-semibold mb-6">Create Account</h4>

        <form action="../../controllers/register.php" method="POST">

            <div class="mb-4">
                <h2 class="font-medium mb-2">Register as</h2>

                <div class="w-full">

                    <!-- Student Option -->
                    <label class="flex justify-between items-center border rounded-full px-4 py-2 mb-3 cursor-pointer">
                        <span>Student</span>
                        <input type="radio" name="role" value="student" class="hidden peer" checked>
                        <span class="w-5 h-5 rounded-full border peer-checked:bg-gray-700"></span>
                    </label>

                    <!-- Sponsor Option -->
                    <label class="flex justify-between items-center border rounded-full px-4 py-2 cursor-pointer">
                        <span>Sponsor</span>
                        <input type="radio" name="role" value="sponsor" class="hidden peer">
                        <span class="w-5 h-5 rounded-full border peer-checked:bg-gray-700"></span>
                    </label>

                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">First Name</label>
                <input type="text" name="first_Name" placeholder="Enter First Name" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Last Name</label>
                <input type="text" name="last_Name" placeholder="Enter Last Name" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Middle Name</label>
                <input type="text" name="middle_Name" placeholder="Enter Middle Name"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Contact Number</label>
                <input type="text" name="contact_Number" placeholder="Enter Contact Number" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email"  placeholder="Enter Email" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- STUDENT FIELDS -->
            <div id="studentFields">

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Student ID</label>
                    <input 
                        type="text" 
                        name="student_ID" 
                        placeholder="Enter University Student ID" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Year Level</label>
                    <select name="year_Level"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">College / Department</label>
                    <input type="text" name="college_department" placeholder="Enter your Department" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Program</label>
                    <input type="text" name="program" placeholder="Enter your Program"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                </div>

            </div>

            <!-- SPONSOR FIELDS -->
            <div id="sponsorField" class="hidden mb-4">

                <label class="block mb-1 font-medium">Organization / Company</label>
                <input 
                    type="text" 
                    id="sponsorCompany"
                    name="sponsor_company"
                    placeholder="Enter your Organization"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black"
                >
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Username</label>
                <input type="text" name="username" placeholder="Enter username" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password"  placeholder="Enter password" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <button 
                type="submit" 
                class="w-full mt-4 bg-black text-white py-2 rounded-lg hover:opacity-80 transition">
                Register
            </button>

        </form>

        <p class="text-center mt-4 text-sm">
            Already have an account?
            <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
        </p>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const roleRadios = document.querySelectorAll("input[name='role']");
            const studentFields = document.getElementById("studentFields");
            const sponsorField = document.getElementById("sponsorField");

            function toggleFields() {
                const checked = document.querySelector("input[name='role']:checked");
                if (!checked) return;

                const role = checked.value;

                const studentInputs = studentFields.querySelectorAll("input, select");
                const sponsorInputs = sponsorField.querySelectorAll("input");

                if (role === "student") {
                    studentFields.classList.remove("hidden");
                    sponsorField.classList.add("hidden");

                    studentInputs.forEach(inp => inp.disabled = false);
                    sponsorInputs.forEach(inp => inp.disabled = true);
                }

                if (role === "sponsor") {
                    studentFields.classList.add("hidden");
                    sponsorField.classList.remove("hidden");

                    studentInputs.forEach(inp => inp.disabled = true);
                    sponsorInputs.forEach(inp => inp.disabled = false);
                }
            }

            roleRadios.forEach(radio => radio.addEventListener("change", toggleFields));

            toggleFields();
        });
    </script>

</body>
</html>
