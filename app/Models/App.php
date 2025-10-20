<?php

namespace App\Models;

/**
 * Model App
 */
class App
{
    /**
     * Busca app por ID
     */
    public static function findById(string $id): ?array
    {
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.id = ?';
        return Db::queryOne($sql, [$id]);
    }

    /**
     * Busca app por slug
     */
    public static function findBySlug(string $slug): ?array
    {
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.slug = ?';
        return Db::queryOne($sql, [$slug]);
    }

    /**
     * Lista todos os apps ativos
     */
    public static function allActive(): array
    {
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.active = 1 
                ORDER BY a.order ASC, a.name ASC';
        return Db::query($sql);
    }

    /**
     * Lista todos os apps (para admin)
     */
    public static function all(): array
    {
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                ORDER BY a.order ASC, a.name ASC';
        return Db::query($sql);
    }

    /**
     * Lista apps por categoria
     */
    public static function byCategory(string $categoryId, bool $activeOnly = true): array
    {
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE a.category_id = ?';
        
        if ($activeOnly) {
            $sql .= ' AND a.active = 1';
        }
        
        $sql .= ' ORDER BY a.order ASC, a.name ASC';
        
        return Db::query($sql, [$categoryId]);
    }

    /**
     * Busca apps por termo (nome, descrição, tags)
     */
    public static function search(string $term, bool $activeOnly = true): array
    {
        $term = '%' . $term . '%';
        $sql = 'SELECT a.*, c.name as category_name, c.slug as category_slug 
                FROM apps a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE (a.name LIKE ? OR a.description LIKE ? OR a.tags LIKE ?)';
        
        if ($activeOnly) {
            $sql .= ' AND a.active = 1';
        }
        
        $sql .= ' ORDER BY a.order ASC, a.name ASC';
        
        return Db::query($sql, [$term, $term, $term]);
    }

    /**
     * Cria um novo app
     */
    public static function create(array $data): string
    {
        $id = bin2hex(random_bytes(12));
        
        Db::execute(
            'INSERT INTO apps (id, name, slug, description, url, open_mode, allowlist_domains, icon, tags, active, `order`, category_id) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $id,
                $data['name'],
                $data['slug'],
                $data['description'] ?? null,
                $data['url'],
                $data['open_mode'] ?? 'INTERNAL',
                $data['allowlist_domains'] ?? null,
                $data['icon'] ?? null,
                $data['tags'] ?? null,
                $data['active'] ?? 1,
                $data['order'] ?? 0,
                $data['category_id']
            ]
        );
        
        return $id;
    }

    /**
     * Atualiza um app
     */
    public static function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [];
        
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }
        
        if (isset($data['slug'])) {
            $fields[] = 'slug = ?';
            $params[] = $data['slug'];
        }
        
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $params[] = $data['description'];
        }
        
        if (isset($data['url'])) {
            $fields[] = 'url = ?';
            $params[] = $data['url'];
        }
        
        if (isset($data['open_mode'])) {
            $fields[] = 'open_mode = ?';
            $params[] = $data['open_mode'];
        }
        
        if (isset($data['allowlist_domains'])) {
            $fields[] = 'allowlist_domains = ?';
            $params[] = $data['allowlist_domains'];
        }
        
        if (isset($data['icon'])) {
            $fields[] = 'icon = ?';
            $params[] = $data['icon'];
        }
        
        if (isset($data['tags'])) {
            $fields[] = 'tags = ?';
            $params[] = $data['tags'];
        }
        
        if (isset($data['active'])) {
            $fields[] = 'active = ?';
            $params[] = $data['active'];
        }
        
        if (isset($data['order'])) {
            $fields[] = '`order` = ?';
            $params[] = $data['order'];
        }
        
        if (isset($data['category_id'])) {
            $fields[] = 'category_id = ?';
            $params[] = $data['category_id'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = 'UPDATE apps SET ' . implode(', ', $fields) . ' WHERE id = ?';
        
        return Db::execute($sql, $params) > 0;
    }

    /**
     * Remove um app
     */
    public static function delete(string $id): bool
    {
        return Db::execute('DELETE FROM apps WHERE id = ?', [$id]) > 0;
    }

    /**
     * Conta o total de apps
     */
    public static function count(bool $activeOnly = false): int
    {
        $sql = 'SELECT COUNT(*) as total FROM apps';
        if ($activeOnly) {
            $sql .= ' WHERE active = 1';
        }
        $result = Db::queryOne($sql);
        return (int) $result['total'];
    }
}
