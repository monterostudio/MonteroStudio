<?php
/**
 * MonteroStudio - Filterable Portfolio Grid
 */
?>
<section id="portfolio" class="spa-section py-12 scroll-animate relative">
    
    <!-- Background lights -->
    <div class="absolute right-10 bottom-1/3 w-[300px] h-[300px] bg-indigo-950/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-2 block">Casos de Éxito</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">Galería de Proyectos</h2>
            <div class="h-1 w-20 bg-blue-600 rounded mt-4 mx-auto"></div>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base mt-4 font-light">
                Descubre cómo impulso marcas mediante el diseño visual estratégico y el desarrollo técnico a medida.
            </p>
        </div>

        <!-- Filter Navigation Buttons -->
        <div class="flex items-center justify-center space-x-3 mb-12">
            <button class="filter-btn px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-blue-600 text-white shadow-lg shadow-blue-500/25 transition-all cursor-pointer" data-filter="all">
                Todos
            </button>
            <button class="filter-btn px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-900/50 text-slate-400 border border-slate-800/40 hover:text-white transition-all cursor-pointer" data-filter="design">
                Diseño Visual
            </button>
            <button class="filter-btn px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-900/50 text-slate-400 border border-slate-800/40 hover:text-white transition-all cursor-pointer" data-filter="web">
                Desarrollo Web
        </div>

        <!-- Portfolio Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php
            $dbProjects = DB::getProjects();
            if (empty($dbProjects)):
            ?>
                <!-- Empty state card -->
                <div class="col-span-1 md:col-span-2 text-center py-12 glass-panel border-slate-900 bg-slate-950/20 max-w-md mx-auto w-full">
                    <i class="bx bx-folder-open text-5xl text-blue-500 mb-3 animate-pulse"></i>
                    <h3 class="text-base font-bold text-white uppercase tracking-wider mb-2">Proyecto no disponible</h3>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-xs mx-auto">Por el momento no hay casos de estudio publicados en la base de datos.</p>
                </div>
            <?php
            else:
                $index = 0;
                foreach ($dbProjects as $proj):
                    $imgUrl = "serve_image.php?file=" . urlencode($proj['image_path']);
            ?>
                <!-- Dynamic Project X -->
                <div class="portfolio-item glass-panel overflow-hidden border-slate-900/80 bg-slate-950/20 group relative" data-category="<?php echo htmlspecialchars($proj['category'], ENT_QUOTES, 'UTF-8'); ?>">
                    <!-- Data Source for Cinema Mode JS -->
                    <div class="cinema-data-source hidden" 
                         data-title="<?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-subtitle="<?php echo htmlspecialchars($proj['subtitle'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-description="<?php echo htmlspecialchars($proj['description'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-tags="<?php echo htmlspecialchars($proj['tags'], ENT_QUOTES, 'UTF-8'); ?>"
                         data-image="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                         data-link="<?php echo htmlspecialchars($proj['external_link'], ENT_QUOTES, 'UTF-8'); ?>"></div>
                    
                    <div class="relative aspect-video overflow-hidden">
                        <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?> Mockup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Hover Cinema Play Icon -->
                        <button class="open-cinema-btn absolute inset-0 bg-slate-950/85 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-all duration-300 cursor-pointer" data-project-index="<?php echo $index; ?>">
                            <i class="bx bx-play-circle text-5xl text-blue-500 mb-2 animate-pulse"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Ver Caso en Cine</span>
                        </button>
                    </div>
                    <div class="p-6 border-t border-slate-900/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest <?php echo $proj['category'] === 'design' ? 'text-indigo-400' : 'text-blue-400'; ?>">
                            <?php echo $proj['category'] === 'design' ? 'Diseño Visual / Branding' : 'Desarrollo Web / App'; ?>
                        </span>
                        <h3 class="text-lg font-bold text-white tracking-tight mt-1 mb-2"><?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="text-slate-400 text-xs leading-relaxed font-light"><?php echo htmlspecialchars($proj['subtitle'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            <?php
                    $index++;
                endforeach;
            endif;
            ?>
        </div>            </div>

        </div>
    </div>
</section>
