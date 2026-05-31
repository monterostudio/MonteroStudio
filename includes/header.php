<?php
/**
 * MonteroStudio - Shared Header Component
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/includes/db.php';

$pageTitle = htmlspecialchars(DB::getSetting('site_title', 'MONTERO STUDIO - DISEÑO VISUAL & DESARROLLO WEB'), ENT_QUOTES, 'UTF-8');
$pageDesc = htmlspecialchars(DB::getSetting('site_description', 'MONTERO STUDIO es un portafolio profesional de diseño visual de alto nivel y desarrollo web limpio, rápido y seguro.'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo $pageDesc; ?>">
    <meta name="keywords" content="MONTERO STUDIO, Diseño Visual, Desarrollo Web, SPA, Freelancer, Branding, UI/UX, PHP, Tailwind CSS, Laragon">
    <meta name="author" content="MONTERO STUDIO">
    
    <!-- Open Graph (Facebook/LinkedIn) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $pageDesc; ?>">
    <meta property="og:url" content="http://localhost/MonteroStudio/">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $pageDesc; ?>">

    <!-- Anti-Clickjacking headers & Content Security Policy (CSP) -->
    <!-- Note: style-src 'unsafe-inline' is required for Tailwind CSS 4.0 runtime compiler to function in browser -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://unpkg.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com; font-src 'self' https://fonts.gstatic.com https://unpkg.com; img-src 'self' data:; connect-src 'self'; frame-ancestors 'self';">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Boxicons (Premium clean vector icons) -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Tailwind CSS 4.0 Browser Compiler (CDN version to support No-Terminal setup) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Custom Style System (Animations, Glassmorphism, Custom Scrollbars, Cinema Mode) -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-[#030712] text-slate-100 min-h-screen relative overflow-x-hidden selection:bg-blue-600 selection:text-white">


    <!-- Background Canvas for Particles -->
    <canvas id="particles-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-0"></canvas>

    <!-- Global Layout Wrapper -->
    <div class="relative z-10 flex flex-col min-h-screen justify-between">

        <!-- Header / Navigation -->
        <header class="sticky top-0 w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="glass-panel px-6 py-4 flex items-center justify-between border-slate-800/40 bg-slate-950/60 shadow-lg">
                    <!-- Brand Logo -->
                    <a href="#home" class="flex items-center space-x-2 text-white group cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-lg font-bold tracking-tight text-white uppercase">MONTERO STUDIO</span>
                            <span class="text-[10px] tracking-widest text-slate-400 font-semibold uppercase leading-none">DISEÑO VISUAL & DESARROLLO WEB</span>
                        </div>
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="nav-link text-sm font-medium tracking-wide text-slate-300 hover:text-white transition-colors duration-200">Inicio</a>
                        <a href="#about" class="nav-link text-sm font-medium tracking-wide text-slate-300 hover:text-white transition-colors duration-200">Sobre Mí</a>
                        <a href="#services" class="nav-link text-sm font-medium tracking-wide text-slate-300 hover:text-white transition-colors duration-200">Servicios</a>
                        <a href="#portfolio" class="nav-link text-sm font-medium tracking-wide text-slate-300 hover:text-white transition-colors duration-200">Portafolio</a>
                        <a href="#contact" class="nav-link text-sm font-medium tracking-wide text-slate-300 hover:text-white transition-colors duration-200">Contacto</a>
                    </div>

                    <!-- Call To Action (CTA) Header Button -->
                    <div class="hidden md:block">
                        <a href="#contact" class="magnetic-button relative inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-xs font-semibold uppercase tracking-wider rounded-lg group bg-gradient-to-br from-blue-600 to-indigo-800 text-white hover:text-white focus:ring-2 focus:outline-none focus:ring-blue-800 transition-all duration-300">
                            <span class="magnetic-inner relative px-5 py-2.5 transition-all ease-in duration-75 bg-[#030712] rounded-md group-hover:bg-opacity-0">
                                Cotizar Proyecto
                            </span>
                        </a>
                    </div>

                    <!-- Mobile Navigation Hamburger (Uses simple hash anchor navigation) -->
                    <div class="md:hidden">
                        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 text-slate-400 hover:text-white focus:outline-none" aria-label="Abrir Menú">
                            <i class="bx bx-menu-alt-right text-3xl"></i>
                        </button>
                    </div>
                </nav>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu" class="hidden md:hidden px-4 py-2 mx-4 mt-1 rounded-xl glass-panel bg-slate-950 border-slate-800/60 shadow-xl z-50">
                <div class="flex flex-col space-y-4 py-3 px-2">
                    <a href="#home" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="text-sm font-medium text-slate-300 hover:text-white py-1 border-b border-slate-800/40">Inicio</a>
                    <a href="#about" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="text-sm font-medium text-slate-300 hover:text-white py-1 border-b border-slate-800/40">Sobre Mí</a>
                    <a href="#services" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="text-sm font-medium text-slate-300 hover:text-white py-1 border-b border-slate-800/40">Servicios</a>
                    <a href="#portfolio" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="text-sm font-medium text-slate-300 hover:text-white py-1 border-b border-slate-800/40">Portafolio</a>
                    <a href="#contact" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="text-sm font-medium text-slate-300 hover:text-white py-1">Contacto</a>
                </div>
            </div>
        </header>

        <!-- Main Workspace for SPA sections -->
        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-20">
