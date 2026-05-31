<?php
/**
 * MonteroStudio - Cinema Teaser Section
 */
?>
<section id="cinema" class="spa-section py-12 scroll-animate relative">
    
    <!-- Cinematic spotlights -->
    <div class="absolute left-1/2 -translate-x-1/2 top-0 w-[80%] h-[150px] bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-5xl mx-auto">
        <!-- Title and Introduction Header -->
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-2 block">Experiencia Inmersiva</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">CINE MONTERO STUDIO</h2>
            <div class="h-1 w-20 bg-blue-600 rounded mt-4 mx-auto"></div>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base mt-4 font-light">
                Apaga las luces del navegador. He diseñado una sala de cine virtual para que disfrutes de mis diseños en alta resolución y con descripciones detalladas de su desarrollo.
            </p>
        </div>

        <!-- Cinema Mockup Frame -->
        <div class="glass-panel p-4 md:p-6 border-slate-800/60 bg-slate-950/20 max-w-4xl mx-auto">
            <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-black shadow-2xl border border-slate-900 group">
                <!-- Preview image or placeholder pattern representing a cinematic environment -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>
                
                <!-- Simulated movie projection beam effect -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-blue-500/10 via-transparent to-transparent pointer-events-none transform origin-top skew-x-12 animate-pulse"></div>

                <!-- Abstract graphic background representing digital cinema -->
                <div class="absolute inset-0 flex items-center justify-center bg-[#02040a]">
                    <div class="text-center space-y-4 px-6 z-20">
                        <span class="text-[10px] font-bold tracking-widest text-blue-400 uppercase">Sala de Proyección Virtual</span>
                        <h3 class="text-2xl md:text-4xl font-extrabold text-white tracking-tight uppercase">El Arte de Programar & Diseñar</h3>
                        <p class="text-slate-500 text-xs max-w-md mx-auto leading-relaxed">
                            Interactúa con los controles, desplázate por las capturas en formato panorámico y descubre las tecnologías detrás de cada solución de desarrollo.
                        </p>
                    </div>
                </div>

                <!-- Centered Interactive Play Button -->
                <button class="open-cinema-btn absolute inset-0 z-30 flex items-center justify-center bg-black/60 hover:bg-black/35 transition-all duration-300 group cursor-pointer" data-project-index="0">
                    <div class="w-20 h-20 rounded-full bg-blue-600/20 group-hover:bg-blue-600/30 border-2 border-blue-500 flex items-center justify-center shadow-[0_0_30px_rgba(37,99,235,0.4)] group-hover:scale-110 transition-all duration-300">
                        <i class="bx bx-play text-white text-5xl ml-1 group-hover:text-blue-200"></i>
                    </div>
                </button>
            </div>
        </div>

        <!-- Cinema tips -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mt-8 text-center text-xs text-slate-500">
            <span class="flex items-center"><i class="bx bx-mobile-vibration mr-2 text-base text-blue-500"></i> Optimización móvil fluida</span>
            <span class="flex items-center"><i class="bx bx-keyboard mr-2 text-base text-blue-500"></i> Navegación por flechas del teclado</span>
            <span class="flex items-center"><i class="bx bx-volume-full mr-2 text-base text-blue-500"></i> Modo inmersivo en pantalla completa</span>
        </div>

    </div>
</section>
