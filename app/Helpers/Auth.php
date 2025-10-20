<?php

namespace App\Helpers;

use App\Models\User;

/**
 * Helper de Autenticação
 */
class Auth
{
    /**
     * Verifica se o usuário está autenticado
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Retorna o ID do usuário autenticado
     */
    public static function id(): ?string
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Retorna os dados do usuário autenticado
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        
        if (!isset($_SESSION['user_data'])) {
            $user = User::findById($_SESSION['user_id']);
            if (!$user) {
                self::logout();
                return null;
            }
            $_SESSION['user_data'] = $user;
        }
        
        return $_SESSION['user_data'];
    }

    /**
     * Retorna o role do usuário autenticado
     */
    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    /**
     * Verifica se o usuário tem um role específico
     */
    public static function hasRole(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Verifica se o usuário tem um dos roles fornecidos
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array(self::role(), $roles);
    }

    /**
     * Verifica se o usuário é ADMIN
     */
    public static function isAdmin(): bool
    {
        return self::hasRole('ADMIN');
    }

    /**
     * Verifica se o usuário é MANAGER ou superior
     */
    public static function isManager(): bool
    {
        return self::hasAnyRole(['ADMIN', 'MANAGER']);
    }

    /**
     * Faz login do usuário
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;
    }

    /**
     * Faz logout do usuário
     */
    public static function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
    }

    /**
     * Requer autenticação (redireciona para login se não autenticado)
     */
    public static function require(): void
    {
        if (!self::check()) {
            Response::redirect('/signin?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        }
    }

    /**
     * Requer role específico
     */
    public static function requireRole(string $role): void
    {
        self::require();
        
        if (!self::hasRole($role)) {
            Response::forbidden('Acesso negado. Você não tem permissão para acessar esta página.');
        }
    }

    /**
     * Requer um dos roles
     */
    public static function requireAnyRole(array $roles): void
    {
        self::require();
        
        if (!self::hasAnyRole($roles)) {
            Response::forbidden('Acesso negado. Você não tem permissão para acessar esta página.');
        }
    }

    /**
     * Requer ADMIN
     */
    public static function requireAdmin(): void
    {
        self::requireRole('ADMIN');
    }

    /**
     * Requer MANAGER ou superior
     */
    public static function requireManager(): void
    {
        self::requireAnyRole(['ADMIN', 'MANAGER']);
    }

    /**
     * Redireciona para home se já autenticado (para páginas de login)
     */
    public static function guest(): void
    {
        if (self::check()) {
            Response::redirect('/');
        }
    }
}
