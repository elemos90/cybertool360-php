<?php
/**
 * CyberTool360 - Configuração
 * 
 * IMPORTANTE: Configure as credenciais do banco de dados e outras variáveis de ambiente.
 * Em produção, proteja este arquivo e nunca o exponha publicamente.
 */

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'cybertool360');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configurações da Aplicação
define('APP_NAME', 'CyberTool360');
define('APP_URL', 'http://localhost');
define('APP_ENV', 'development'); // development | production

// Segurança
define('SESSION_LIFETIME', 7200); // 2 horas em segundos
define('CSRF_TOKEN_NAME', '_token');
define('PASSWORD_MIN_LENGTH', 8);

// Rate Limiting (Login)
define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutos

// Paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', APP_PATH . '/Views');

// Timezone
date_default_timezone_set('Africa/Luanda');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/logs/php-errors.log');
}

// Session Configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? '1' : '0');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
