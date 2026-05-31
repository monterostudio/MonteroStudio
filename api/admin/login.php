<?php
/**
 * MONTERO STUDIO - Secure Admin Login
 * Implements CSRF validation, brute-force delay, and secure session management.
 */

require_once dirname(__DIR__) . '/includes/admin_auth.php';
require_once dirname(__DIR__) . '/includes/db.php';

// Redirect if already authenticated
if (is_admin_logged_in()) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$maxAttempts = 5;
$lockoutTime = 30; // 30 seconds lockout

// Lockout status check bypassed for stateless serverless compatibility

// Process login post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // 1. Validate CSRF
    if (!validate_admin_csrf($csrfToken)) {
        $error = "Error de seguridad: Token de validación inválido. Por favor, recarga la página.";
    } 
    // 2. Validate inputs
    elseif (empty($username) || empty($password)) {
        $error = "Por favor, ingresa tu usuario y contraseña.";
    } else {
        // Enforce a small delay to slow down brute force attacks
        usleep(1000000); // 1.0 second delay

        $db = DB::getConnection();
        if (!$db) {
            $error = "La base de datos no está configurada o no está disponible. Corre el asistente de instalación en setup.php.";
        } else {
            try {
                // Fetch user
                $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Set stateless login cookie
                    set_admin_login_cookie($user['username']);

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Credenciales incorrectas.";
                }
            } catch (PDOException $e) {
                error_log("Login query failed: " . $e->getMessage());
                $error = "Ocurrió un error en el servidor.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MONTERO STUDIO</title>
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
        h1, h2 {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex items-center justify-center p-4">

    <!-- Glowing Background Spotlights -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div class="w-full max-w-md glass-panel p-8 rounded-2xl shadow-2xl relative z-10">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight uppercase">MONTERO STUDIO</h1>
            <p class="text-xs text-blue-400 font-semibold tracking-widest uppercase mt-1">Panel de Control</p>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 bg-rose-950/40 border border-rose-900/60 text-rose-300 text-sm rounded-lg flex items-start space-x-2">
                <i class="bx bx-error-circle text-lg mt-0.5"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="login.php" class="space-y-5">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="space-y-2">
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Usuario</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-lg">
                        <i class="bx bx-user"></i>
                    </span>
                    <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario"
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 pl-10 pr-4 text-sm text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-lg">
                        <i class="bx bx-lock-alt"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña"
                           class="w-full bg-slate-950/50 border border-slate-800 rounded-lg py-3 pl-10 pr-4 text-sm text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" <?php echo ($error && strpos($error, 'bloqueada') !== false) ? 'disabled' : ''; ?>
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-800 disabled:text-slate-500 text-white font-bold rounded-lg text-xs uppercase tracking-wider transition-all cursor-pointer shadow-lg shadow-blue-600/20 active:scale-98">
                    Iniciar Sesión
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a href="../#home" class="text-xs text-slate-500 hover:text-blue-400 transition-colors">
                <i class="bx bx-left-arrow-alt align-middle mr-0.5"></i> Volver al sitio principal
            </a>
        </div>

    </div>

</body>
</html>
