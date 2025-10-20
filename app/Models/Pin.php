<?php

namespace App\Models;

/**
 * Model Pin (Favoritos)
 */
class Pin
{
    /**
     * Lista apps favoritados de um usuário
     */
    public static function userPins(string $userId): array
    {
        $sql = 'SELECT p.*, a.*, c.name as category_name, c.slug as category_slug 
                FROM pins p 
                INNER JOIN apps a ON p.app_id = a.id 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE p.user_id = ? AND a.active = 1 
                ORDER BY p.order ASC';
        
        return Db::query($sql, [$userId]);
    }

    /**
     * Verifica se um app está favoritado pelo usuário
     */
    public static function isPinned(string $userId, string $appId): bool
    {
        $result = Db::queryOne(
            'SELECT id FROM pins WHERE user_id = ? AND app_id = ?',
            [$userId, $appId]
        );
        return $result !== null;
    }

    /**
     * Adiciona um app aos favoritos
     */
    public static function add(string $userId, string $appId): bool
    {
        // Verifica se já existe
        if (self::isPinned($userId, $appId)) {
            return false;
        }
        
        $id = bin2hex(random_bytes(12));
        
        // Pega o próximo order
        $result = Db::queryOne(
            'SELECT MAX(`order`) as max_order FROM pins WHERE user_id = ?',
            [$userId]
        );
        $order = ($result['max_order'] ?? 0) + 10;
        
        Db::execute(
            'INSERT INTO pins (id, user_id, app_id, `order`) VALUES (?, ?, ?, ?)',
            [$id, $userId, $appId, $order]
        );
        
        return true;
    }

    /**
     * Remove um app dos favoritos
     */
    public static function remove(string $userId, string $appId): bool
    {
        return Db::execute(
            'DELETE FROM pins WHERE user_id = ? AND app_id = ?',
            [$userId, $appId]
        ) > 0;
    }

    /**
     * Remove todos os pins de um usuário
     */
    public static function clearUser(string $userId): bool
    {
        return Db::execute('DELETE FROM pins WHERE user_id = ?', [$userId]) > 0;
    }

    /**
     * Conta quantos pins um usuário tem
     */
    public static function countUser(string $userId): int
    {
        $result = Db::queryOne(
            'SELECT COUNT(*) as total FROM pins WHERE user_id = ?',
            [$userId]
        );
        return (int) $result['total'];
    }
}
