<?php

namespace App\Models;

/**
 * Model Category
 */
class Category
{
    /**
     * Busca categoria por ID
     */
    public static function findById(string $id): ?array
    {
        return Db::queryOne('SELECT * FROM categories WHERE id = ?', [$id]);
    }

    /**
     * Busca categoria por slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return Db::queryOne('SELECT * FROM categories WHERE slug = ?', [$slug]);
    }

    /**
     * Lista todas as categorias
     */
    public static function all(): array
    {
        return Db::query('SELECT * FROM categories ORDER BY `order` ASC, name ASC');
    }

    /**
     * Lista categorias com contagem de apps
     */
    public static function withAppCount(bool $activeOnly = true): array
    {
        $sql = 'SELECT c.*, COUNT(a.id) as app_count 
                FROM categories c 
                LEFT JOIN apps a ON c.id = a.category_id';
        
        if ($activeOnly) {
            $sql .= ' AND a.active = 1';
        }
        
        $sql .= ' GROUP BY c.id ORDER BY c.order ASC, c.name ASC';
        
        return Db::query($sql);
    }

    /**
     * Cria uma nova categoria
     */
    public static function create(array $data): string
    {
        $id = bin2hex(random_bytes(12));
        
        Db::execute(
            'INSERT INTO categories (id, name, slug, icon, `order`) VALUES (?, ?, ?, ?, ?)',
            [
                $id,
                $data['name'],
                $data['slug'],
                $data['icon'] ?? null,
                $data['order'] ?? 0
            ]
        );
        
        return $id;
    }

    /**
     * Atualiza uma categoria
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
        
        if (isset($data['icon'])) {
            $fields[] = 'icon = ?';
            $params[] = $data['icon'];
        }
        
        if (isset($data['order'])) {
            $fields[] = '`order` = ?';
            $params[] = $data['order'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = 'UPDATE categories SET ' . implode(', ', $fields) . ' WHERE id = ?';
        
        return Db::execute($sql, $params) > 0;
    }

    /**
     * Remove uma categoria
     */
    public static function delete(string $id): bool
    {
        return Db::execute('DELETE FROM categories WHERE id = ?', [$id]) > 0;
    }

    /**
     * Conta o total de categorias
     */
    public static function count(): int
    {
        $result = Db::queryOne('SELECT COUNT(*) as total FROM categories');
        return (int) $result['total'];
    }
}
