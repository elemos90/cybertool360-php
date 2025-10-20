<?php

namespace App\Helpers;

/**
 * Helper de Segurança
 */
class Security
{
    /**
     * Gera um ID único curto
     */
    public static function generateId(int $bytes = 12): string
    {
        return bin2hex(random_bytes($bytes));
    }

    /**
     * Gera token CSRF
     */
    public static function generateCsrfToken(): string
    {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * Verifica token CSRF
     */
    public static function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    /**
     * Sanitiza string
     */
    public static function sanitize(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida URL
     */
    public static function isValidUrl(string $url): bool
    {
        if (empty($url)) {
            return false;
        }
        
        // Rejeita javascript:, data:, etc
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array(strtolower($scheme ?? ''), ['http', 'https'])) {
            return false;
        }
        
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Valida email
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Gera slug a partir de string
     */
    public static function slugify(string $str): string
    {
        $str = mb_strtolower($str, 'UTF-8');
        
        // Substituições de caracteres acentuados
        $replacements = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n'
        ];
        
        $str = strtr($str, $replacements);
        $str = preg_replace('/[^a-z0-9]+/', '-', $str);
        $str = trim($str, '-');
        
        return $str;
    }

    /**
     * Constrói frame-src para CSP
     */
    public static function buildFrameSrc(string $allowlist = ''): string
    {
        $sources = ["'self'"];
        
        if (!empty($allowlist)) {
            $domains = array_filter(array_map('trim', explode(',', $allowlist)));
            foreach ($domains as $domain) {
                if (str_starts_with($domain, 'http://') || str_starts_with($domain, 'https://')) {
                    $sources[] = $domain;
                } else {
                    $sources[] = "https://{$domain}";
                }
            }
        }
        
        return implode(' ', array_unique($sources));
    }

    /**
     * Envia headers CSP para página internal
     */
    public static function sendCspForInternal(string $frameSrc): void
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            "img-src 'self' data: https:",
            "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
            "connect-src 'self' https:",
            "frame-src {$frameSrc}"
        ];
        
        header('Content-Security-Policy: ' . implode('; ', $directives));
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: no-referrer');
        header('X-Content-Type-Options: nosniff');
    }

    /**
     * Envia headers de segurança padrão
     */
    public static function sendSecurityHeaders(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: no-referrer-when-downgrade');
        
        // CSP básico (permitindo Tailwind CDN, Alpine, e Lucide)
        $csp = [
            "default-src 'self'",
            "style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com",
            "img-src 'self' data: https:",
            "connect-src 'self' https:",
            "font-src 'self' data:",
            "frame-ancestors 'none'",
            "object-src 'none'"
        ];
        
        header('Content-Security-Policy: ' . implode('; ', $csp));
    }

    /**
     * Rate limiting simples para login (usa arquivo)
     */
    public static function checkRateLimit(string $identifier): bool
    {
        $lockFile = sys_get_temp_dir() . '/cybertool360_ratelimit_' . md5($identifier) . '.lock';
        
        if (!file_exists($lockFile)) {
            file_put_contents($lockFile, json_encode([
                'attempts' => 1,
                'first_attempt' => time()
            ]));
            return true;
        }
        
        $data = json_decode(file_get_contents($lockFile), true);
        
        // Se passou o tempo de lockout, reseta
        if (time() - $data['first_attempt'] > LOGIN_LOCKOUT_TIME) {
            file_put_contents($lockFile, json_encode([
                'attempts' => 1,
                'first_attempt' => time()
            ]));
            return true;
        }
        
        // Se atingiu o limite, bloqueia
        if ($data['attempts'] >= LOGIN_MAX_ATTEMPTS) {
            return false;
        }
        
        // Incrementa tentativas
        $data['attempts']++;
        file_put_contents($lockFile, json_encode($data));
        
        return true;
    }

    /**
     * Limpa rate limit após login bem-sucedido
     */
    public static function clearRateLimit(string $identifier): void
    {
        $lockFile = sys_get_temp_dir() . '/cybertool360_ratelimit_' . md5($identifier) . '.lock';
        if (file_exists($lockFile)) {
            unlink($lockFile);
        }
    }
}
