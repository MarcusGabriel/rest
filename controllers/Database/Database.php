<?php

namespace controllers\Database;

use PDO;

class Database {

    protected static $instance;
    private $driver = 'mysql';
    private $host = 'localhost';
    private $dbname = 'sysfornecedor';
    private $root = 'root';
    private $pass = 'flexpeak@2017';
    # Informações sobre o sistema
    private $sistema_titulo = "SysFornecedor";
    private $sistema_email = "email ainda nao definido";

    public function __construct() {
        try {
            self::$instance = new PDO($this->driver . ":host=" . $this->host . ";dbname=" . $this->dbname, $this->root, $this->pass);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('SET NAMES utf8');
        } catch (Exception $e) {

            mail($this->sistema_email, "PDOException em $this->sistema_titulo", $e->getMessage());

            die("Connection Error: " . $e->getMessage());
        }
    }

    public static function getConnection() {
        if (!self::$instance) {
            new Database();
        }
        return self::$instance;
    }

}
