<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aleo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/indexStyle.css">
    <title>Smart Scholar</title>
</head>
<body>

    <header>
        <nav class="navbar" id="navbar">
            <div class="logo">
                <img src="assets\img\logo.png" alt="Smart Scholar Logo">
            </div>
            <ul class="nav-links">
                <li><a href="#landingPage" class="#">HOME</a></li>
                <li><a href="#aboutUs">ABOUT</a></li>
                <li><a href="#mv">MISSION | VISSION</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="../app/views/auth/signup.php"><button class="btn signup">SIGN UP</button></a>
                <a href="../app/views/auth/login.php"><button class="btn login">LOGIN</button></a>
            </div>
        </nav>
    </header>

    <main>
        <section class="landingPage" id="landingPage">
            <div class="landingPage-text">
                <h1>WELCOME TO</h1>
                <h1 class="sms">SCHOLARSHIP MANAGEMENT SYSTEM</h1>
                <p>“Empowering students and schools through effortless scholarship access.”</p>
                <a href="../app/views/auth/login.php"><button class="apply-btn">APPLY NOW!</button></a>
            </div>

            <div class="landingPage-image">
                <img src="assets\img\section1pic.png" alt="Graduation Cap">
            </div>
        </section>
        <section class="aboutUs" id="aboutUs">
            <div class="container">
                <div class="con1">
                    <h5>ABOUT US</h5>
                    <h1>Manage Your Scholarships, Maximize Your Potential</h1>
                    <h4>Our scholarship app connects students to the right opportunities with ease. It simplifies applications, tracking, and management for both students and schools, making the scholarship process faster, fairer, and more accessible to everyone.</h4>
                </div>
                <div class="con2">
                    <img src="assets\img\about1.jpg" alt="pic1">
                </div>

                <div class="con3">
                    <img src="assets\img\about2.jpg" alt="pic2">
                </div>
                <div class="con4">
                    <img src="assets\img\about3.jpg" alt="pic4">
                </div>
                <div class="con5">
                    <p>Sponsors from LGU</p>
                    <p>10,000 students connected</p>
                    <p>3 Million + Total Funds Managed</p>
                    <p>98% Success Rate in Meeting Deadlines</p>
                </div>
            </div>
        </section>
        

        <section id="mv" class="bg-gray-50 flex items-center justify-center px-6 py-20">
            <div class="max-w-6xl w-full text-center" >

                <!-- Title -->
                <h2 class="text-2xl font-semibold mb-2">Mission and Vision</h2>
                <p class="text-gray-600 mb-12">
                    Guided by our commitment to educational equity and excellence
                </p>

                <!-- Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Mission -->
                    <div class="bg-white p-10 rounded-xl shadow-md border text-left">
                        <div class="flex items-start mb-6">
                            <div class="w-14 h-14 bg-black rounded-full flex items-center justify-center">
                                <span class="material-icons text-white text-3xl">track_changes</span>
                            </div>
                        </div>

                        <h3 class="font-semibold text-lg mb-3">Our Mission</h3>

                        <p class="text-gray-600 leading-relaxed">
                            To democratize access to educational funding by providing institutions
                            and organizations with powerful, intuitive tools that simplify scholarship
                            management. We strive to remove barriers and create seamless connections
                            between scholarship providers and deserving students, ensuring that financial
                            constraints never stand in the way of academic excellence.
                        </p>
                    </div>

                    <!-- Vision -->
                    <div class="bg-white p-10 rounded-xl shadow-md border text-left">
                        <div class="flex items-start mb-6">
                            <div class="w-14 h-14 bg-black rounded-full flex items-center justify-center">
                                <span class="material-icons text-white text-3xl">visibility</span>
                            </div>
                        </div>

                        <h3 class="font-semibold text-lg mb-3">Our Vision</h3>

                        <p class="text-gray-600 leading-relaxed">
                            To create a world where every student, regardless of their background,
                            has equal access to educational opportunities. We envision a future where
                            scholarship management is transparent, efficient, and equitable, empowering
                            the next generation of leaders, innovators, and change-makers to pursue their
                            dreams without financial limitations.
                        </p>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#1E2326] py-4 mt-10 w-full text-center">
        <p class="text-sm text-slate-300">
            © 2025 Smart Scholar Management System. All rights reserved.
        </p>
    </footer>
</body>
</html>