<?php
define('DB_HOST',    getenv('DB_HOST')    ?: 'mysql');
define('DB_NAME',    getenv('DB_NAME')    ?: 'ifgaccess');
define('DB_USER',    getenv('DB_USER')    ?: 'ifgaccess');
define('DB_PASS',    getenv('DB_PASS')    ?: 'root');
define('DB_CHARSET', 'utf8');

class Database
{
    private static ?PDO $cont = null;

    public static function connect(): PDO
    {
        if (self::$cont === null) {
            try {
                self::$cont = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                error_log('Falha na conexão com o banco de dados: ' . $e->getMessage());
                http_response_code(500);
                exit('Erro interno. Tente novamente mais tarde.');
            }
        }
        return self::$cont;
    }

    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function create(string $table, array $fields): bool
    {
        $columns      = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        $sql  = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = self::connect()->prepare($sql);
        return $stmt->execute($fields);
    }

    public static function update(string $table, array $fields, array $where): int
    {
        $set  = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($fields)));
        $sql  = "UPDATE {$table} SET {$set} WHERE {$where[0]} = :__where";
        $data = array_merge($fields, ['__where' => $where[1]]);
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    public static function disconnect(): void
    {
        self::$cont = null;
    }
}
