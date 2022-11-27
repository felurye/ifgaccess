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
		die('Init function is not allowed');
	}

	public static function connect()
	{
		// One connection through whole application
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
