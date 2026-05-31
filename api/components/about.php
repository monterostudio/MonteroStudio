<?php
/**
 * MonteroStudio - About Me & Interactive Skills Component
 */
?>
<section id="about" class="spa-section py-12 scroll-animate relative">
    
    <!-- Decorative side lights -->
    <div class="absolute right-0 top-1/4 w-[250px] h-[250px] bg-blue-900/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto">
        <!-- Title and Introduction Header -->
        <div class="text-center md:text-left mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-2 block">
                <?php echo htmlspecialchars(DB::getSetting('about_subtitle', 'Sobre Mí'), ENT_QUOTES, 'UTF-8'); ?>
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">
                <?php echo htmlspecialchars(DB::getSetting('about_title_part1', 'El Arte del Diseño Visual'), ENT_QUOTES, 'UTF-8'); ?> <br class="hidden md:inline">
                <?php echo htmlspecialchars(DB::getSetting('about_title_part2', 'y la Ingeniería de Software'), ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <div class="h-1 w-20 bg-blue-600 rounded mt-4 mx-auto md:mx-0"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Column: Biography & Philosophy -->
            <div class="lg:col-span-5 space-y-6">
                <h3 class="text-2xl font-bold text-white tracking-tight">
                    <?php echo htmlspecialchars(DB::getSetting('about_intro', 'Fusionando lo estético con lo funcional'), ENT_QUOTES, 'UTF-8'); ?>
                </h3>
                <?php
                $bio = DB::getSetting('about_biography', "En MONTERO STUDIO no creo en las páginas web aburridas ni en las plantillas saturadas. Diseño con un propósito visual y programo con código limpio, rápido y seguro.\n\nComo desarrollador independiente y diseñador visual, entiendo que tu marca necesita destacar en segundos. Por eso creo sistemas Single Page Application (SPA) que ofrecen velocidades de carga asombrosas y una estética que capta la atención.");
                $paragraphs = array_filter(array_map('trim', explode("\n\n", $bio)));
                foreach ($paragraphs as $p):
                ?>
                <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                    <?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <?php endforeach; ?>

                <!-- Performance indicators/Stats -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-900">
                    <div class="text-center lg:text-left">
                        <span class="block text-3xl font-extrabold text-blue-500 tracking-tight">
                            <?php echo htmlspecialchars(DB::getSetting('stat_exp_val', '1+'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">
                            <?php echo htmlspecialchars(DB::getSetting('stat_exp_lbl', 'Años de Exp.'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <div class="text-center lg:text-left">
                        <span class="block text-3xl font-extrabold text-blue-500 tracking-tight">
                            <?php echo htmlspecialchars(DB::getSetting('stat_projects_val', '10+'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">
                            <?php echo htmlspecialchars(DB::getSetting('stat_projects_lbl', 'Proyectos'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <div class="text-center lg:text-left">
                        <span class="block text-3xl font-extrabold text-blue-500 tracking-tight">
                            <?php echo htmlspecialchars(DB::getSetting('stat_commit_val', '100%'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">
                            <?php echo htmlspecialchars(DB::getSetting('stat_commit_lbl', 'Compromiso'), ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Skills Constellation -->
            <div class="lg:col-span-7">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-6 text-center lg:text-left">
                    Constelación de Habilidades & Herramientas
                </h4>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <!-- Creative / Design Skills -->
                    <div class="col-span-2 space-y-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 block px-2">Diseño Visual & UI</span>
                        
                        <!-- Skill node: Branding -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-indigo-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-indigo-900/30 flex items-center justify-center text-indigo-400 text-xl border border-indigo-800/30">
                                <i class="bx bx-palette"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Branding & Logo</h5>
                                <span class="text-[10px] text-slate-400">Identidad Corporativa</span>
                            </div>
                        </div>

                        <!-- Skill node: UI/UX Prototyping -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-indigo-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-indigo-900/30 flex items-center justify-center text-indigo-400 text-xl border border-indigo-800/30">
                                <i class="bx bx-vector"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Diseño UI/UX</h5>
                                <span class="text-[10px] text-slate-400">Figma & Prototipado</span>
                            </div>
                        </div>

                        <!-- Skill node: Illustration -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-indigo-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-indigo-900/30 flex items-center justify-center text-indigo-400 text-xl border border-indigo-800/30">
                                <i class="bx bx-paint-roll"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Ilustración Vector</h5>
                                <span class="text-[10px] text-slate-400">Arte Visual Digital</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical / Code Skills -->
                    <div class="col-span-2 space-y-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400 block px-2">Desarrollo & Backend</span>
                        
                        <!-- Skill node: PHP / OOP -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-blue-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-blue-900/30 flex items-center justify-center text-blue-400 text-xl border border-blue-800/30">
                                <i class="bx bxl-php"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">PHP Moderno</h5>
                                <span class="text-[10px] text-slate-400">Backend & Controladores</span>
                            </div>
                        </div>

                        <!-- Skill node: JavaScript -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-blue-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-blue-900/30 flex items-center justify-center text-blue-400 text-xl border border-blue-800/30">
                                <i class="bx bxl-javascript"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">JavaScript (SPA)</h5>
                                <span class="text-[10px] text-slate-400">Enrutamiento & Eventos</span>
                            </div>
                        </div>

                        <!-- Skill node: Tailwind CSS 4.0 -->
                        <div class="glass-panel p-4 flex items-center space-x-3 border-blue-950/60 bg-slate-950/40 hover:scale-[1.03] transition-transform duration-300">
                            <div class="w-10 h-10 rounded bg-blue-900/30 flex items-center justify-center text-blue-400 text-xl border border-blue-800/30">
                                <i class="bx bxl-tailwind-css"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-white uppercase tracking-wider">Tailwind CSS 4.0</h5>
                                <span class="text-[10px] text-slate-400">Responsive Layouts</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
