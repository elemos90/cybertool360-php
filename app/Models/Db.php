<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Wrapper simples para PDO com prepared statements
 */
class Db
{
    private static ?PDO $pdo = null;

    /**
     * Obtém a conexão PDO (Singleton)
     */
    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Em produção, registrar o erro sem expor detalhes
                if (APP_ENV === 'development') {
                    die('Erro de conexão: ' . $e->getMessage());
                }
                die('Erro ao conectar ao banco de dados.');
            }
        }
        return self::$pdo;
    }

    /**
     * Executa uma query SELECT e retorna todos os resultados
     */
    public static function query(string $sql, array $params = []): array
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executa uma query SELECT e retorna um único resultado
     */
    public static function queryOne(string $sql, array $params = []): ?array
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Executa uma query INSERT, UPDATE ou DELETE
     * Retorna o número de linhas afetadas
     */
    public static function execute(string $sql, array $params = []): int
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Retorna o último ID inserido
     */
    public static function lastInsertId(): string
    {
        return self::connect()->lastInsertId();
    }

    /**
     * Inicia uma transação
     */
    public static function beginTransaction(): bool
    {
        return self::connect()->beginTransaction();
    }

    /**
     * Confirma uma transação
     */
    public static function commit(): bool
    {
        return self::connect()->commit();
    }

    /**
     * Reverte uma transação
     */
    public static function rollback(): bool
    {
        return self::connect()->rollBack();
    }
}
