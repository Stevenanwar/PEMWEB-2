<?php

require_once 'config.php';

class Connection
{
    public static function make()
    {
        $host = 'localhost';
        $database = 'db_koperasi';
        $user = 'root';
        $password = '';

        $dsn = "mysql:host=$host;dbname=$database;charset=UTF8";

        try {
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            return new PDO ($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
