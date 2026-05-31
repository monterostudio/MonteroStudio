<?php
/**
 * MonteroStudio - Hero / Welcome Section
 */
?>
<section id="home" class="spa-section active flex flex-col justify-center items-center min-h-[80vh] py-16 text-center relative overflow-hidden">
    
    <!-- Background Radial Neon Spotlight -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-[300px] h-[300px] bg-indigo-900/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 flex flex-col items-center">
        
        <!-- Freelance / Agency Badge -->
        <div class="inline-flex items-center space-x-2 px-3 py-1.5 mb-6 rounded-full border border-blue-500/20 bg-blue-950/20 text-blue-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md animate-pulse">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            <span>Disponible para nuevos proyectos</span>
        </div>

        <!-- High-Impact Main Heading -->
        <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]">
            <?php echo htmlspecialchars(DB::getSetting('hero_title_part1', 'Transformo Ideas en'), ENT_QUOTES, 'UTF-8'); ?> <br>
            <span class="bg-gradient-to-r from-blue-500 via-indigo-400 to-blue-300 bg-clip-text text-transparent animate-glow">
                <?php echo htmlspecialchars(DB::getSetting('hero_title_part2', 'Experiencias Digitales'), ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </h1>

        <!-- Subheading -->
        <p class="text-base sm:text-lg md:text-xl text-slate-400 max-w-2xl mb-10 leading-relaxed font-light">
            <?php echo htmlspecialchars(DB::getSetting('hero_description', 'Hola, soy MONTERO STUDIO. Combino la precisión del desarrollo web con la magia del diseño visual para crear portales rápidos, interactivos y funcionales.'), ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <!-- Call-to-Actions (CTAs) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
            <!-- Explore Projects button -->
            <a href="#portfolio" class="magnetic-button relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-bold uppercase tracking-wider rounded-xl group bg-gradient-to-br from-blue-600 to-indigo-800 text-white w-full sm:w-auto transition-all duration-300">
                <span class="magnetic-inner relative px-8 py-4 transition-all ease-in duration-75 bg-[#030712] rounded-lg group-hover:bg-opacity-0 flex items-center justify-center gap-2">
                    Explorar Trabajos <i class="bx bx-right-arrow-alt text-lg"></i>
                </span>
            </a>

            <!-- Immersive Cinema Showcase trigger -->
            <button class="open-cinema-btn px-8 py-4 border border-slate-800/80 hover:border-blue-500/40 bg-slate-950/40 hover:bg-slate-900/50 text-slate-300 hover:text-white font-bold rounded-xl backdrop-blur-md transition-all duration-300 flex items-center justify-center gap-2 w-full sm:w-auto cursor-pointer shadow-lg hover:shadow-blue-900/10" data-project-index="0">
                <i class="bx bx-play-circle text-2xl text-blue-500"></i>
                <span>Ver Modo Cine</span>
            </button>
        </div>

        <!-- Minimal Interactive Indicator -->
        <div class="mt-20 animate-bounce">
            <a href="#about" class="text-slate-500 hover:text-blue-400 transition-colors" aria-label="Desplazarse hacia abajo">
                <i class="bx bx-chevron-down text-3xl"></i>
            </a>
        </div>

    </div>

    <!-- Floating UI Tech Decorative Elements (Vite/Tailwind-style particles) -->
    <div class="absolute top-1/4 left-10 opacity-15 hidden lg:block animate-float">
        <div class="glass-panel p-3 border-slate-700/40 text-blue-500 text-3xl">
            <i class="bx bxl-tailwind-css"></i>
        </div>
    </div>
    <div class="absolute bottom-1/4 right-12 opacity-15 hidden lg:block animate-float" style="animation-delay: 2s;">
        <div class="glass-panel p-3 border-slate-700/40 text-blue-400 text-3xl">
            <i class="bx bxl-php"></i>
        </div>
    </div>
    <div class="absolute top-1/3 right-1/4 opacity-10 hidden lg:block animate-float" style="animation-delay: 4s;">
        <div class="glass-panel p-2.5 border-slate-700/40 text-white text-xl">
            <i class="bx bxl-javascript"></i>
        </div>
    </div>
</section>
