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
                <!-- Project 1: AeroBranding (Design) -->
                <div class="portfolio-item glass-panel overflow-hidden border-slate-900/80 bg-slate-950/20 group relative" data-category="design">
                    <!-- Data Source for Cinema Mode JS -->
                    <div class="cinema-data-source hidden" 
                         data-title="AeroBranding"
                         data-subtitle="Identidad Corporativa"
                         data-description="Branding ecológico premium diseñado para una aerolínea de movilidad sostenible. Cree una paleta de colores limpia inspirada en el aire libre, logotipo vectorial geométrico, papelería institucional y guías de estilos tipográficos modernos."
                         data-tags="Branding, Logotipos, Manual de Marca, Vector"
                         data-image="assets/images/aerobranding.png"
                         data-link="#"></div>
                    
                    <div class="relative aspect-video overflow-hidden">
                        <img src="assets/images/aerobranding.png" alt="AeroBranding Mockup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Hover Cinema Play Icon -->
                        <button class="open-cinema-btn absolute inset-0 bg-slate-950/85 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-all duration-300 cursor-pointer" data-project-index="0">
                            <i class="bx bx-play-circle text-5xl text-blue-500 mb-2 animate-pulse"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Ver Caso en Cine</span>
                        </button>
                    </div>
                    <div class="p-6 border-t border-slate-900/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">Diseño Visual / Branding</span>
                        <h3 class="text-lg font-bold text-white tracking-tight mt-1 mb-2">AeroBranding</h3>
                        <p class="text-slate-400 text-xs leading-relaxed font-light">Identidad corporativa ecológica y papelería digital corporativa premium.</p>
                    </div>
                </div>

                <!-- Project 2: NovaCommerce (Web) -->
                <div class="portfolio-item glass-panel overflow-hidden border-slate-900/80 bg-slate-950/20 group relative" data-category="web">
                    <div class="cinema-data-source hidden" 
                         data-title="NovaCommerce"
                         data-subtitle="Desarrollo Web SPA"
                         data-description="E-commerce moderno desarrollado como una Single Page Application (SPA). Cuenta con pasarela de pago simulada por AJAX, administración de stock dinámico en base de datos MySQL de Laragon y un enrutador CSS reactivo ultrarápido."
                         data-tags="PHP, MySQL, JavaScript ES6, Tailwind CSS"
                         data-image="assets/images/novacommerce.png"
                         data-link="#"></div>
                    
                    <div class="relative aspect-video overflow-hidden">
                        <img src="assets/images/novacommerce.png" alt="NovaCommerce Mockup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="open-cinema-btn absolute inset-0 bg-slate-950/85 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-all duration-300 cursor-pointer" data-project-index="1">
                            <i class="bx bx-play-circle text-5xl text-blue-500 mb-2 animate-pulse"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Ver Caso en Cine</span>
                        </button>
                    </div>
                    <div class="p-6 border-t border-slate-900/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Desarrollo Web / E-Commerce</span>
                        <h3 class="text-lg font-bold text-white tracking-tight mt-1 mb-2">NovaCommerce</h3>
                        <p class="text-slate-400 text-xs leading-relaxed font-light">Tienda en línea responsiva e interactiva tipo SPA sin recarga de páginas.</p>
                    </div>
                </div>

                <!-- Project 3: Valkyria Esports (Design) -->
                <div class="portfolio-item glass-panel overflow-hidden border-slate-900/80 bg-slate-950/20 group relative" data-category="design">
                    <div class="cinema-data-source hidden" 
                         data-title="Valkyria Esports"
                         data-subtitle="Diseño UI/UX & Figma"
                         data-description="Prototipo de aplicación móvil y web para una comunidad competitiva de videojuegos. Desarrollé esquemas de flujos (wireframes), prototipos interactivos animados en Figma y logotipos deportivos dinámicos de alto nivel."
                         data-tags="Figma, UI/UX, Logotipos, Prototipos"
                         data-image="assets/images/valkyria.png"
                         data-link="#"></div>
                    
                    <div class="relative aspect-video overflow-hidden">
                        <img src="assets/images/valkyria.png" alt="Valkyria Mockup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="open-cinema-btn absolute inset-0 bg-slate-950/85 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-all duration-300 cursor-pointer" data-project-index="2">
                            <i class="bx bx-play-circle text-5xl text-blue-500 mb-2 animate-pulse"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Ver Caso en Cine</span>
                        </button>
                    </div>
                    <div class="p-6 border-t border-slate-900/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">Diseño UI/UX / Gaming</span>
                        <h3 class="text-lg font-bold text-white tracking-tight mt-1 mb-2">Valkyria Esports</h3>
                        <p class="text-slate-400 text-xs leading-relaxed font-light">Diseño de interfaz y sistema de diseño visual de marca deportiva para torneos.</p>
                    </div>
                </div>

                <!-- Project 4: Cryptic Wallet (Web) -->
                <div class="portfolio-item glass-panel overflow-hidden border-slate-900/80 bg-slate-950/20 group relative" data-category="web">
                    <div class="cinema-data-source hidden" 
                         data-title="Cryptic Wallet"
                         data-subtitle="Web Application Dashboard"
                         data-description="Dashboard interactivo para visualizar precios e inventarios de criptoactivos. Integra librerías de gráficos en JS, comunicación segura con APIs públicas mediante AJAX en PHP y arquitectura de componentes responsivos."
                         data-tags="JavaScript, PHP, API REST, Charts.js"
                         data-image="assets/images/cryptic.png"
                         data-link="#"></div>
                    
                    <div class="relative aspect-video overflow-hidden">
                        <img src="assets/images/cryptic.png" alt="Cryptic Mockup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="open-cinema-btn absolute inset-0 bg-slate-950/85 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white transition-all duration-300 cursor-pointer" data-project-index="3">
                            <i class="bx bx-play-circle text-5xl text-blue-500 mb-2 animate-pulse"></i>
                            <span class="text-xs font-bold uppercase tracking-wider">Ver Caso en Cine</span>
                        </button>
                    </div>
                    <div class="p-6 border-t border-slate-900/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Desarrollo Web / Dashboard</span>
                        <h3 class="text-lg font-bold text-white tracking-tight mt-1 mb-2">Cryptic Wallet</h3>
                        <p class="text-slate-400 text-xs leading-relaxed font-light">Panel administrativo financiero con consultas a API externas en tiempo real.</p>
                    </div>
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
