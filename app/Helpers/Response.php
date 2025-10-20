<?php

namespace App\Helpers;

/**
 * Helper de Respostas HTTP
 */
class Response
{
    /**
     * Redireciona para uma URL
     */
    public static function redirect(string $url, int $code = 302): void
    {
        header("Location: {$url}", true, $code);
        exit;
    }

    /**
     * Retorna JSON
     */
    public static function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Retorna erro JSON
     */
    public static function jsonError(string $message, int $code = 400, array $errors = []): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Retorna sucesso JSON
     */
    public static function jsonSuccess(string $message, mixed $data = null, int $code = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Página de erro 404
     */
    public static function notFound(string $message = 'Página não encontrada'): void
    {
        http_response_code(404);
        self::renderError(404, $message);
        exit;
    }

    /**
     * Página de erro 403
     */
    public static function forbidden(string $message = 'Acesso negado'): void
    {
        http_response_code(403);
        self::renderError(403, $message);
        exit;
    }

    /**
     * Página de erro 500
     */
    public static function serverError(string $message = 'Erro interno do servidor'): void
    {
        http_response_code(500);
        self::renderError(500, $message);
        exit;
    }

    /**
     * Renderiza página de erro
     */
    private static function renderError(int $code, string $message): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="pt">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Erro <?= $code ?> - <?= APP_NAME ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-50 dark:bg-gray-900">
            <div class="min-h-screen flex items-center justify-center px-4">
                <div class="text-center">
                    <h1 class="text-9xl font-bold text-sky-500"><?= $code ?></h1>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mt-4"><?= htmlspecialchars($message) ?></p>
                    <a href="/" class="mt-8 inline-block px-6 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
                        Voltar ao início
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    /**
     * Renderiza uma view
     */
    public static function view(string $view, array $data = []): void
    {
        extract($data);
        
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            self::serverError("View não encontrada: {$view}");
        }
        
        require $viewFile;
        exit;
    }

    /**
     * Define mensagem flash
     */
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Obtém mensagem flash
     */
    public static function getFlash(string $key): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Verifica se existe mensagem flash
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }
}
