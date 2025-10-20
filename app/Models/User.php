<?php

namespace App\Models;

/**
 * Model User
 */
class User
{
    /**
     * Busca usuário por ID
     */
    public static function findById(string $id): ?array
    {
        return Db::queryOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    /**
     * Busca usuário por email
     */
    public static function findByEmail(string $email): ?array
    {
        return Db::queryOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    /**
     * Lista todos os usuários
     */
    public static function all(): array
    {
        return Db::query('SELECT id, email, name, role, avatar_url, created_at, updated_at FROM users ORDER BY created_at DESC');
    }

    /**
     * Cria um novo usuário
     */
    public static function create(array $data): string
    {
        $id = bin2hex(random_bytes(12)); // 24 caracteres
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        Db::execute(
            'INSERT INTO users (id, email, password_hash, name, role, avatar_url) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $id,
                $data['email'],
                $passwordHash,
                $data['name'] ?? null,
                $data['role'] ?? 'USER',
                $data['avatar_url'] ?? null
            ]
        );
        
        return $id;
    }

    /**
     * Atualiza um usuário
     */
    public static function update(string $id, array $data): bool
    {
        $fields = [];
        $params = [];
        
        if (isset($data['email'])) {
            $fields[] = 'email = ?';
            $params[] = $data['email'];
        }
        
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }
        
        if (isset($data['role'])) {
            $fields[] = 'role = ?';
            $params[] = $data['role'];
        }
        
        if (isset($data['avatar_url'])) {
            $fields[] = 'avatar_url = ?';
            $params[] = $data['avatar_url'];
        }
        
        if (isset($data['password'])) {
            $fields[] = 'password_hash = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        
        return Db::execute($sql, $params) > 0;
    }

    /**
     * Remove um usuário
     */
    public static function delete(string $id): bool
    {
        return Db::execute('DELETE FROM users WHERE id = ?', [$id]) > 0;
    }

    /**
     * Verifica se a senha está correta
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Conta o total de usuários
     */
    public static function count(): int
    {
        $result = Db::queryOne('SELECT COUNT(*) as total FROM users');
        return (int) $result['total'];
    }
}
