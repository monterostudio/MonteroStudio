<?php
/**
 * MonteroStudio - Shared Footer Component
 */
?>
        </main> <!-- End of main workspace container -->

        <!-- Footer -->
        <footer class="bg-[#02040a] border-t border-slate-900 py-12 relative z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand information -->
                    <div class="md:col-span-2 space-y-4">
                        <div class="flex flex-col text-white">
                            <span class="text-base font-bold tracking-tight text-white uppercase">MONTERO STUDIO</span>
                            <span class="text-[10px] tracking-widest text-slate-400 font-semibold uppercase leading-none mt-1">DISEÑO VISUAL & DESARROLLO WEB</span>
                        </div>
                        <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                            <?php echo htmlspecialchars(DB::getSetting('footer_description', 'Diseño visual y desarrollo web a nivel de creador independiente. Creo soluciones digitales limpias, funcionales y adaptadas a tus objetivos.'), ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <!-- Social networks buttons -->
                        <div class="flex items-center space-x-4 pt-2">
                            <a href="<?php echo htmlspecialchars(DB::getSetting('social_linkedin', '#'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="w-9 h-9 rounded-full bg-slate-900 hover:bg-blue-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="LinkedIn">
                                <i class="bx bxl-linkedin text-lg"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars(DB::getSetting('social_dribbble', '#'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="w-9 h-9 rounded-full bg-slate-900 hover:bg-blue-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Behance/Dribbble">
                                <i class="bx bxl-dribbble text-lg"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars(DB::getSetting('social_github', '#'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="w-9 h-9 rounded-full bg-slate-900 hover:bg-blue-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Github">
                                <i class="bx bxl-github text-lg"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars(DB::getSetting('social_instagram', '#'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="w-9 h-9 rounded-full bg-slate-900 hover:bg-blue-600 border border-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-all duration-300" aria-label="Instagram">
                                <i class="bx bxl-instagram text-lg"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Sitemap quicklinks -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Navegación</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#home" class="text-sm text-slate-400 hover:text-blue-400 transition-colors">Inicio</a></li>
                            <li><a href="#about" class="text-sm text-slate-400 hover:text-blue-400 transition-colors">Sobre Mí</a></li>
                            <li><a href="#services" class="text-sm text-slate-400 hover:text-blue-400 transition-colors">Servicios</a></li>
                            <li><a href="#portfolio" class="text-sm text-slate-400 hover:text-blue-400 transition-colors">Portafolio</a></li>
                            <li><a href="#contact" class="text-sm text-slate-400 hover:text-blue-400 transition-colors">Contacto</a></li>
                        </ul>
                    </div>

                    <!-- Services info -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Servicios</h4>
                        <ul class="space-y-2.5">
                            <li class="text-sm text-slate-400">Diseño Corporativo & Branding</li>
                            <li class="text-sm text-slate-400">Prototipado UI/UX en Figma</li>
                            <li class="text-sm text-slate-400">Desarrollo Web SPA & Apps</li>
                            <li class="text-sm text-slate-400">Optimización de Motores de Búsqueda</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-900/60 flex flex-col md:flex-row items-center justify-between">
                    <p class="text-xs text-slate-500 leading-none mb-4 md:mb-0">
                        &copy; <?php echo date('Y'); ?> MONTERO STUDIO. Todos los derechos reservados.
                    </p>
                    <p class="text-xs text-slate-600 flex items-center space-x-1">
                        <span>Creado con</span> <i class="bx bxs-heart text-red-500"></i> <span>y tecnología libre de fricción.</span>
                    </p>
                </div>
            </div>
        </footer>

    </div> <!-- End of Global Layout Wrapper -->

    <!-- CINEMA MODE OVERLAY STRUCTURE -->
    <div id="cinema-overlay" class="cinema-overlay flex items-center justify-center">
        <!-- Spotlight visual backdrop -->
        <div class="cinema-spotlight"></div>
        <div class="lens-flare"></div>

        <!-- Cinema Exit Button -->
        <button id="close-cinema" class="absolute top-6 right-6 text-slate-400 hover:text-white hover:scale-115 text-4xl focus:outline-none transition-all z-[1006] cursor-pointer" aria-label="Cerrar Modo Cine">
            <i class="bx bx-x"></i>
        </button>

        <!-- Widescreen Cinematic Letterbox Slider container -->
        <div class="relative w-[90%] max-w-5xl aspect-video md:aspect-[21/9] bg-black rounded-xl border border-slate-800/80 shadow-[0_0_80px_rgba(37,99,235,0.15)] overflow-hidden z-[1004] flex flex-col md:flex-row">
            
            <!-- Left part: Project Image Showcase -->
            <div class="w-full md:w-3/5 h-full relative overflow-hidden bg-slate-950">
                <img id="cinema-image" src="" alt="Cinema Showcase Media" class="w-full h-full object-cover transition-all duration-500 ease-out">
                <!-- Smooth gradient layer masking image edge -->
                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-slate-950 via-slate-950/20 to-transparent"></div>
            </div>

            <!-- Right part: Project Info -->
            <div class="w-full md:w-2/5 p-6 md:p-8 flex flex-col justify-between bg-slate-950/90 backdrop-blur-sm border-t md:border-t-0 md:border-l border-slate-900">
                <div>
                    <span id="cinema-subtitle" class="text-[10px] font-bold tracking-widest text-blue-500 uppercase mb-2 block transition-opacity duration-300">MONTERO STUDIO CINEMA</span>
                    <h2 id="cinema-title" class="text-2xl md:text-3xl font-extrabold text-white tracking-tight mb-4 transition-opacity duration-300">Proyecto Destacado</h2>
                    <p id="cinema-desc" class="text-slate-400 text-xs md:text-sm leading-relaxed mb-6 transition-opacity duration-300">Cargando detalles técnicos y objetivos del proyecto...</p>
                </div>
                
                <div>
                    <!-- Dynamic project technology tags -->
                    <div id="cinema-tags" class="flex flex-wrap gap-1.5 mb-6 transition-opacity duration-300"></div>
                    <div class="flex items-center">
                        <a id="cinema-link" href="#" target="_blank" class="magnetic-button relative inline-flex items-center justify-center p-0.5 overflow-hidden text-[10px] font-bold uppercase tracking-wider rounded-lg group bg-gradient-to-br from-blue-600 to-indigo-800 text-white transition-all duration-300">
                            <span class="magnetic-inner relative px-5 py-2.5 transition-all ease-in duration-75 bg-[#030712] rounded-md group-hover:bg-opacity-0">
                                Explorar Proyecto <i class="bx bx-right-arrow-alt ml-1 align-middle text-sm"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Carousel navigation triggers -->
        <button id="cinema-prev" class="absolute left-4 md:left-8 text-slate-500 hover:text-white hover:bg-slate-900/60 p-3 rounded-full text-3xl focus:outline-none transition-all z-[1005] cursor-pointer" aria-label="Proyecto Anterior">
            <i class="bx bx-chevron-left"></i>
        </button>
        <button id="cinema-next" class="absolute right-4 md:right-8 text-slate-500 hover:text-white hover:bg-slate-900/60 p-3 rounded-full text-3xl focus:outline-none transition-all z-[1005] cursor-pointer" aria-label="Siguiente Proyecto">
            <i class="bx bx-chevron-right"></i>
        </button>
    </div>

    <!-- Inject Particle Background Initiator & Main application script -->
    <script src="assets/js/particles.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Start particle ecosystem inside canvas
        window.initParticles('particles-canvas');
    </script>
</body>
</html>
