<?php
/**
 * MonteroStudio - Secure Contact Form Component
 */
// Generate dynamic CSRF token in cookie if not set
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
if (empty($_COOKIE['public_csrf_token'])) {
    $publicCsrfToken = bin2hex(random_bytes(32));
    setcookie('public_csrf_token', $publicCsrfToken, [
        'expires'  => time() + 7200,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
} else {
    $publicCsrfToken = $_COOKIE['public_csrf_token'];
}
?>
<section id="contact" class="spa-section py-12 scroll-animate relative">
    
    <!-- Background glow spotlight -->
    <div class="absolute left-1/2 -translate-x-1/2 bottom-0 w-[500px] h-[300px] bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-5xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-2 block">Contacto</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight">Iniciemos un Proyecto</h2>
            <div class="h-1 w-20 bg-blue-600 rounded mt-4 mx-auto"></div>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base mt-4 font-light">
                ¿Tienes una idea en mente o necesitas cotizar un desarrollo? Escríbeme y te responderé en menos de 24 horas.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left Info Panel -->
            <div class="lg:col-span-4 flex flex-col justify-between space-y-6">
                
                <div class="glass-panel p-6 border-slate-800/40 bg-slate-950/20 space-y-6">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-900 pb-2">Información</h3>
                    
                    <!-- Location Info -->
                    <div class="flex items-start space-x-3 text-sm">
                        <div class="text-blue-500 text-xl mt-0.5"><i class="bx bx-map"></i></div>
                        <div>
                            <h4 class="font-semibold text-white">Ubicación</h4>
                            <p class="text-slate-400 text-xs">Mérida, Venezuela (Disponible Remoto)</p>
                        </div>
                    </div>

                    <!-- Email Info (Obfuscated using HTML entities and formatting to prevent scraper bots) -->
                    <div class="flex items-start space-x-3 text-sm">
                        <div class="text-blue-500 text-xl mt-0.5"><i class="bx bx-envelope"></i></div>
                        <div>
                            <h4 class="font-semibold text-white">Correo Electrónico</h4>
                            <!-- Obfuscated structure for crawler bots safety -->
                            <p class="text-slate-400 text-xs">contacto [arroba] monterostudio.com</p>
                        </div>
                    </div>

                    <!-- Business hours -->
                    <div class="flex items-start space-x-3 text-sm">
                        <div class="text-blue-500 text-xl mt-0.5"><i class="bx bx-time-five"></i></div>
                        <div>
                            <h4 class="font-semibold text-white">Horario de Atención</h4>
                            <p class="text-slate-400 text-xs">Lunes a Viernes (8:00 AM - 6:00 PM)</p>
                        </div>
                    </div>
                </div>

                <!-- Call to action card inside left panel -->
                <div class="glass-panel p-6 border-blue-950/40 bg-blue-950/10 text-center relative overflow-hidden group">
                    <div class="absolute -top-12 -left-12 w-24 h-24 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>
                    <i class="bx bx-message-rounded-dots text-4xl text-blue-400 mb-2 animate-bounce"></i>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-2">¿Asesoría Inmediata?</h4>
                    <p class="text-slate-400 text-xs leading-relaxed mb-4">Escríbeme directamente para agendar una videollamada de 15 minutos en Figma.</p>
                </div>

            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-8">
                <form id="contact-form" action="index.php" method="POST" class="glass-panel p-8 border-slate-800/40 bg-slate-950/20 space-y-6">
                    
                    <!-- CSRF Validation Token Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($publicCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Input Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Nombre Completo</label>
                            <div class="relative">
                                <input type="text" id="name" name="name" required placeholder="Ej. Juan Pérez" 
                                       class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 px-4 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                            </div>
                        </div>

                        <!-- Input Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Correo Electrónico</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" required placeholder="Ej. juan@correo.com" 
                                       class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 px-4 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Input Subject -->
                    <div class="space-y-2">
                        <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Servicio Interesado</label>
                        <select id="subject" name="subject" class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 px-4 text-sm text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                            <option value="Branding">Identidad Visual & Branding</option>
                            <option value="UI/UX Design">Diseño Interfaces UI/UX</option>
                            <option value="Web Development" selected>Desarrollo Web SPA</option>
                            <option value="Consulting">Consultoría & SEO</option>
                        </select>
                    </div>

                    <!-- Input Message -->
                    <div class="space-y-2">
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Descripción del Proyecto</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Cuéntame un poco sobre tu idea, objetivos y presupuesto aproximado..." 
                                  class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 px-4 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all resize-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="magnetic-button relative inline-flex items-center justify-center p-0.5 overflow-hidden text-xs font-bold uppercase tracking-wider rounded-lg group bg-gradient-to-br from-blue-600 to-indigo-800 text-white w-full transition-all duration-300 cursor-pointer shadow-lg hover:shadow-blue-500/10">
                            <span class="magnetic-inner relative px-5 py-3.5 transition-all ease-in duration-75 bg-[#030712] rounded-md group-hover:bg-opacity-0 w-full text-center">
                                Enviar Mensaje <i class="bx bx-paper-plane ml-1 text-sm align-middle"></i>
                            </span>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>
