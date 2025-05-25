<?php
declare(strict_types=1);

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'sqlite:' . __DIR__ . '/database.db';
            self::$instance = new PDO($dsn);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('PRAGMA foreign_keys = ON;');
        }
        return self::$instance;
    }

    private function __construct() {}
}