<?php
/**
 * CyberTool360 - Router
 * 
 * Roteador simples e leve para a aplicação
 */

// Carrega configuração
require_once __DIR__ . '/../config.php';

// Autoload simples
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AppController;
use App\Controllers\AdminController;
use App\Helpers\Response;
use App\Helpers\Security;

// Headers de segurança padrão
Security::sendSecurityHeaders();

// Captura a rota
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove trailing slash (exceto root)
if ($uri !== '/' && substr($uri, -1) === '/') {
    $uri = rtrim($uri, '/');
}

// Rotas
try {
    // ========== HOME ==========
    if ($uri === '/' && $method === 'GET') {
        HomeController::index();
    }
    
    // ========== AUTH ==========
    elseif ($uri === '/signin' && $method === 'GET') {
        AuthController::showSignIn();
    }
    elseif ($uri === '/signin' && $method === 'POST') {
        AuthController::signIn();
    }
    elseif ($uri === '/signup' && $method === 'GET') {
        AuthController::showSignUp();
    }
    elseif ($uri === '/signup' && $method === 'POST') {
        AuthController::signUp();
    }
    elseif ($uri === '/logout' && $method === 'POST') {
        AuthController::logout();
    }
    
    // ========== APPS ==========
    elseif ($uri === '/internal' && $method === 'GET') {
        AppController::internal();
    }
    elseif ($uri === '/open' && $method === 'GET') {
        AppController::open();
    }
    
    // ========== PINS ==========
    elseif ($uri === '/pins/add' && $method === 'POST') {
        AppController::addPin();
    }
    elseif ($uri === '/pins/remove' && $method === 'POST') {
        AppController::removePin();
    }
    elseif ($uri === '/pins/toggle' && $method === 'POST') {
        AppController::togglePin();
    }
    
    // ========== ADMIN ==========
    elseif ($uri === '/admin' && $method === 'GET') {
        AdminController::dashboard();
    }
    
    // Apps Management
    elseif ($uri === '/admin/apps' && $method === 'GET') {
        AdminController::listApps();
    }
    elseif ($uri === '/admin/apps/create' && $method === 'POST') {
        AdminController::createApp();
    }
    elseif ($uri === '/admin/apps/update' && $method === 'POST') {
        AdminController::updateApp();
    }
    elseif ($uri === '/admin/apps/delete' && $method === 'POST') {
        AdminController::deleteApp();
    }
    
    // Categories Management
    elseif ($uri === '/admin/categories' && $method === 'GET') {
        AdminController::listCategories();
    }
    elseif ($uri === '/admin/categories/create' && $method === 'POST') {
        AdminController::createCategory();
    }
    elseif ($uri === '/admin/categories/update' && $method === 'POST') {
        AdminController::updateCategory();
    }
    elseif ($uri === '/admin/categories/delete' && $method === 'POST') {
        AdminController::deleteCategory();
    }
    
    // Users Management (Admin only)
    elseif ($uri === '/admin/users' && $method === 'GET') {
        AdminController::listUsers();
    }
    elseif ($uri === '/admin/users/update-role' && $method === 'POST') {
        AdminController::updateUserRole();
    }
    elseif ($uri === '/admin/users/delete' && $method === 'POST') {
        AdminController::deleteUser();
    }
    
    // Metrics (Admin only)
    elseif ($uri === '/admin/metrics' && $method === 'GET') {
        AdminController::metrics();
    }
    
    // ========== 404 ==========
    else {
        Response::notFound();
    }
    
} catch (Exception $e) {
    // Em produção, registrar o erro e mostrar mensagem genérica
    if (APP_ENV === 'development') {
        echo '<pre>';
        echo 'Erro: ' . $e->getMessage() . "\n";
        echo 'Arquivo: ' . $e->getFile() . ':' . $e->getLine() . "\n\n";
        echo $e->getTraceAsString();
        echo '</pre>';
    } else {
        Response::serverError();
    }
}
