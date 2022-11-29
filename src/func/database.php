<?php
define('DB_TYPE', 'mysql');
define('DB_HOST', 'mysql');
define('DB_NAME', 'ifgaccess');
define('DB_USER', 'ifgaccess');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8');

class Database
{
	private static $cont  = null;

	public function __construct()
	{
		$this->connect();
	}

	public static function query($sql)
	{
		self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = self::$cont->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function create($table, $fields)
	{
		if (!is_array($fields)) {
			$fields = (array) $fields;
		}

		$sql = "insert into {$table}";
		$sql .= "(" . implode(',', array_keys($fields)) . ")";
		$sql .= " values(" . ":" . implode(',:', array_keys($fields)) . ")";

		$insert = self::$cont->prepare($sql);

		return $insert->execute($fields);
	}

	public static function update($table, $fields, $where)
	{
		if (!is_array($fields)) {
			$fields = (array) $fields;
		}

		$data = array_map(function ($field) {
			return "{$field} = :{$field}";
		}, array_keys($fields));

		$sql = "update {$table} set ";
		$sql .= implode(',', $data);
		$sql .= " where {$where[0]} = :{$where[0]}";

		$data = array_merge($fields, [$where[0] => $where[1]]);
		
		$pdo = self::connect();
		$update = $pdo->prepare($sql);
		$update->execute($data);

		return $update->rowCount();
	}

	public static function connect()
	{
		if (null == self::$cont) {
			try {
				self::$cont =  new PDO("mysql:host=" . DB_HOST . ";" . "dbname=" . DB_NAME, DB_USER, DB_PASS);
			} catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		return self::$cont;
	}

	public static function disconnect()
	{
		self::$cont = null;
	}
}
