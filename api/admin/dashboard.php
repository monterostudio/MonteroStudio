<?php
/**
 * MONTERO STUDIO - Administrative Dashboard
 * Dynamically edit site settings, services, and portfolio projects with high security.
 */

require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once dirname(__DIR__) . '/includes/db.php';

// Enforce auth
require_admin_auth();

$db = DB::getConnection();
if (!$db) {
    die("Error: Base de datos no disponible. Por favor ejecuta el asistente de instalación en setup.php.");
}

$error = '';
$success = '';

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // 1. Validate CSRF
    if (!validate_admin_csrf($csrfToken)) {
        $error = "Error de seguridad: Token CSRF no válido.";
    } else {

        // ACTION: Update General Settings
        if ($action === 'update_settings') {
            $settingsToUpdate = [
                'site_title' => $_POST['site_title'] ?? '',
                'site_description' => $_POST['site_description'] ?? '',
                'hero_title_part1' => $_POST['hero_title_part1'] ?? '',
                'hero_title_part2' => $_POST['hero_title_part2'] ?? '',
                'hero_description' => $_POST['hero_description'] ?? '',
                'about_subtitle' => $_POST['about_subtitle'] ?? '',
                'about_title_part1' => $_POST['about_title_part1'] ?? '',
                'about_title_part2' => $_POST['about_title_part2'] ?? '',
                'about_intro' => $_POST['about_intro'] ?? '',
                'about_biography' => $_POST['about_biography'] ?? '',
                'stat_exp_val' => $_POST['stat_exp_val'] ?? '',
                'stat_exp_lbl' => $_POST['stat_exp_lbl'] ?? '',
                'stat_projects_val' => $_POST['stat_projects_val'] ?? '',
                'stat_projects_lbl' => $_POST['stat_projects_lbl'] ?? '',
                'stat_commit_val' => $_POST['stat_commit_val'] ?? '',
                'stat_commit_lbl' => $_POST['stat_commit_lbl'] ?? '',
                'footer_description' => $_POST['footer_description'] ?? '',
            ];

            try {
                $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
                if ($driver === 'pgsql') {
                    $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON CONFLICT (setting_key) DO UPDATE SET setting_value = EXCLUDED.setting_value");
                    foreach ($settingsToUpdate as $key => $val) {
                        $stmt->execute([$key, $val]);
                    }
                } else {
                    $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    foreach ($settingsToUpdate as $key => $val) {
                        $stmt->execute([$key, $val, $val]);
                    }
                }
                $success = "Configuración general actualizada correctamente.";
            } catch (PDOException $e) {
                error_log("Settings update failed: " . $e->getMessage());
                $error = "No se pudieron actualizar los ajustes generales.";
            }
        }

        // ACTION: Save Service (Add / Edit)
        elseif ($action === 'save_service') {
            $id = $_POST['service_id'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon = trim($_POST['icon'] ?? 'bx-layer');
            $bullet_items = trim($_POST['bullet_items'] ?? '');
            $sort_order = (int)($_POST['sort_order'] ?? 0);

            if (empty($title) || empty($description)) {
                $error = "El título y la descripción del servicio son obligatorios.";
            } else {
                try {
                    if (empty($id)) {
                        $stmt = $db->prepare("INSERT INTO services (title, description, icon, bullet_items, sort_order) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $description, $icon, $bullet_items, $sort_order]);
                        $success = "Servicio creado con éxito.";
                    } else {
                        $stmt = $db->prepare("UPDATE services SET title = ?, description = ?, icon = ?, bullet_items = ?, sort_order = ? WHERE id = ?");
                        $stmt->execute([$title, $description, $icon, $bullet_items, $sort_order, $id]);
                        $success = "Servicio actualizado con éxito.";
                    }
                } catch (PDOException $e) {
                    error_log("Service save failed: " . $e->getMessage());
                    $error = "No se pudo guardar el servicio.";
                }
            }
        }

        // ACTION: Delete Service
        elseif ($action === 'delete_service') {
            $id = (int)($_POST['service_id'] ?? 0);
            try {
                $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
                $stmt->execute([$id]);
                $success = "Servicio eliminado correctamente.";
            } catch (PDOException $e) {
                error_log("Service deletion failed: " . $e->getMessage());
                $error = "No se pudo eliminar el servicio.";
            }
        }

        // ACTION: Save Portfolio Project (Add / Edit)
        elseif ($action === 'save_project') {
            $id = $_POST['project_id'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $tags = trim($_POST['tags'] ?? '');
            $external_link = trim($_POST['external_link'] ?? '#');
            $category = $_POST['category'] ?? 'design';
            $sort_order = (int)($_POST['sort_order'] ?? 0);

            if (empty($title) || empty($description)) {
                $error = "El título y la descripción del proyecto son obligatorios.";
            } else {
                $imagePath = $_POST['existing_image'] ?? '';
                $imageUploaded = isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK;

                // Validate and process file upload
                if ($imageUploaded) {
                    $file = $_FILES['image_file'];
                    $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                    // Check file size (limit to 2MB)
                    if ($file['size'] > 2097152) {
                        $error = "La imagen excede el límite de tamaño permitido (2MB).";
                    } 
                    // Validate extension
                    elseif (!in_array($ext, $allowedExts)) {
                        $error = "Formato de imagen no permitido. Solo se aceptan PNG, JPG, JPEG, GIF o WEBP.";
                    } else {
                        // Validate magic bytes MIME type
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $file['tmp_name']);
                        finfo_close($finfo);

                        if (strpos($mimeType, 'image/') !== 0) {
                            $error = "El archivo subido no es una imagen válida.";
                        } else {
                            // Generate unique secure filename
                            $newFilename = bin2hex(random_bytes(16)) . '.' . $ext;
                            $destPath = dirname(__DIR__) . '/uploads/' . $newFilename;

                            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                                // Delete old image file if updating and exists
                                if (!empty($imagePath)) {
                                    $oldFilePath = dirname(__DIR__) . '/uploads/' . basename($imagePath);
                                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                                        @unlink($oldFilePath);
                                    }
                                }
                                $imagePath = $newFilename;
                            } else {
                                $error = "Error al mover el archivo subido al directorio de imágenes.";
                            }
                        }
                    }
                } elseif (empty($id) && !$imageUploaded) {
                    $error = "Debes seleccionar una imagen para el nuevo proyecto.";
                }

                // If no file errors, save record
                if (empty($error)) {
                    try {
                        if (empty($id)) {
                            $stmt = $db->prepare("INSERT INTO portfolio_projects (title, subtitle, description, tags, image_path, external_link, category, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$title, $subtitle, $description, $tags, $imagePath, $external_link, $category, $sort_order]);
                            $success = "Proyecto del portafolio creado con éxito.";
                        } else {
                            $stmt = $db->prepare("UPDATE portfolio_projects SET title = ?, subtitle = ?, description = ?, tags = ?, image_path = ?, external_link = ?, category = ?, sort_order = ? WHERE id = ?");
                            $stmt->execute([$title, $subtitle, $description, $tags, $imagePath, $external_link, $category, $sort_order, $id]);
                            $success = "Proyecto del portafolio actualizado con éxito.";
                        }
                    } catch (PDOException $e) {
                        error_log("Project save failed: " . $e->getMessage());
                        $error = "No se pudo guardar el proyecto en la base de datos.";
                    }
                }
            }
        }

        // ACTION: Delete Portfolio Project
        elseif ($action === 'delete_project') {
            $id = (int)($_POST['project_id'] ?? 0);

            try {
                // Fetch image path to delete file
                $stmtFetch = $db->prepare("SELECT image_path FROM portfolio_projects WHERE id = ?");
                $stmtFetch->execute([$id]);
                $proj = $stmtFetch->fetch();

                if ($proj) {
                    $imgFile = dirname(__DIR__) . '/uploads/' . basename($proj['image_path']);
                    if (file_exists($imgFile) && is_file($imgFile)) {
                        @unlink($imgFile);
                    }

                    $stmt = $db->prepare("DELETE FROM portfolio_projects WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = "Proyecto eliminado correctamente.";
                } else {
                    $error = "Proyecto no encontrado.";
                }
            } catch (PDOException $e) {
                error_log("Project deletion failed: " . $e->getMessage());
                $error = "No se pudo eliminar el proyecto.";
            }
        }
    }
}

// Fetch all database records for rendering
$settings = DB::getSettings();
$services = DB::getServices();
$projects = DB::getProjects();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MONTERO STUDIO</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Space+Grotesk:wght@400;500&display=swap" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS 4.0 -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #030712;
        }
        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen relative overflow-x-hidden">

    <!-- Background radial spotlights -->
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-blue-600/5 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-indigo-900/5 rounded-full blur-[100px] pointer-events-none z-0"></div>

    <!-- Navigation Header -->
    <header class="sticky top-0 w-full z-40 bg-slate-950/70 border-b border-slate-900 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-lg font-bold text-white uppercase tracking-tight">MONTERO STUDIO</span>
                <span class="text-[9px] tracking-widest text-slate-400 font-semibold uppercase leading-none mt-1">Panel Administrativo</span>
            </div>
            <div class="flex items-center space-x-6">
                <span class="text-xs text-slate-400 hidden sm:inline"><i class="bx bx-user align-middle mr-1 text-sm text-blue-500"></i> Hola, <strong><?php echo htmlspecialchars(get_admin_user(), ENT_QUOTES, 'UTF-8'); ?></strong></span>
                <a href="../#home" target="_blank" class="text-xs text-slate-300 hover:text-white transition-colors"><i class="bx bx-globe align-middle mr-0.5"></i> Ver Sitio</a>
                <a href="logout.php" class="px-4 py-2 bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-800/40 rounded-lg text-xs font-bold uppercase tracking-wider transition-all"><i class="bx bx-log-out align-middle mr-1"></i> Salir</a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">

        <!-- Banner messages -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 bg-rose-950/40 border border-rose-900/60 text-rose-300 text-sm rounded-lg flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="bx bx-error-circle text-xl"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white"><i class="bx bx-x text-lg"></i></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="mb-6 p-4 bg-emerald-950/40 border border-emerald-900/60 text-emerald-300 text-sm rounded-lg flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="bx bx-check-circle text-xl"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="bx bx-x text-lg"></i></button>
            </div>
        <?php endif; ?>

        <!-- Dashboard Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Navigation Menu -->
            <div class="lg:col-span-1">
                <div class="glass-panel p-4 space-y-1.5 sticky top-24">
                    <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 mb-3">Navegación</h3>
                    
                    <button onclick="switchTab('tab-general', this)" class="tab-btn w-full px-4 py-3 rounded-lg text-xs font-bold text-left uppercase tracking-wider bg-blue-600/20 text-blue-400 border border-blue-800/40 transition-all flex items-center space-x-2">
                        <i class="bx bx-cog text-base"></i>
                        <span>Ajustes Generales</span>
                    </button>

                    <button onclick="switchTab('tab-services', this)" class="tab-btn w-full px-4 py-3 rounded-lg text-xs font-bold text-left uppercase tracking-wider hover:bg-slate-900 text-slate-400 hover:text-white border border-transparent transition-all flex items-center space-x-2">
                        <i class="bx bx-briefcase text-base"></i>
                        <span>Servicios</span>
                    </button>

                    <button onclick="switchTab('tab-portfolio', this)" class="tab-btn w-full px-4 py-3 rounded-lg text-xs font-bold text-left uppercase tracking-wider hover:bg-slate-900 text-slate-400 hover:text-white border border-transparent transition-all flex items-center space-x-2">
                        <i class="bx bx-images text-base"></i>
                        <span>Portafolio</span>
                    </button>
                </div>
            </div>

            <!-- Dashboard Modules Area -->
            <div class="lg:col-span-3">

                <!-- MODULE 1: General Settings -->
                <div id="tab-general" class="tab-content active glass-panel p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-900 pb-4">
                        <h2 class="text-xl font-bold text-white uppercase tracking-wider">Ajustes Generales de Textos</h2>
                        <p class="text-xs text-slate-400 mt-1">Controla los títulos SEO, textos de bienvenida, biografía y estadísticas principales del sitio.</p>
                    </div>

                    <form method="POST" action="dashboard.php" class="space-y-6">
                        <input type="hidden" name="action" value="update_settings">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

                        <!-- SEO Metadata Group -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider border-l-2 border-blue-500 pl-2">SEO & Metadatos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título de la Página (Meta Title)</label>
                                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Descripción del Sitio (Meta Description)</label>
                                    <input type="text" name="site_description" value="<?php echo htmlspecialchars($settings['site_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Hero Section Settings Group -->
                        <div class="space-y-4 pt-4 border-t border-slate-900">
                            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider border-l-2 border-blue-500 pl-2">Sección de Inicio (Hero)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título - Parte 1 (Blanco)</label>
                                    <input type="text" name="hero_title_part1" value="<?php echo htmlspecialchars($settings['hero_title_part1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título - Parte 2 (Gradiente)</label>
                                    <input type="text" name="hero_title_part2" value="<?php echo htmlspecialchars($settings['hero_title_part2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Descripción del Hero (Subheading)</label>
                                <textarea name="hero_description" rows="3"
                                          class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2.5 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"><?php echo htmlspecialchars($settings['hero_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <!-- About & Biography Section Group -->
                        <div class="space-y-4 pt-4 border-t border-slate-900">
                            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider border-l-2 border-blue-500 pl-2">Sección Sobre Mí (Biography)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Subtítulo Sección</label>
                                    <input type="text" name="about_subtitle" value="<?php echo htmlspecialchars($settings['about_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título Sección - Parte 1</label>
                                    <input type="text" name="about_title_part1" value="<?php echo htmlspecialchars($settings['about_title_part1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título Sección - Parte 2</label>
                                    <input type="text" name="about_title_part2" value="<?php echo htmlspecialchars($settings['about_title_part2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Introducción destacada</label>
                                    <input type="text" name="about_intro" value="<?php echo htmlspecialchars($settings['about_intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Biografía Principal (separa los párrafos con dos saltos de línea)</label>
                                <textarea name="about_biography" rows="5"
                                          class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2.5 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"><?php echo htmlspecialchars($settings['about_biography'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <!-- Stats Settings Group -->
                        <div class="space-y-4 pt-4 border-t border-slate-900">
                            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider border-l-2 border-blue-500 pl-2">Estadísticas Principales</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <!-- Stat 1 -->
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Stat 1 (Valor)</label>
                                    <input type="text" name="stat_exp_val" value="<?php echo htmlspecialchars($settings['stat_exp_val'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 text-center">
                                    <input type="text" name="stat_exp_lbl" value="<?php echo htmlspecialchars($settings['stat_exp_lbl'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-1 px-2 text-[10px] text-slate-400 focus:outline-none focus:border-blue-500 text-center">
                                </div>
                                <!-- Stat 2 -->
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Stat 2 (Valor)</label>
                                    <input type="text" name="stat_projects_val" value="<?php echo htmlspecialchars($settings['stat_projects_val'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 text-center">
                                    <input type="text" name="stat_projects_lbl" value="<?php echo htmlspecialchars($settings['stat_projects_lbl'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-1 px-2 text-[10px] text-slate-400 focus:outline-none focus:border-blue-500 text-center">
                                </div>
                                <!-- Stat 3 -->
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Stat 3 (Valor)</label>
                                    <input type="text" name="stat_commit_val" value="<?php echo htmlspecialchars($settings['stat_commit_val'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 text-center">
                                    <input type="text" name="stat_commit_lbl" value="<?php echo htmlspecialchars($settings['stat_commit_lbl'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-1 px-2 text-[10px] text-slate-400 focus:outline-none focus:border-blue-500 text-center">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Info Group -->
                        <div class="space-y-4 pt-4 border-t border-slate-900">
                            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider border-l-2 border-blue-500 pl-2">Pie de Página (Footer)</h3>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Descripción Pie de Página</label>
                                <textarea name="footer_description" rows="2"
                                          class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2.5 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"><?php echo htmlspecialchars($settings['footer_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all shadow-lg shadow-blue-600/20 active:scale-95 cursor-pointer">
                                Guardar Ajustes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- MODULE 2: Services Dashboard -->
                <div id="tab-services" class="tab-content tab-content glass-panel p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-900 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Gestión de Servicios</h2>
                            <p class="text-xs text-slate-400 mt-1">Crea, edita o elimina servicios de la cuadrícula de soluciones.</p>
                        </div>
                        <button onclick="openServiceForm()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all flex items-center space-x-1 cursor-pointer">
                            <i class="bx bx-plus text-base"></i>
                            <span>Nuevo Servicio</span>
                        </button>
                    </div>

                    <!-- Add/Edit Service Inline Form (Hidden by default) -->
                    <div id="service-form-panel" class="hidden p-6 border border-slate-800 rounded-xl bg-slate-950/50 space-y-4">
                        <h3 id="service-form-title" class="text-xs font-bold text-blue-400 uppercase tracking-wider">Crear Nuevo Servicio</h3>
                        <form method="POST" action="dashboard.php" class="space-y-4">
                            <input type="hidden" name="action" value="save_service">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" id="form-service-id" name="service_id" value="">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título del Servicio</label>
                                    <input type="text" id="form-service-title" name="title" required
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Icono de Boxicons (ej. bx-brush)</label>
                                    <input type="text" id="form-service-icon" name="icon" required value="bx-layer"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Descripción Corta</label>
                                <textarea id="form-service-desc" name="description" rows="3" required
                                          class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div class="sm:col-span-3 space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Viñetas / Balas de Características (una por línea)</label>
                                    <textarea id="form-service-bullets" name="bullet_items" rows="3" placeholder="Característica 1&#10;Característica 2"
                                              class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"></textarea>
                                </div>
                                <div class="sm:col-span-1 space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Orden de lista</label>
                                    <input type="number" id="form-service-order" name="sort_order" required value="0"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-2">
                                <button type="button" onclick="closeServiceForm()" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all cursor-pointer">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all shadow-lg shadow-blue-600/20 cursor-pointer">
                                    Guardar Servicio
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Services Table list -->
                    <div class="overflow-x-auto rounded-xl border border-slate-900 bg-slate-950/10">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-950 border-b border-slate-900 text-slate-400 font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-4 w-12 text-center">Icono</th>
                                    <th class="py-3.5 px-4">Título</th>
                                    <th class="py-3.5 px-4 hidden md:table-cell">Descripción</th>
                                    <th class="py-3.5 px-4 w-16 text-center">Orden</th>
                                    <th class="py-3.5 px-4 w-28 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-900/60">
                                <?php if (empty($services)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-slate-500">No hay servicios creados en la base de datos.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($services as $serv): ?>
                                        <tr class="hover:bg-slate-900/10 transition-colors">
                                            <td class="py-4 px-4 text-center text-blue-500 text-xl"><i class="bx <?php echo htmlspecialchars($serv['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></td>
                                            <td class="py-4 px-4 font-bold text-white"><?php echo htmlspecialchars($serv['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="py-4 px-4 text-slate-400 hidden md:table-cell max-w-xs truncate"><?php echo htmlspecialchars($serv['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="py-4 px-4 text-center text-slate-400"><?php echo (int)$serv['sort_order']; ?></td>
                                            <td class="py-4 px-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Edit Button -->
                                                    <button onclick="editService(<?php echo htmlspecialchars(json_encode($serv), ENT_QUOTES, 'UTF-8'); ?>)" 
                                                            class="p-1.5 bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white rounded border border-blue-900/20 hover:border-transparent transition-all cursor-pointer" title="Editar">
                                                        <i class="bx bx-edit text-sm"></i>
                                                    </button>
                                                    
                                                    <!-- Delete Form -->
                                                    <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?');" class="inline">
                                                        <input type="hidden" name="action" value="delete_service">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="service_id" value="<?php echo (int)$serv['id']; ?>">
                                                        <button type="submit" class="p-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white rounded border border-rose-900/20 hover:border-transparent transition-all cursor-pointer" title="Eliminar">
                                                            <i class="bx bx-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MODULE 3: Portfolio Dashboard -->
                <div id="tab-portfolio" class="tab-content tab-content glass-panel p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-900 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-white uppercase tracking-wider">Gestión del Portafolio</h2>
                            <p class="text-xs text-slate-400 mt-1">Sube y organiza tus proyectos de diseño y desarrollo web.</p>
                        </div>
                        <button onclick="openProjectForm()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all flex items-center space-x-1 cursor-pointer">
                            <i class="bx bx-plus text-base"></i>
                            <span>Nuevo Proyecto</span>
                        </button>
                    </div>

                    <!-- Add/Edit Project Form (Hidden by default) -->
                    <div id="project-form-panel" class="hidden p-6 border border-slate-800 rounded-xl bg-slate-950/50 space-y-4">
                        <h3 id="project-form-title" class="text-xs font-bold text-blue-400 uppercase tracking-wider">Crear Nuevo Proyecto</h3>
                        <form method="POST" action="dashboard.php" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="action" value="save_project">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" id="form-project-id" name="project_id" value="">
                            <input type="hidden" id="form-project-existing-img" name="existing_image" value="">

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Título</label>
                                    <input type="text" id="form-project-title" name="title" required
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Subtítulo</label>
                                    <input type="text" id="form-project-subtitle" name="subtitle" required
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Categoría</label>
                                    <select id="form-project-cat" name="category" class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-slate-400 focus:outline-none focus:border-blue-500">
                                        <option value="design">Diseño Visual</option>
                                        <option value="web">Desarrollo Web</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Descripción detallada (Sección Cine)</label>
                                <textarea id="form-project-desc" name="description" rows="4" required
                                          class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500 resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Etiquetas (separadas por comas)</label>
                                    <input type="text" id="form-project-tags" name="tags" placeholder="Ej. Figma, UI/UX, Vector"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Enlace del proyecto (External Link)</label>
                                    <input type="text" id="form-project-link" name="external_link" value="#"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                                <div class="sm:col-span-3 space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Archivo de Imagen (PNG, JPG, WEBP, GIF - Máx. 2MB)</label>
                                    <input type="file" id="form-project-file" name="image_file"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-1.5 px-3 text-xs text-slate-400 focus:outline-none focus:border-blue-500">
                                    <p id="form-project-img-help" class="text-[9px] text-slate-500 hidden mt-1"></p>
                                </div>
                                <div class="sm:col-span-1 space-y-1">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Orden</label>
                                    <input type="number" id="form-project-order" name="sort_order" required value="0"
                                           class="w-full bg-slate-950/40 border border-slate-800 rounded-lg py-2 px-3 text-xs text-white focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-2">
                                <button type="button" onclick="closeProjectForm()" class="px-4 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all cursor-pointer">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all shadow-lg shadow-blue-600/20 cursor-pointer">
                                    Guardar Proyecto
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Portfolio Grid List -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <?php if (empty($projects)): ?>
                            <div class="col-span-full py-8 text-center text-slate-500">No hay proyectos registrados en el portafolio.</div>
                        <?php else: ?>
                            <?php foreach ($projects as $proj): ?>
                                <div class="border border-slate-900 bg-slate-950/20 rounded-xl overflow-hidden flex flex-col justify-between group">
                                    <div class="relative aspect-video bg-black overflow-hidden border-b border-slate-900">
                                        <!-- Image loaded dynamically from secure serve_image script -->
                                        <img src="../serve_image.php?file=<?php echo htmlspecialchars($proj['image_path'], ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="<?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                             class="w-full h-full object-cover">
                                        <span class="absolute top-2 left-2 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider bg-slate-950/80 text-blue-400 border border-slate-800 rounded-md">
                                            <?php echo $proj['category'] === 'design' ? 'Diseño' : 'Web'; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="p-4 space-y-1.5 flex-grow">
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-indigo-400 block"><?php echo htmlspecialchars($proj['subtitle'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <h3 class="text-sm font-bold text-white leading-tight"><?php echo htmlspecialchars($proj['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed font-light"><?php echo htmlspecialchars($proj['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>

                                    <div class="p-4 pt-0 border-t border-slate-900/40 flex items-center justify-between mt-2">
                                        <span class="text-[10px] text-slate-500">Orden: <strong><?php echo (int)$proj['sort_order']; ?></strong></span>
                                        <div class="flex items-center space-x-1.5">
                                            <button onclick="editProject(<?php echo htmlspecialchars(json_encode($proj), ENT_QUOTES, 'UTF-8'); ?>)"
                                                    class="p-1.5 bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white rounded border border-blue-900/20 hover:border-transparent transition-all cursor-pointer text-xs" title="Editar">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            
                                            <form method="POST" action="dashboard.php" onsubmit="return confirm('¿Seguro que deseas eliminar este proyecto del portafolio?');" class="inline">
                                                <input type="hidden" name="action" value="delete_project">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="project_id" value="<?php echo (int)$proj['id']; ?>">
                                                <button type="submit" class="p-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white rounded border border-rose-900/20 hover:border-transparent transition-all cursor-pointer text-xs" title="Eliminar">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Administrative dashboard scripts -->
    <script>
        // Switch between layout tabs
        function switchTab(tabId, btnElement) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            // Show target
            document.getElementById(tabId).classList.add('active');

            // Deactivate all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600/20', 'text-blue-400', 'border-blue-800/40');
                btn.classList.add('hover:bg-slate-900', 'text-slate-400', 'hover:text-white', 'border-transparent');
            });

            // Activate current
            btnElement.classList.add('bg-blue-600/20', 'text-blue-400', 'border-blue-800/40');
            btnElement.classList.remove('hover:bg-slate-900', 'text-slate-400', 'hover:text-white', 'border-transparent');

            // Store active tab in localStorage
            localStorage.setItem('active_dashboard_tab', tabId);
        }

        // Keep active tab state on reload
        window.addEventListener('DOMContentLoaded', () => {
            const lastTab = localStorage.getItem('active_dashboard_tab');
            if (lastTab) {
                const btn = Array.from(document.querySelectorAll('.tab-btn')).find(b => b.getAttribute('onclick').includes(lastTab));
                if (btn) btn.click();
            }
        });

        // --- SERVICES FORM CONTROLS ---
        function openServiceForm() {
            document.getElementById('service-form-panel').classList.remove('hidden');
            document.getElementById('service-form-title').textContent = 'Crear Nuevo Servicio';
            
            // Clean inputs
            document.getElementById('form-service-id').value = '';
            document.getElementById('form-service-title').value = '';
            document.getElementById('form-service-icon').value = 'bx-layer';
            document.getElementById('form-service-desc').value = '';
            document.getElementById('form-service-bullets').value = '';
            document.getElementById('form-service-order').value = '0';
        }

        function closeServiceForm() {
            document.getElementById('service-form-panel').classList.add('hidden');
        }

        function editService(serv) {
            document.getElementById('service-form-panel').classList.remove('hidden');
            document.getElementById('service-form-title').textContent = 'Editar Servicio: ' + serv.title;
            
            document.getElementById('form-service-id').value = serv.id;
            document.getElementById('form-service-title').value = serv.title;
            document.getElementById('form-service-icon').value = serv.icon;
            document.getElementById('form-service-desc').value = serv.description;
            document.getElementById('form-service-bullets').value = serv.bullet_items;
            document.getElementById('form-service-order').value = serv.sort_order;
            
            // Scroll smoothly to form
            document.getElementById('service-form-panel').scrollIntoView({ behavior: 'smooth' });
        }

        // --- PORTFOLIO FORM CONTROLS ---
        function openProjectForm() {
            document.getElementById('project-form-panel').classList.remove('hidden');
            document.getElementById('project-form-title').textContent = 'Crear Nuevo Proyecto';
            
            // Clean inputs
            document.getElementById('form-project-id').value = '';
            document.getElementById('form-project-existing-img').value = '';
            document.getElementById('form-project-title').value = '';
            document.getElementById('form-project-subtitle').value = '';
            document.getElementById('form-project-desc').value = '';
            document.getElementById('form-project-tags').value = '';
            document.getElementById('form-project-link').value = '#';
            document.getElementById('form-project-cat').value = 'design';
            document.getElementById('form-project-order').value = '0';
            document.getElementById('form-project-file').required = true;
            document.getElementById('form-project-img-help').classList.add('hidden');
        }

        function closeProjectForm() {
            document.getElementById('project-form-panel').classList.add('hidden');
        }

        function editProject(proj) {
            document.getElementById('project-form-panel').classList.remove('hidden');
            document.getElementById('project-form-title').textContent = 'Editar Proyecto: ' + proj.title;
            
            document.getElementById('form-project-id').value = proj.id;
            document.getElementById('form-project-existing-img').value = proj.image_path;
            document.getElementById('form-project-title').value = proj.title;
            document.getElementById('form-project-subtitle').value = proj.subtitle;
            document.getElementById('form-project-desc').value = proj.description;
            document.getElementById('form-project-tags').value = proj.tags;
            document.getElementById('form-project-link').value = proj.external_link;
            document.getElementById('form-project-cat').value = proj.category;
            document.getElementById('form-project-order').value = proj.sort_order;
            
            // File is not mandatory when editing
            document.getElementById('form-project-file').required = false;
            
            // Show help message
            const help = document.getElementById('form-project-img-help');
            help.textContent = 'Actual: ' + proj.image_path + ' (deja vacío para conservar la misma imagen)';
            help.classList.remove('hidden');

            document.getElementById('project-form-panel').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

</body>
</html>
