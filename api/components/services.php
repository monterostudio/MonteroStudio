<?php
/**
 * MonteroStudio - Services Spotlight Component
 */
?>
<section id="services" class="spa-section py-12 scroll-animate relative">
    
    <!-- Background lights -->
    <div class="absolute left-0 bottom-0 w-[300px] h-[300px] bg-blue-950/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-2 block">Mis Soluciones</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">Diseño Visual & Desarrollo Web</h2>
            <div class="h-1 w-20 bg-blue-600 rounded mt-4 mx-auto"></div>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base mt-4 font-light">
                Como creador independiente, aporto pasión y equilibrio a cada proyecto. Desde el nivel de diseño gráfico para construir tu identidad, hasta la programación web fluida y segura para aplicaciones modernas.
        </div>

        <!-- Grid of Services with Spotlight Hover Effect -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php
            $dbServices = DB::getServices();
            if (empty($dbServices)):
            ?>
                <!-- Service 1: Branding & Logo -->
                <div class="spotlight-card glass-panel p-8 relative overflow-hidden group border-slate-800/40 bg-slate-950/30">
                    <!-- Cursor-following glow effect -->
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300" 
                         style="background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(37, 99, 235, 0.15), transparent 80%);"></div>
                    
                    <!-- Glow border layer -->
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl border border-blue-500/50" 
                         style="mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent); -webkit-mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-blue-900/20 border border-blue-800/40 text-blue-400 text-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                                <i class="bx bx-brush"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider mb-3">Diseño Visual & Gráfico</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6 font-light">
                                Llevo la estética de tu marca al siguiente nivel. Creo logotipos, paletas de color equilibradas (como nuestro azul índigo) y manuales de identidad visual que conectan con tu audiencia.
                            </p>
                        </div>
                        <ul class="space-y-2 text-xs font-semibold tracking-wide text-slate-300">
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Logotipo principal e imagotipos</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Manuales de uso de marca completos</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Diseño de empaque y papelería</li>
                        </ul>
                    </div>
                </div>

                <!-- Service 2: UI/UX Prototyping -->
                <div class="spotlight-card glass-panel p-8 relative overflow-hidden group border-slate-800/40 bg-slate-950/30">
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300" 
                         style="background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(37, 99, 235, 0.15), transparent 80%);"></div>
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl border border-blue-500/50" 
                         style="mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent); -webkit-mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-blue-900/20 border border-blue-800/40 text-blue-400 text-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                                <i class="bx bx-layer"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider mb-3">Diseño UI/UX Equilibrado</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6 font-light">
                                Diseño interfaces estéticas y equilibradas antes de programar. Creo prototipos navegables en Figma pensando siempre en la máxima fluidez tecnológica y la mejor experiencia de usuario.
                            </p>
                        </div>
                        <ul class="space-y-2 text-xs font-semibold tracking-wide text-slate-300">
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Prototipos de alta fidelidad interactivos</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Pruebas de usabilidad y wireframes</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Adaptabilidad responsive garantizada</li>
                        </ul>
                    </div>
                </div>

                <!-- Service 3: SPA Web Development -->
                <div class="spotlight-card glass-panel p-8 relative overflow-hidden group border-slate-800/40 bg-slate-950/30">
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300" 
                         style="background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(37, 99, 235, 0.15), transparent 80%);"></div>
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl border border-blue-500/50" 
                         style="mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent); -webkit-mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-blue-900/20 border border-blue-800/40 text-blue-400 text-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                                <i class="bx bx-code-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider mb-3">Programación Web & SPA</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6 font-light">
                                Programación web a nivel de aplicaciones SPA. Escribimos código limpio, seguro y estructurado en PHP moderno para garantizar que el motor de tu sitio funcione con una velocidad y fluidez impecables.
                            </p>
                        </div>
                        <ul class="space-y-2 text-xs font-semibold tracking-wide text-slate-300">
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Sitios dinámicos sin recargas molestas</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Backend rápido estructurado en PHP</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> API RESTful y seguridad de datos</li>
                        </ul>
                    </div>
                </div>

                <!-- Service 4: Optimization & SEO -->
                <div class="spotlight-card glass-panel p-8 relative overflow-hidden group border-slate-800/40 bg-slate-950/30">
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300" 
                         style="background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(37, 99, 235, 0.15), transparent 80%);"></div>
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl border border-blue-500/50" 
                         style="mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent); -webkit-mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-blue-900/20 border border-blue-800/40 text-blue-400 text-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                                <i class="bx bx-line-chart"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider mb-3">Seguridad & Rendimiento</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6 font-light">
                                La seguridad y la fluidez son prioridad. Implementamos código seguro contra vulnerabilidades, optimización profunda y buenas prácticas tecnológicas para que tu plataforma sea robusta y confiable.
                            </p>
                        </div>
                        <ul class="space-y-2 text-xs font-semibold tracking-wide text-slate-300">
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Puntuaciones altas en Core Web Vitals</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Estructura semántica HTML5 pura</li>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> Indexación XML y SEO on-page</li>
                        </ul>
                    </div>
                </div>
            <?php
            else:
                foreach ($dbServices as $serv):
                    $bullets = array_filter(array_map('trim', explode("\n", $serv['bullet_items'])));
            ?>
                <!-- Dynamic Service -->
                <div class="spotlight-card glass-panel p-8 relative overflow-hidden group border-slate-800/40 bg-slate-950/30">
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300" 
                         style="background: radial-gradient(350px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), rgba(37, 99, 235, 0.15), transparent 80%);"></div>
                    <div class="pointer-events-none absolute -inset-px opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl border border-blue-500/50" 
                         style="mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent); -webkit-mask-image: radial-gradient(150px circle at var(--mouse-x, 0px) var(--mouse-y, 0px), black, transparent);"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <div class="w-12 h-12 rounded-lg bg-blue-900/20 border border-blue-800/40 text-blue-400 text-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                                <i class="bx <?php echo htmlspecialchars($serv['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white uppercase tracking-wider mb-3"><?php echo htmlspecialchars($serv['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6 font-light">
                                <?php echo htmlspecialchars($serv['description'], ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>
                        <?php if (!empty($bullets)): ?>
                        <ul class="space-y-2 text-xs font-semibold tracking-wide text-slate-300">
                            <?php foreach ($bullets as $bullet): ?>
                            <li class="flex items-center"><i class="bx bx-check text-blue-500 mr-2 text-base"></i> <?php echo htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>     </div>

        </div>
    </div>
</section>
