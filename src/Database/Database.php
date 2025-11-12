<?php

namespace Config;

use PDO;
use PDOException;
use Error\APIException;

class Database
{
	private static string $database = __DIR__ . '/database.sqlite';
	private static ?PDO $connection = null;

	public static function getConnection(): PDO
	{
		if (self::$connection === null) {
			try {
				$dsn = "sqlite:" . self::$database;
				self::$connection = new PDO($dsn);

				self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

				self::$connection->exec("PRAGMA foreign_keys = ON;");
			} catch (PDOException $e) {
				throw new APIException("Erro ao conectar ao banco de dados: " . $e->getMessage(), 500);	
			}
		}

		return self::$connection;
	}
}
