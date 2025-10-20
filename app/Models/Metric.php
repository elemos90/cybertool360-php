<?php

namespace App\Models;

/**
 * Model Metric
 */
class Metric
{
    /**
     * Registra uma abertura de app
     */
    public static function logOpen(string $appId, ?string $userId = null): void
    {
        $id = bin2hex(random_bytes(12));
        
        Db::execute(
            'INSERT INTO metrics (id, app_id, user_id, user_agent, referrer) VALUES (?, ?, ?, ?, ?)',
            [
                $id,
                $appId,
                $userId,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $_SERVER['HTTP_REFERER'] ?? null
            ]
        );
    }

    /**
     * Top apps por aberturas (últimos N dias)
     */
    public static function topApps(int $days = 7, int $limit = 10): array
    {
        $sql = 'SELECT a.id, a.name, a.slug, a.icon, COUNT(m.id) as open_count 
                FROM apps a 
                INNER JOIN metrics m ON a.id = m.app_id 
                WHERE m.opened_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                GROUP BY a.id, a.name, a.slug, a.icon 
                ORDER BY open_count DESC 
                LIMIT ?';
        
        return Db::query($sql, [$days, $limit]);
    }

    /**
     * Aberturas por app (últimos N dias)
     */
    public static function appStats(string $appId, int $days = 7): array
    {
        $sql = 'SELECT DATE(opened_at) as date, COUNT(*) as count 
                FROM metrics 
                WHERE app_id = ? AND opened_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                GROUP BY DATE(opened_at) 
                ORDER BY date ASC';
        
        return Db::query($sql, [$appId, $days]);
    }

    /**
     * Total de aberturas por app (últimos N dias)
     */
    public static function appTotalOpens(string $appId, int $days = 7): int
    {
        $sql = 'SELECT COUNT(*) as total 
                FROM metrics 
                WHERE app_id = ? AND opened_at >= DATE_SUB(NOW(), INTERVAL ? DAY)';
        
        $result = Db::queryOne($sql, [$appId, $days]);
        return (int) $result['total'];
    }

    /**
     * Sumário geral de métricas
     */
    public static function summary(int $days = 7): array
    {
        $sql = 'SELECT 
                COUNT(*) as total_opens,
                COUNT(DISTINCT app_id) as unique_apps,
                COUNT(DISTINCT user_id) as unique_users
                FROM metrics 
                WHERE opened_at >= DATE_SUB(NOW(), INTERVAL ? DAY)';
        
        return Db::queryOne($sql, [$days]) ?? [
            'total_opens' => 0,
            'unique_apps' => 0,
            'unique_users' => 0
        ];
    }

    /**
     * Aberturas por dia (últimos N dias)
     */
    public static function dailyOpens(int $days = 7): array
    {
        $sql = 'SELECT DATE(opened_at) as date, COUNT(*) as count 
                FROM metrics 
                WHERE opened_at >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                GROUP BY DATE(opened_at) 
                ORDER BY date ASC';
        
        return Db::query($sql, [$days]);
    }

    /**
     * Remove métricas antigas (limpeza)
     */
    public static function cleanOld(int $days = 90): int
    {
        return Db::execute(
            'DELETE FROM metrics WHERE opened_at < DATE_SUB(NOW(), INTERVAL ? DAY)',
            [$days]
        );
    }
}
