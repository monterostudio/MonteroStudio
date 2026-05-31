<?php
/**
 * MONTERO STUDIO - Secure Database Access Layer
 * Implements PDO prepared statements and graceful fallback logic.
 */

class DB {
    private static $conn = null;
    private static $connectionFailed = false;

    /**
     * Establish a secure PDO connection with error logging.
     * If the configuration is missing or connection fails, it returns null.
     */
    public static function getConnection() {
        if (self::$conn !== null) {
            return self::$conn;
        }

        if (self::$connectionFailed) {
            return null;
        }

        // 1. Try to read environment variables (especially for Vercel/production)
        $dbUrl = getenv('DATABASE_URL');
        $dbType = getenv('DB_TYPE') ?: '';
        $dbHost = getenv('DB_HOST') ?: '';
        $dbPort = getenv('DB_PORT') ?: '';
        $dbName = getenv('DB_NAME') ?: '';
        $dbUser = getenv('DB_USER') ?: '';
        $dbPass = getenv('DB_PASS') ?: '';

        if (!empty($dbUrl)) {
            // Parse connection string: postgresql://username:password@host:port/dbname?query
            $parsedUrl = parse_url($dbUrl);
            if ($parsedUrl) {
                $scheme = $parsedUrl['scheme'] ?? '';
                $dbType = ($scheme === 'postgres' || $scheme === 'postgresql') ? 'pgsql' : 'mysql';
                $dbHost = $parsedUrl['host'] ?? '';
                $dbPort = $parsedUrl['port'] ?? '';
                $dbUser = $parsedUrl['user'] ?? '';
                $dbPass = $parsedUrl['pass'] ?? '';
                $dbName = ltrim($parsedUrl['path'] ?? '', '/');
                
                // Decode URL-encoded credentials
                $dbUser = urldecode($dbUser);
                $dbPass = urldecode($dbPass);
            }
        }

        // 2. If environment variables are not set, fallback to db_config.php (local Laragon)
        if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
            $configPath = __DIR__ . '/db_config.php';
            if (file_exists($configPath)) {
                require_once $configPath;
                $dbType = defined('DB_TYPE') ? DB_TYPE : 'mysql';
                $dbHost = defined('DB_HOST') ? DB_HOST : '';
                $dbPort = defined('DB_PORT') ? DB_PORT : '';
                $dbName = defined('DB_NAME') ? DB_NAME : '';
                $dbUser = defined('DB_USER') ? DB_USER : '';
                $dbPass = defined('DB_PASS') ? DB_PASS : '';
            } else {
                self::$connectionFailed = true;
                return null;
            }
        }

        // Final check that we have the necessary credentials
        if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
            self::$connectionFailed = true;
            return null;
        }

        if (empty($dbType)) {
            $dbType = 'mysql';
        }

        try {
            if ($dbType === 'pgsql') {
                $portPart = !empty($dbPort) ? ";port=" . $dbPort : "";
                // Supabase requires sslmode=require
                $dsn = "pgsql:host=" . $dbHost . $portPart . ";dbname=" . $dbName . ";sslmode=require";
            } else {
                $portPart = !empty($dbPort) ? ";port=" . $dbPort : "";
                $dsn = "mysql:host=" . $dbHost . $portPart . ";dbname=" . $dbName . ";charset=utf8mb4";
            }

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$conn = new PDO($dsn, $dbUser, $dbPass, $options);
            return self::$conn;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            self::$connectionFailed = true;
            return null;
        }
    }

    /**
     * Get all general settings as an associative key-value array.
     */
    public static function getSettings() {
        $db = self::getConnection();
        if (!$db) {
            return [];
        }

        try {
            $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        } catch (PDOException $e) {
            error_log("Failed to fetch site settings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a specific setting value or return the default fallback.
     */
    public static function getSetting($key, $default = '') {
        static $settings = null;
        if ($settings === null) {
            $settings = self::getSettings();
        }
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * Get all services ordered by sort_order.
     */
    public static function getServices() {
        $db = self::getConnection();
        if (!$db) {
            return [];
        }

        try {
            $stmt = $db->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Failed to fetch services: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all portfolio projects ordered by sort_order.
     */
    public static function getProjects($category = null) {
        $db = self::getConnection();
        if (!$db) {
            return [];
        }

        try {
            if ($category) {
                $stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE category = ? ORDER BY sort_order ASC, id DESC");
                $stmt->execute([$category]);
            } else {
                $stmt = $db->query("SELECT * FROM portfolio_projects ORDER BY sort_order ASC, id DESC");
            }
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Failed to fetch portfolio projects: " . $e->getMessage());
            return [];
        }
    }
}
