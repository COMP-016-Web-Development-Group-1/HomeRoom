<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HomeRoom | Streamline Your Rental Experience</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .nav-underline {
            transition: left 0.3s cubic-bezier(.4,0,.2,1), width 0.3s cubic-bezier(.4,0,.2,1);
        }
        .navbar-gradient-bg {
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0.97) 0%,
                rgba(255,255,255,0.97) 40%,
                rgba(255,255,255,0.80) 60%,
                rgba(255,255,255,0.25) 90%,
                rgba(255,255,255,0.01) 100%
            );
            background-repeat: no-repeat;
        }
        .navbar-link {
            text-decoration: none !important;
        }
        .navbar-link:hover,
        .navbar-link:focus {
            text-decoration: none !important;
        }
        #home {
            min-height: calc(100vh - 80px);
        }
    </style>
</head>
<body class="bg-[#E0FFE0] text-[#1b3a2b] font-sans">
    <!-- Navbar -->
    <header class="fixed top-0 left-0 w-full z-50 navbar-gradient-bg" style="height: 80px;">
        <nav id="main-navbar"
            class="relative max-w-5xl mx-auto flex items-center justify-center py-5 px-4 h-full">
            <!-- Left Nav -->
            <div class="flex items-center gap-10" id="navbar-left">
                <x-a href="#home" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2" data-nav="home">Home</x-a>
                <x-a href="#services" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2" data-nav="services">Services</x-a>
                <x-a href="#about" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2 whitespace-nowrap" data-nav="about">About Us</x-a>
            </div>
            <!-- Logo (clickable) -->
            <div class="flex items-center justify-center px-12">
                <a href="{{ url()->current() }}">
                    <img src="{{ asset('assets/images/logo_text.svg') }}" alt="HomeRoom Logo"
                        class="h-14 w-auto object-contain whitespace-nowrap" style="max-width:190px; min-width:120px;" />
                </a>
            </div>
            <!-- Right Nav -->
            <div class="flex items-center gap-10" id="navbar-right">
                <x-a href="#team" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2 whitespace-nowrap" data-nav="team">Our Team</x-a>
                @if (Route::has('login'))
                    @auth
                        <x-a href="{{ url('/dashboard') }}" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2" data-nav="dashboard">Dashboard</x-a>
                    @else
                        <x-a href="{{ route('login') }}" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2" data-nav="login">Login</x-a>
                        @if (Route::has('register'))
                            <x-a href="{{ route('register') }}" variant="text" :uppercase="false" class="navbar-link text-[1.12rem] px-5 py-2" data-nav="register">Register</x-a>
                        @endif
                    @endauth
                @endif
            </div>
            <!-- Underline -->
            <div id="navbar-underline" class="nav-underline absolute bottom-0 h-1 bg-[#55C58F] rounded transition-all" style="left:0;width:0;"></div>
        </nav>
    </header>
    <div class="pt-[80px]"></div>

    <!-- Home Section -->
    <section id="home" class="relative flex items-center justify-center w-full overflow-hidden" style="min-height:calc(100vh - 80px);">
        <!-- Fullscreen placeholder image, covers the whole section -->
        <img
            src="{{ Vite::asset('resources/assets/images/welcome-page-bg.jpg') }}"
            alt="Home Background"
            class="absolute inset-0 w-full h-full object-cover z-0"
            draggable="false"
        />
        <!-- Optionally, overlay text/buttons here -->
        <div class="relative z-10 flex flex-col items-center justify-center w-full h-full"></div>
    </section>

    <!-- The rest of the page goes here... -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navbar = document.getElementById('main-navbar');
            const underline = document.getElementById('navbar-underline');
            const links = Array.from(document.querySelectorAll('.navbar-link'));

            function setUnderlineTo(link) {
                const rect = link.getBoundingClientRect();
                const navbarRect = navbar.getBoundingClientRect();
                underline.style.left = (rect.left - navbarRect.left) + "px";
                underline.style.width = rect.width + "px";
            }

            function updateActiveLinkOnScroll() {
                const sections = ['home', 'services', 'about', 'team'];
                let indexActive = 0;
                for (let i = 0; i < sections.length; i++) {
                    const el = document.getElementById(sections[i]);
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        if (rect.top <= 90) indexActive = i;
                    }
                }
                const activeSection = sections[indexActive];
                const activeLink = links.find(l => l.dataset.nav === activeSection);
                if (activeLink) {
                    links.forEach(l => l.classList.remove('text-[#55C58F]', 'font-bold'));
                    activeLink.classList.add('text-[#55C58F]', 'font-bold');
                    setUnderlineTo(activeLink);
                }
            }

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href').substring(1);
                        const target = document.getElementById(targetId);
                        if (target) {
                            window.scrollTo({
                                top: target.offsetTop - 75,
                                behavior: 'smooth'
                            });
                            setUnderlineTo(this);
                            links.forEach(l => l.classList.remove('text-[#55C58F]', 'font-bold'));
                            this.classList.add('text-[#55C58F]', 'font-bold');
                        }
                    }
                });
            });

            const initialActive = links.find(l => l.dataset.nav === 'home');
            if (initialActive) setUnderlineTo(initialActive);

            window.addEventListener('scroll', updateActiveLinkOnScroll, { passive: true });
            window.addEventListener('resize', function () {
                const current = document.querySelector('.navbar-link.text-[#55C58F]');
                if (current) setUnderlineTo(current);
            });
        });
    </script>
</body>
</html>
