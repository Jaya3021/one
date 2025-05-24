<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reel Life - Your Premier OTT Platform</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vimeo Player API -->
    <script src="https://player.vimeo.com/api/player.js"></script>
    <!-- Custom CSS -->
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1616530940355-351fabd46501');
            background-size: cover;
            background-position: center;
            min-height: 600px;
            display: flex;
            align-items: center;
            position: relative;
        }
        .feature-card, .category-card {
            transition: transform 0.3s ease-in-out;
        }
        .feature-card:hover, .category-card:hover {
            transform: translateY(-8px);
        }
        .carousel-item {
            transition: opacity 0.5s ease-in-out;
        }
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .dark-mode {
            background-color: #1a202c;
            color: #e2e8f0;
        }
        .dark-mode .bg-gray-900 {
            background-color: #2d3748;
        }
        .dark-mode .bg-white {
            background-color: #2d3748;
        }
        .dark-mode .text-white {
            color: #e2e8f0;
        }
        .nav-btn {
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        .vimeo-player {
            width: 100%;
            height: 300px;
        }
        .search-bar {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans transition-colors duration-300" id="themeBody">
    <!-- Navigation Bar -->
    <nav class="bg-gray-900 text-white py-4">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <a class="text-2xl font-bold" href="/">Reel Life</a>
            <div class="hidden md:flex space-x-6 items-center">
                <a href="/home" class="hover:text-red-500 transition">Home</a>
                <a href="/movies" class="hover:text-red-500 transition">Movies</a>
                <a href="/shows" class="hover:text-red-500 transition">TV Shows</a>
                <a href="/live" class="hover:text-red-500 transition">Live TV</a>
                <a href="/login" class="nav-btn bg-blue-600 hover:bg-blue-700 text-white">Login</a>
                <a href="/register" class="nav-btn bg-red-600 hover:bg-red-700 text-white">Register</a>
                <!-- Theme Toggle -->
                <button id="themeToggle" aria-label="Toggle dark mode" class="focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
            <button class="md:hidden" onclick="toggleMenu()" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-gray-800">
            <a href="/home" class="block px-4 py-2 hover:bg-gray-700">Home</a>
            <a href="/movies" class="block px-4 py-2 hover:bg-gray-700">Movies</a>
            <a href="/shows" class="block px-4 py-2 hover:bg-gray-700">TV Shows</a>
            <a href="/live" class="block px-4 py-2 hover:bg-gray-700">Live TV</a>
            <a href="/login" class="block px-4 py-2 bg-blue-600 hover:bg-blue-700">Login</a>
            <a href="/register" class="block px-4 py-2 bg-red-600 hover:bg-red-700">Register</a>
        </div>
    </nav>

    <!-- Hero Section with Vimeo Player -->
    <section class="hero-section text-white text-center fade-in">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl font-bold mb-4">Welcome to Reel Life</h1>
            <p class="text-xl mb-6">Your gateway to unlimited entertainment - movies, shows, and live TV!</p>
            <!-- Vimeo Player for Featured Trailer -->
            <div class="vimeo-player mb-6">
                <!-- Using a publicly accessible Vimeo video as a fallback; replace with your own video ID -->
                <iframe src="https://player.vimeo.com/video/76979871?autoplay=0&loop=1&muted=1" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
            </div>
            <a href="/register" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition text-lg">Start Your Free Trial</a>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="py-6 bg-gray-200">
        <div class="container mx-auto px-4">
            <div class="search-bar">
                <input type="text" class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Search for movies, shows, and more..." />
            </div>
        </div>
    </section>

    <!-- Trending Carousel with Unsplash Images -->
    <section class="py-12 bg-gray-200">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Trending Now</h2>
            <div id="trendingCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1536440136628-849c177e76ff" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 1">
                                <h4 class="font-semibold">Action Thriller</h4>
                                <p>A high-octane action movie to keep you on the edge!</p>
                            </div>
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1593642532400-2682810df593" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 2">
                                <h4 class="font-semibold">Romantic Drama</h4>
                                <p>A heartwarming tale of love and loss.</p>
                            </div>
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 3">
                                <h4 class="font-semibold">Sci-Fi Adventure</h4>
                                <p>Explore the unknown in this epic sci-fi journey.</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1580130775562-0de5a77d7167" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 4">
                                <h4 class="font-semibold">Comedy Special</h4>
                                <p>Laugh out loud with this stand-up comedy special.</p>
                            </div>
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 5">
                                <h4 class="font-semibold">Documentary</h4>
                                <p>Uncover the truth in this gripping documentary.</p>
                            </div>
                            <div class="bg-gray-300 p-4 rounded-lg">
                                <img src="https://images.unsplash.com/photo-1560165705-121119734818" class="w-full h-48 object-cover rounded mb-3" alt="Trending Movie 6">
                                <h4 class="font-semibold">Fantasy Epic</h4>
                                <p>Embark on a magical journey in a fantasy world.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Featured Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="category-card bg-red-100 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold mb-2">Action</h3>
                    <p>High-energy movies to get your adrenaline pumping.</p>
                </div>
                <div class="category-card bg-blue-100 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold mb-2">Drama</h3>
                    <p>Compelling stories that tug at your heartstrings.</p>
                </div>
                <div class="category-card bg-green-100 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold mb-2">Comedy</h3>
                    <p>Light-hearted fun to brighten your day.</p>
                </div>
                <div class="category-card bg-purple-100 p-6 rounded-lg text-center">
                    <h3 class="text-xl font-semibold mb-2">Sci-Fi</h3>
                    <p>Futuristic adventures in uncharted worlds.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Why Reel Life?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Multi-Device Streaming</h3>
                    <p>Watch on your phone, tablet, laptop, or smart TV with seamless syncing.</p>
                </div>
                <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Exclusive Originals</h3>
                    <p>Discover award-winning series and movies exclusive to Reel Life.</p>
                </div>
                <div class="feature-card bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Ad-Free Experience</h3>
                    <p>Enjoy uninterrupted streaming with no advertisements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-red-600 text-white text-center py-12">
        <div class="container mx-auto px-4">
            <h3 class="text-2xl font-bold mb-4">Ready for Unlimited Entertainment?</h3>
            <p class="mb-6">Join millions of subscribers and start your Reel Life journey!</p>
            <a href="/register" class="bg-white text-red-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition text-lg">Get Started Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p>© 2025 Reel Life. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a href="#" class="hover:text-red-500">Facebook</a>
                    <a href="#" class="hover:text-red-500">Twitter</a>
                    <a href="#" class="hover:text-red-500">Instagram</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        // Mobile Menu Toggle
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.getElementById('themeBody');
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            themeToggle.innerHTML = isDark ?
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>' :
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>';
        });
    </script>